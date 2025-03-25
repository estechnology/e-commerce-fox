<?php
return [
    'db' => [
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'dbname' => 'apifox',
        'user' => 'root',
        'password' => '',
        'pooling' => true,
        'pool_size' => 10, // Utilizando um pool de conexões para gerenciar conexões ao banco de dados de forma eficiente. O doctrine/dbal suporta pool de conexões
    ],
];
