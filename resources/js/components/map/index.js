const PHOTON_API = 'https://photon.komoot.io';
const ICONS = {
    bus: '<svg class="text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor"><path d="M256.001 0C153.683 0 70.737 82.947 70.737 185.264 70.737 332.734 256.001 512 256.001 512s185.262-179.266 185.262-326.736C441.263 82.947 358.318 0 256.001 0zm0 320.453c-74.664 0-135.189-60.527-135.189-135.189s60.525-135.19 135.189-135.19c74.662 0 135.189 60.527 135.189 135.189s-60.527 135.19-135.189 135.19zm74.378-179.307h-5.738v-18.85c0-14.469-12.624-21.866-26.509-21.866h-84.264c-13.885 0-26.508 7.397-26.508 21.866v18.85h-5.739c-3.65 0-6.609 2.868-6.609 6.405v32.455c0 3.538 2.959 6.405 6.609 6.405h5.739v52.626h6.933v14.278c0 5.021 4.2 9.091 9.381 9.091h12.789c5.182 0 9.382-4.07 9.382-9.091v-14.278h59.947v14.278c0 5.021 4.2 9.091 9.381 9.091h12.79c5.181 0 9.38-4.07 9.38-9.091v-14.278h7.299v-52.626h5.738c3.65 0 6.609-2.867 6.609-6.405v-32.455c-.001-3.537-2.96-6.405-6.61-6.405zm-122.07 84.955c-6.097 0-11.04-4.79-11.04-10.699 0-5.911 4.943-10.701 11.04-10.701 6.098 0 11.041 4.79 11.041 10.701 0 5.909-4.943 10.699-11.041 10.699zm39.812-38.244H199.02v-56.924h49.101zm15.758 0v-56.924h49.101v56.924zm39.812 38.244c-6.098 0-11.042-4.79-11.042-10.699 0-5.911 4.944-10.701 11.042-10.701 6.097 0 11.04 4.79 11.04 10.701 0 5.909-4.943 10.699-11.04 10.699z" /></svg>',
    busStop: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"><path class="text-red-500 dark:text-red-400" d="M12 22s7-5.15 7-12a7 7 0 10-14 0c0 6.85 7 12 7 12z" fill="currentColor"/><path class="text-white" d="M12 13.25a3.25 3.25 0 110-6.5 3.25 3.25 0 010 6.5z" fill="currentColor"/></svg>',
    user: '<svg class="text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="3" fill="white"></circle></svg>'
};
const DEFAULT_CENTER = { lat: 35.565, lng: 45.433 };
const MAP_STYLES = {
    standard: { id: 'standard', name: 'Standard', url: (isDark) => `https://{s}.basemaps.cartocdn.com/${isDark ? 'dark' : 'light'}_all/{z}/{x}/{y}{r}.png`, maxZoom: 20 },
    streets: { id: 'streets', name: 'Streets', url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', maxZoom: 20 },
    vivid: { id: 'vivid', name: 'Vivid', url: 'https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', maxZoom: 20 },
    satellite: { id: 'satellite', name: 'Satellite', url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', maxZoom: 20 },
    terrain: { id: 'terrain', name: 'Terrain', url: 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', maxZoom: 20 }
};
const MAP_STYLE_STORAGE_KEY = 'Rega.mapStyle';

const isDarkTheme = () => document.documentElement.classList.contains('dark');
const toFloat = (val, fallback = null) => Number.isFinite(parseFloat(val)) ? parseFloat(val) : fallback;
const escapeHtml = (val) => String(val ?? '').replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[m]);

function extractGeoCoords(geom) {
    if (!geom) return [];
    if (geom.type === 'FeatureCollection') return geom.features.flatMap(f => extractGeoCoords(f.geometry));
    if (geom.type === 'Feature') return extractGeoCoords(geom.geometry);
    if (geom.type === 'LineString') return geom.coordinates;
    if (geom.type === 'MultiLineString') return geom.coordinates.flat();
    return [];
}

function buildMarkerElement(svg, selected = false, size = 48) {
    const el = document.createElement('div');
    el.className = 'custom-marker-icon map-marker-icon drop-shadow-lg';
    el.style = `width:${size}px; height:${size}px; will-change:transform;`;
    const ring = selected ? '<span class="map-marker__pulse"></span><span class="map-marker__ring"></span>' : '';
    el.innerHTML = `<div class="map-marker${selected ? ' is-selected' : ''}">${ring}<div class="map-marker__svg w-full h-full">${svg}</div></div>`;
    return el;
}

function MapComponent(options) {
    return {
        map: null, mapEl: null, locateControl: null, routeMarkers: [], icons: {}, userLocationMarker: null,
        marks: [], marksInfo: {}, providedMarks: [], _hiddenRouteMarks: [], _pendingRouteGeoJson: null,
        currentMapStyle: localStorage.getItem(MAP_STYLE_STORAGE_KEY) || 'standard', mapStyles: Object.values(MAP_STYLES),
        isLoading: false, isReloading: false, isLocatingUser: false, isCentering: false, selectedMarkId: null,
        routeSourceId: 'route-source', routeLayerId: 'route-line', _loadMapDataToken: 0, _destroyed: false,
        ...options,

        getI18nValue(key, fallback = '') {
            const source = this.i18n ?? window.Rega?.i18n ?? {};

            return source[key] ?? key.split('.').reduce((o, i) => o?.[i], source) ?? fallback;
        },

        localizeMapStyles() {
            this.mapStyles = Object.values(MAP_STYLES).map(style => ({
                ...style,
                name: this.getI18nValue(`map.styles.${style.id}`, style.name),
            }));
        },

        async initMap() {
            this.mapEl = document.getElementById(this.id);
            if (!this.mapEl || this.mapCreated || this._initializing) return;

            if (this.mapEl.clientWidth === 0 || this.mapEl.clientHeight === 0) {
                setTimeout(() => this.initMap(), 100);
                return;
            }

            this._initializing = true;
            this.localizeMapStyles();

            this.providedMarks = (this.marks || []).map((m, i) => ({
                id: m.id ?? `mark-${Date.now()}-${i}`,
                lat: toFloat(m.lat ?? m[0]), lng: toFloat(m.lng ?? m[1]), ...m
            })).filter(m => m.lat !== null && m.lng !== null);

            this.createIconsWithScale(this.getMarkerScale?.() ?? 1);
            const center = this.centerPoint?.lat ? [this.centerPoint.lng, this.centerPoint.lat] : [DEFAULT_CENTER.lng, DEFAULT_CENTER.lat];

            this.map = new window.maplibregl.Map({
                container: this.id,
                style: this.buildRasterStyle(),
                center,
                zoom: this.zoomLevel ?? 16,
                minZoom: 3,
                maxZoom: 19,
                attributionControl: true
            });

            this.map.addControl(new window.maplibregl.NavigationControl({ visualizePitch: false }), 'bottom-left');
            this.map.addControl(new window.maplibregl.FullscreenControl(), 'top-right');
            this.locateControl = new window.maplibregl.GeolocateControl({ positionOptions: { enableHighAccuracy: true }, showUserLocation: false });
            this.map.addControl(this.locateControl, 'top-right');
            this.locateControl.on('geolocate', e => this.onLocationFound(e));

            this._themeObserver = new MutationObserver(() => this.updateMapStyle());
            this._themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
            this.resizeObserver = new ResizeObserver(() => this.map?.resize());
            this.resizeObserver.observe(this.mapEl);

            this.setupEvents();

            this.map.on('load', async () => {
                this.map.on('zoom', () => {
                    clearTimeout(this._zoomDebounce);
                    this._zoomDebounce = setTimeout(() => this.updateMarkerIcons(), 80);
                });
                if (!this.disableMarkAction) this.map.on('click', e => this.handleMapClick(e.lngLat));

                await this.loadInitialMarks();
                if (this.geoJsonUrl) await this.loadGeoJSONFromUrl(this.geoJsonUrl);
                this.mapCreated = true;
                this._initializing = false;
            });
            this.map.on('styledata', () => this.applyOverlays());
        },

        buildRasterStyle() {
            const def = MAP_STYLES[this.currentMapStyle] || MAP_STYLES.standard;
            const url = typeof def.url === 'function' ? def.url(isDarkTheme()) : def.url;
            return {
                version: 8, sources: { 'raster-tiles': { type: 'raster', tiles: ['a', 'b', 'c'].map(s => url.replace('{s}', s)), tileSize: 256 } },
                layers: [{ id: 'raster-tiles', type: 'raster', source: 'raster-tiles', minzoom: 2, maxzoom: def.maxZoom ?? 20 }]
            };
        },

        updateMapStyle() { this.map?.setStyle(this.buildRasterStyle()); },

        setMapStyle(id) {
            if (this.currentMapStyle === id) return;
            localStorage.setItem(MAP_STYLE_STORAGE_KEY, this.currentMapStyle = id);
            this.updateMapStyle();
        },

        setupEvents() {
            this._events = [
                ['load-map-data', e => this.handleLoadMapData(e)],
                ['clear-map-data', () => this.resetMap()],
                ['map:remove-marker', e => e.detail.mapId === this.id && this.removeMarker(e.detail.id)],
                ['resize', () => requestAnimationFrame(() => this.map?.resize())]
            ];
            this._events.forEach(([evt, fn]) => window.addEventListener(evt, fn));
            document.addEventListener('livewire:navigate', this._navFn = () => this.destroy());
        },

        async handleMapClick({ lat, lng }) {
            if (this.disableMarkAction || this.isLoading || this.isReloading) return;
            if (!this.hasMultiple) this.clearMarks(true);
            this.isLoading = true;
            try { this.addMarker(lat, lng, await this.getLocationDetails(lat, lng)); }
            finally { this.isLoading = false; }
        },

        addMarker(lat, lng, info = {}, isStored = false, id = crypto.randomUUID()) {
            const el = buildMarkerElement(info?.type === 'bus' ? ICONS.bus : ICONS.busStop, false, Math.round(48 * this.getMarkerScale()));
            const popup = new window.maplibregl.Popup({ offset: 24 }).setHTML(this.buildPopup(info, isStored, id));
            const marker = new window.maplibregl.Marker({ element: el }).setLngLat([lng, lat]).setPopup(popup).addTo(this.map);

            el.addEventListener('click', () => {
                this.setSelectedMarker(id);
                this.map.flyTo({ center: [lng, lat], zoom: 16 });
            });

            this.marks.push({ id, lat, lng, marker, info });
            if (!isStored && !this.disableMarkAction) {
                this.setSelectedMarker(id);
                marker.togglePopup();
                this.syncWithComponent();
            }
        },

        buildPopup(info, isStored, id) {
            let html = `<div class="map-popup"><h3 class="map-popup__title">${escapeHtml(info?.name || this.getI18nValue('map.location', 'Location'))}</h3>`;
            if (info?.address) html += `<p class="map-popup__text">${escapeHtml(info.address)}</p>`;
            if (!isStored && !this.disableMarkAction) {
                html += `<button type="button" class="map-popup__btn" onclick="..."> ${escapeHtml(this.getI18nValue('map.removeMarker', 'Remove'))} </button>`;
            }
            return html + '</div>';
        },

        setSelectedMarker(id) {
            if (this.selectedMarkId === id) return;
            const prev = this.marks.find(m => m.id === this.selectedMarkId);
            if (prev) prev.marker.getElement().innerHTML = buildMarkerElement(prev.info?.type === 'bus' ? ICONS.bus : ICONS.busStop, false).innerHTML;

            this.selectedMarkId = id;
            const current = this.marks.find(m => m.id === id);
            if (current) current.marker.getElement().innerHTML = buildMarkerElement(current.info?.type === 'bus' ? ICONS.bus : ICONS.busStop, true).innerHTML;
        },

        removeMarker(id) {
            const idx = this.marks.findIndex(m => m.id === id);
            if (idx > -1) {
                this.marks[idx].marker?.remove();
                this.marks.splice(idx, 1);
                if (this.selectedMarkId === id) this.selectedMarkId = null;
                this.syncWithComponent();
            }
        },

        clearMarks(skipSync = false) {
            this.marks.forEach(m => m.marker?.remove());
            this.marks = []; this.selectedMarkId = null; this._hiddenRouteMarks = [];
            if (!skipSync && this.$wire?.set) this.$wire.set(this.model, this.hasMultiple ? [] : null);
        },

        async handleLoadMapData(event) {
            const data = Array.isArray(event?.detail) ? event.detail[0] : event?.detail ?? {};
            if (data.mapId && data.mapId !== this.id) return;

            // ئەگەر ماپەکە هێشتا دروست نەبووە یان بەتەواوی ئامادە نییە، پێی دەڵێین بە بەردەوامی چاوەڕێ بکات
            if (!this.map || !this.mapCreated) {
                setTimeout(() => this.handleLoadMapData(event), 100);
                return;
            }

            this.isReloading = true;
            this.clearRoute();

            if (data.routeStartName !== undefined) this.routeStartName = data.routeStartName;
            if (data.routeEndName !== undefined) this.routeEndName = data.routeEndName;

            if (data.marks !== undefined) {
                this.providedMarks = data.marks.map(m => ({ ...m, lat: toFloat(m.lat), lng: toFloat(m.lng) }));
                await this.loadProvidedMarks();
            }
            if (data.geoJson) this.loadGeoJSON(data.geoJson);
            else if (data.geoJsonUrl) await this.loadGeoJSONFromUrl(data.geoJsonUrl);

            this.isReloading = false;
        },

        async loadInitialMarks() { await this.loadProvidedMarks(); },

        async loadProvidedMarks() {
            this.clearMarks(true);
            this.providedMarks.forEach(m => this.addMarker(m.lat, m.lng, { name: m.name, address: m.address, type: m.type }, true, m.id));
            this.fitBoundsToMarks();
        },

        fitBoundsToMarks() {
            if (this.marks.length === 1) this.map.flyTo({ center: [this.marks[0].lng, this.marks[0].lat], zoom: 15 });
            else if (this.marks.length > 1) {
                const bounds = new window.maplibregl.LngLatBounds();
                this.marks.forEach(m => bounds.extend([m.lng, m.lat]));
                this.map.fitBounds(bounds, { padding: 80, maxZoom: 15, minZoom: 5 });
            }
        },

        loadGeoJSON(data, options = { fitBounds: true }) {
            this.clearRoute();
            const geoJson = typeof data === 'string' ? JSON.parse(data) : data;
            this._pendingRouteGeoJson = geoJson;
            this.applyOverlays();

            const coords = extractGeoCoords(geoJson);
            if (coords.length >= 2) {
                const start = coords[0], end = coords[coords.length - 1];
                this.hideMarksNearRoute([start, end]);

                const createPoint = (coord, icon, name, textIcon) => {
                    const el = buildMarkerElement(icon, false, Math.round(48 * this.getMarkerScale()));
                    const popup = new window.maplibregl.Popup({ offset: 24, closeButton: false }).setText(`${textIcon} ${name}`);
                    return [new window.maplibregl.Marker({ element: el }).setLngLat([coord[0], coord[1]]).addTo(this.map), popup.setLngLat([coord[0], coord[1]]).addTo(this.map)];
                };
                this.routeMarkers.push(
                    ...createPoint(start, ICONS.bus, this.routeStartName || 'Start', '🚌'),
                    ...createPoint(end, ICONS.busStop, this.routeEndName || 'End', '🏁')
                );
            }

            if (options.fitBounds && coords.length) {
                const bounds = new window.maplibregl.LngLatBounds();
                coords.forEach(c => bounds.extend([c[0], c[1]]));
                this.map.fitBounds(bounds, { padding: 100, maxZoom: 16, minZoom: 5 });
            }
        },

        async loadGeoJSONFromUrl(url) {
            if (!url) return;
            try { const res = await fetch(url); if (res.ok) this.loadGeoJSON(await res.json()); } catch (e) { }
        },

        clearRoute() {
            this._hiddenRouteMarks.forEach(m => m.marker?.addTo(this.map));
            this._hiddenRouteMarks = [];
            if (this.map?.getLayer(this.routeLayerId)) this.map.removeLayer(this.routeLayerId);
            if (this.map?.getSource(this.routeSourceId)) this.map.removeSource(this.routeSourceId);
            this.routeMarkers.forEach(m => m.remove());
            this.routeMarkers = []; this._pendingRouteGeoJson = null;
        },

        hideMarksNearRoute(targets, tolerance = 0.0002) {
            this.marks.forEach(m => {
                if (targets.some(t => Math.abs(m.lat - t[1]) <= tolerance && Math.abs(m.lng - t[0]) <= tolerance)) {
                    m.marker?.remove();
                    this._hiddenRouteMarks.push(m);
                }
            });
        },

        applyOverlays() {
            if (!this.map || !this._pendingRouteGeoJson) return;
            const src = this.map.getSource(this.routeSourceId);
            if (src) src.setData(this._pendingRouteGeoJson);
            else this.map.addSource(this.routeSourceId, { type: 'geojson', data: this._pendingRouteGeoJson });

            const paint = { 'line-color': isDarkTheme() ? '#60a5fa' : '#2563eb', 'line-width': 6, 'line-opacity': 0.9 };
            if (!this.map.getLayer(this.routeLayerId)) {
                this.map.addLayer({ id: this.routeLayerId, type: 'line', source: this.routeSourceId, layout: { 'line-cap': 'round', 'line-join': 'round' }, paint });
            } else this.map.setPaintProperty(this.routeLayerId, 'line-color', paint['line-color']);
        },

        async getLocationDetails(lat, lng) {
            try {
                const lang = String(this.locale ?? 'en').split(/[_-]/)[0];
                const res = await fetch(`${PHOTON_API}/reverse?lon=${lng}&lat=${lat}&lang=${lang === 'ckb' ? 'ar' : lang}`);
                const data = await res.json();
                const p = data.features?.[0]?.properties;
                if (!p) throw new Error();
                return { name: p.name || p.street || p.city || this.getI18nValue('map.unknown', 'Unknown'), address: [p.street, p.city, p.country].filter(Boolean).join(', ') };
            } catch (e) {
                return { name: this.getI18nValue('map.selectedLocation', 'Selected Location'), address: `${lat.toFixed(6)}, ${lng.toFixed(6)}` };
            }
        },

        syncWithComponent() {
            if (!this.$wire?.set) return;
            const payload = this.marks.map(m => ({ lat: m.lat, lng: m.lng }));
            this.$wire.set(this.model, this.hasMultiple ? payload : (payload[0] || null));
        },

        onLocationFound(e) {
            const lat = e.coords.latitude, lng = e.coords.longitude;
            const el = buildMarkerElement(ICONS.user, false, 24);
            if (this.userLocationMarker) this.userLocationMarker.setLngLat([lng, lat]);
            else this.userLocationMarker = new window.maplibregl.Marker({ element: el }).setLngLat([lng, lat]).addTo(this.map);
            this.map.flyTo({ center: [lng, lat], zoom: 16 });
        },

        getMarkerScale() {
            const z = this.map?.getZoom() ?? 16;
            return 0.6 + (1.4 - 0.6) * Math.min(1, Math.max(0, (z - 8) / 14));
        },

        updateMarkerIcons() {
            const scale = this.getMarkerScale();
            const newSize = Math.round(48 * scale);

            // Skip if size hasn't changed
            if (this._lastMarkerSize === newSize) return;
            this._lastMarkerSize = newSize;

            this.createIconsWithScale(scale);
            this.marks.forEach(m => {
                if (!m.marker) return;
                const el = m.marker.getElement();
                el.style.width = `${newSize}px`;
                el.style.height = `${newSize}px`;
                el.innerHTML = buildMarkerElement(
                    m.info?.type === 'bus' ? ICONS.bus : ICONS.busStop,
                    m.id === this.selectedMarkId,
                    newSize
                ).innerHTML;
            });
        },

        createIconsWithScale(s) { this.icons = { busSize: Math.round(48 * s), userSize: Math.round(24 * s) }; },

        resetMap() {
            this.clearMarks(true); this.clearRoute();
            if (this.userLocationMarker) { this.userLocationMarker.remove(); this.userLocationMarker = null; }
            this.map.jumpTo({ center: [DEFAULT_CENTER.lng, DEFAULT_CENTER.lat], zoom: 16 });
        },

        destroy() {
            if (this._destroyed) return;
            this._destroyed = true;
            clearTimeout(this._zoomDebounce);
            this._events?.forEach(([evt, fn]) => window.removeEventListener(evt, fn));
            document.removeEventListener('livewire:navigate', this._navFn);
            this._themeObserver?.disconnect(); this.resizeObserver?.disconnect();
            if (this.map) { this.clearMarks(); this.clearRoute(); this.map.remove(); }
        }
    };
}
window.MapComponent = MapComponent;
