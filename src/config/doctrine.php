<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Database Connection
	|--------------------------------------------------------------------------
	|
	| This array passes right through to the EntityManager factory. For
	| example, here you can set additional connection details like "charset".
	|
	| http://doctrine-dbal.readthedocs.org/en/latest/reference/configuration.html#connection-details
	|
	*/

	'connection' => array(

		'driver'    => 'pdo_mysql',
		'user'		=> 'root',
		'password'	=> '',
		'dbname'	=> 'database',
		'host'		=> 'localhost',
		'prefix'	=> ''

	),

	/*
	|--------------------------------------------------------------------------
	| Metadata Sources
	|--------------------------------------------------------------------------
	|
	| This array passes right through to the EntityManager factory.
	|
	| http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/configuration.html
	|
	*/
	'metadata' => array(
		__DIR__.'/../../../../../app/models'
	),

	/*
	|--------------------------------------------------------------------------
	| Sets the directory where Doctrine generates any proxy classes, including
	| with which namespace.
	|--------------------------------------------------------------------------
	|
	| http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/configuration.html
	|
	*/
	'proxy_classes' => array(
		'auto_generate' => false,
		'directory' => null,
		'namespace' => null,
	),
 
 	/*
	|--------------------------------------------------------------------------
	| Cache providers, supports apc, xcache, memcache, redis
	| Only redis and memcache have additionals configurations
	|--------------------------------------------------------------------------
	*/
	'cache' => array(
		'provider' => 'redis',

		'redis' => array(
			'host'     => '127.0.0.1',
			'port'     => 6379,
			'database' => 1
		),

		'memcache' => array(
			'host' => '127.0.0.1', 
			'port' => 11211
		)
	),

	'migrations' => array(
		'directory' => '/database/doctrine-migrations',
                'namespace'  => 'DoctrineMigrations',
		'table_name' => 'doctrine_migration_versions'
	),

 	/*
	|--------------------------------------------------------------------------
	| Use to specify the default repository
    | http://doctrine-orm.readthedocs.org/en/latest/reference/working-with-objects.html#custom-repositories
	|--------------------------------------------------------------------------
	*/
	'defaultRepository' => '\Doctrine\ORM\EntityRepository',

	/*
	 |--------------------------------------------------------------------------
	 | Annotation Reader
	 | https://github.com/doctrine/doctrine2/blob/master/lib/Doctrine/ORM/Tools/Setup.php
	 |--------------------------------------------------------------------------
	 */
	'use_simple_annotation_reader' => FALSE,

	/*
	|--------------------------------------------------------------------------
	| Use to specify the SQL Logger
	| To use with \Doctrine\DBAL\Logging\EchoSQLLogger, do:
	| 'sqlLogger' => new \Doctrine\DBAL\Logging\EchoSQLLogger();
	|
	| http://doctrine-orm.readthedocs.org/en/latest/reference/advanced-configuration.html#sql-logger-optional
	|--------------------------------------------------------------------------
	*/
	'sqlLogger' => null,

	/*
	|--------------------------------------------------------------------------
	| Use to specify the DriverChain driver to allow Multiple Metadata Sources
	| http://docs.doctrine-project.org/en/latest/reference/advanced-configuration.html#multiple-metadata-sources
	|
	| laravel-doctrine will automatically add the annotations driver with specified defaultNamespace
	|--------------------------------------------------------------------------
	*/
	'driverChain' => array(
		'enabled' => false,
		'defaultNamespace' => 'Entities'
	),
);
