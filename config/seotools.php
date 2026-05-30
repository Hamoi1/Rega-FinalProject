<?php

/**
 * @see https://github.com/artesaos/seotools
 */

return [
    'meta' => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults' => [
            'title' => setting()->get('company_name', setting()->get('company_name')),
            'titleBefore' => ' | ', // Put defaults.title before page title, like 'It's Over 9000! - Dashboard'
            'description' => setting()->get('about_of_company'),
            'separator' => ' - ',
            'keywords' => [
                'bus', 'city', 'rider', 'transportation', 'travel', 'commute', 'public transport', 'bus lines', 'city bus', 'bus routes', 'urban mobility', 'transit', 'bus schedules', 'bus stops', 'passenger transport', 'bus services', 'city travel', 'bus network', 'bus information', 'bus tracking', 'bus navigation', 'bus planner', 'bus finder', 'bus app', 'bus system', 'bus map', 'bus guide', 'bus tickets', 'bus fares',
                'kurdistan bus', 'erbil transport', 'sulaymaniyah bus lines', 'dohuk bus routes', 'kurdish city bus', 'iraq public transport', 'kurdistan travel', 'erbil bus schedules', 'sulaymaniyah bus stops', 'dohuk passenger transport', 'kurdish bus services', 'iraq city travel', 'kurdistan bus network', 'erbil bus information', 'sulaymaniyah bus tracking', 'dohuk bus navigation', 'kurdish bus planner', 'iraq bus finder', 'kurdistan bus app', 'erbil bus system', 'sulaymaniyah bus map', 'dohuk bus guide', 'kurdish bus tickets', 'iraq bus fares',
            ],
            'canonical' => false, // Set to null or 'full' to use Url::full(), set to 'current' to use Url::current(), set false to total remove
            'robots' => 'index, follow', // Set to 'all', 'none' or any combination of index/noindex and follow/nofollow
        ],
        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google' => 'google-site-verification=6ViEX1SAzBfizx3rrOC-KcWURBMFMlJUgnzeh3bwGpk',
            'bing' => 'D79103994F144D370FD313FAA9D0D9D0',
            'alexa' => null,
            'pinterest' => null,
            'yandex' => null,
            'norton' => null,
        ],

        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title' => setting()->get('company_name'), // set false to total remove
            'description' => setting()->get('about_of_company'), // set false to total remove
            'url' => null,
            'type' => 'website',
            'site_name' => setting()->get('company_name'),
            'images' => [],
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
            // 'card'        => 'summary',
            // 'site'        => '@LuizVinicius73',
        ],
    ],
    'json-ld' => [
        /*
         * The default configurations to be used by the json-ld generator.
         */
        'defaults' => [
            'title' => 'Over 9000 Thousand!', // set false to total remove
            'description' => 'For those who helped create the Genki Dama', // set false to total remove
            'url' => false, // Set to null or 'full' to use Url::full(), set to 'current' to use Url::current(), set false to total remove
            'type' => 'WebPage',
            'images' => [],
        ],
    ],
];
