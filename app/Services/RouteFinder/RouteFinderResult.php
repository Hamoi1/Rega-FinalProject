<?php

namespace App\Services\RouteFinder;

class RouteFinderResult
{
    /**
     * @param  array<int, array<string, mixed>>  $segments
     * @param  array<string, mixed>  $geoJson
     */
    public function __construct(
        public array $segments,
        public array $geoJson,
    ) {}
}