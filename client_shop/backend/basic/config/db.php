<?php

return [
//    'class' => 'yii\db\Connection',
//    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
//    'username' => 'root',
//    'password' => '',
//    'charset' => 'utf8',
    'class' => '\yii\mongodb\Connection',
    'dsn' => 'mongodb://' . getenv('MONGODB_USER') . ':' . getenv('MONGODB_PASSWORD') . '@'
        . getenv('MONGODB_HOST') . ':' . getenv('MONGODB_PORT') . '/'
        . getenv('MONGODB_DB')
    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
