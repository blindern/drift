<?php

return array(

    'default' => 'mysql',

    'connections' => array(

        'mysql' => array(
            'driver'    => 'mysql',
            'host'      => 'uka-mysql.zt.foreningenbs.no',
            'database'  => 'uka_billett',
            'username'  => 'uka_billett',
            'password'  => isset($_ENV['BILLETT_MYSQL_PASS']) ? $_ENV['BILLETT_MYSQL_PASS'] : 'uka_billett',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ),
    ),

);
