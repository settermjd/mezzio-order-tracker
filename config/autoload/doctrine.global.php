<?php

declare(strict_types=1);

use Doctrine\DBAL\Driver\PDO\SQLite\Driver;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;

return [
    'doctrine' => [
        'connection'    => [
            'orm_default' => [
                'driver_class' => Driver::class,
                'params'       => [
                    'path' => __DIR__ . '/../../data/database/db.sqlite',
                ],
            ],
        ],
        'configuration' => [
            'orm_default' => [
                'schema_assets_filter' => [],
                'types'                => [],
            ],
        ],
        'driver'        => [
            'orm_default' => [
                'drivers' => [
                    'App\Entity' => [
                        'class' => AttributeDriver::class,
                        'paths' => [
                            'src/App/src/Entity',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
