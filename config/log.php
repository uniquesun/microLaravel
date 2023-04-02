<?php

return [
    'default' => 'file',
    'channels' => [
        'file' => [
            'driver' => 'stack',
            'path' => PHP_PATH . '/../storage/',
            'format' => '[%s][%s] %s',
        ]
    ]
];