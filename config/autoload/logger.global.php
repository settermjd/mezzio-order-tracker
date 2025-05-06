<?php

declare(strict_types=1);

return [
    'logger' => [
        'name'     => $_ENV['LOGGER_NAME'] ?? '',
        'location' => $_ENV['LOGGER_LOCATION'] ?? '',
    ],
];
