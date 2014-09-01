<?php

/**
 * Bono App Configuration
 *
 * @category  PHP_Framework
 * @package   Bono
 * @author    Ganesha <reekoheek@gmail.com>
 * @copyright 2013 PT Sagara Xinix Solusitama
 * @license   https://raw.github.com/xinix-technology/bono/master/LICENSE MIT
 * @version   0.10.0
 * @link      http://xinix.co.id/products/bono
 */

return array(
    'application' => array(
        'title' => 'Mahabharat',
        'subtitle' => 'Mahabharat Download and Streaming'
    ),
    'bono.salt' => 'please change this',
    'bono.providers' => array(
        '\\Norm\\Provider\\NormProvider' => array(
            'datasources' => array(
                // 'mongo' => array(
                //     'driver' => '\\Norm\\Connection\\MongoConnection',
                //     'database' => 'mahabharat',
                // ),
                //<-- SQL Connection-->
                'mysql' => array(
                    'driver'   => '\\App\\Connection\\PDOConnection',
                    'dialect'  => '\\App\\Dialect\\MySQLDialect',
                    'prefix'   => 'mysql',
                    'dsn'      => 'mysql',
                    'dbname'   => 'mahabharat',
                    'host'     => 'localhost',
                    // 'host'     => '192.168.1.10',
                    'username' => 'root',
                    'password' => 'password',
                ),
            ),

            'collections' => array(
                'default' => array(
                    'observers' => array(
                        '\\Norm\\Observer\\Ownership' => null,
                        '\\Norm\\Observer\\Timestampable' => null,
                    ),
                ),
                'resolvers' => array(
                    '\\App\\CollectionResolver',
                ),
            ),
        ),
        '\\Xinix\\Migrate\\Provider\\MigrateProvider' => array(
            // 'token' => 'changetokenherebeforeenable',
        ),
        '\\App\\Provider\\AppProvider',
    ),
    'bono.middlewares' => array(
        '\\Bono\\Middleware\\StaticPageMiddleware' => null,
        '\\Bono\\Middleware\\ControllerMiddleware' => array(
            'default' => '\\App\\Controller\\BaseController',
            'mapping' => array(
                '/user' => null,
                '/episode' => '\\App\\Controller\\EpisodeController',
            ),
        ),
        '\\Bono\\Middleware\\ContentNegotiatorMiddleware' => array(
            'extensions' => array(
                'json' => 'application/json',
            ),
            'views' => array(
                'application/json' => '\\Bono\\View\\JsonView',
            ),
        ),
        // uncomment below to enable auth
        // '\\ROH\\BonoAuth\\Middleware\\AuthMiddleware' => array(
        //     'driver' => '\\ROH\\BonoAuth\\Driver\\NormAuth',
        // ),
        '\\Bono\\Middleware\\NotificationMiddleware' => null,
        '\\Bono\\Middleware\\SessionMiddleware' => null,
    ),
    'bono.theme' => array(
        'class' => '\\Xinix\\Theme\\NakedTheme',
        'overwrite' => true,
    ),
    'app.templates.path' => '../templates',
);
