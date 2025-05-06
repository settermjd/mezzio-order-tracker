<?php

declare(strict_types=1);

use Doctrine\DBAL\Driver\PDO\PgSQL\Driver;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;

return [
    'doctrine' => [
        'dbal'          => [
            'connections' => [
                'default' => [
                    'logging' => true,
                ],
            ],
        ],
        'connection'    => [
            'orm_default' => [
                'driver_class' => Driver::class,
                'params'       => [
                    'dbname'   => $_ENV['DB_NAME'] ?? '',
                    'host'     => $_ENV['DB_HOST'] ?? '',
                    'password' => $_ENV['DB_PASSWORD'] ?? '',
                    'port'     => $_ENV['DB_PORT'] ?? 5432,
                    'user'     => $_ENV['DB_USER'] ?? '',
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
