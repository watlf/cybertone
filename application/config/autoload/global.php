<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
//    'service_manager' => array(
//        'factories' => array(
//            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
//        ),
//    ),
    // db adapter config
    'db' => array(
        'driver'    => 'pdo',
        'dsn'       => 'mysql:dbname=cybertone;host=127.0.0.1',
        'username'  => 'root',
        'password'  => '123456',
    ),

    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'root',
                    'password' => '123456',
                    'dbname'   => 'cybertone',
                )
            )
        )
    ),
);
