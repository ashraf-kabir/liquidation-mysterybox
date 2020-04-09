<?php
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
return [
    'paths'        => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds'      => '%%PHINX_CONFIG_DIR%%/db/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database'        => 'development',
        'development'             => [
            'adapter' => '{{{adapter}}}',
            'host'    => '{{{hostname}}}',
            'name'    => '{{{database}}}',
            'user'    => '{{{username}}}',
            'pass'    => '{{{password}}}',
            'port'    => {{{port}}},
            'charset' => 'utf8'
        ]
    ],
];