<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=' . getenv('POSTGRES_HOST') . ';port=' . getenv('POSTGRES_PORT') . ';dbname=' . getenv('POSTGRES_DB'),
    'username' => getenv('POSTGRES_USER'),
    'password' => getenv('POSTGRES_PASSWORD'),
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
