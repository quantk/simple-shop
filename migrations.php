<?php
declare(strict_types=1);

return [
    'name'                    => 'Vseinstrumentiru task migrations',
    'migrations_namespace'    => 'App\Infrastructure\Migrations',
    'table_name'              => 'doctrine_migration_versions',
    'column_name'             => 'version',
    'column_length'           => 14,
    'executed_at_column_name' => 'executed_at',
    'migrations_directory'    => './src/Infrastructure/Migrations',
    'all_or_nothing'          => true,
    'check_database_platform' => true,
];
