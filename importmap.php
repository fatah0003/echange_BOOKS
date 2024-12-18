<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    'card' => [
        'path' => './assets/card.js',
        'entrypoint' => true,
    ],
    'notification' => [
        'path' => './assets/notification.js',
        'entrypoint' => true,
    ],
    'home' => [
        'path' => './assets/home.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/turbo' => [
        'version' => '7.3.0',
    ],
    'search' => [
        'path' => './assets/js/serach/address-input.js',
        'entrypoint' => true,
    ],
    'search-isbn' => [
        'path' => './assets/js/serach/isbn-input.js',
        'entrypoint' => true,
    ],
    'book' => [
        'path' => './assets/book.js',
        'entrypoint' => true,
    ],
    'form' => [
        'path' => './assets/form.js',
        'entrypoint' => true,
    ],
    'filter' => [
        'path' => './assets/filter.js',
        'entrypoint' => true,
    ],
    'profil' => [
        'path' => './assets/profil.js',
        'entrypoint' => true,
    ],
    'exchange' => [
        'path' => './assets/exchange.js',
        'entrypoint' => true,
    ],
    'dashbord' => [
        'path' => './assets/dashbord.js',
        'entrypoint' => true,
    ],
];