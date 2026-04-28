<?php

return [
    'routes' => [
        ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
        ['name' => 'page#hello', 'url' => '/hello', 'verb' => 'GET'],
        ['name' => 'health#check', 'url' => '/healthcheck', 'verb' => 'GET'],
    ],
];
