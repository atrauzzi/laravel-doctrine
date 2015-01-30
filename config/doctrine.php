<?php return [

	/*
	 * Metadata Driver Configuration
	 */
	/*
	'metadata' => [

		'driver' => 'config',

		//
		// Alternatively, if you want to use a chain, specify multiple drivers as nested arrays.
		//
		[
			'driver' => 'config'
		],
		[
			'driver' => 'annotation'
		]
		//
		// ...accepting PRs for more!

	],
	*/

	'mappings' => [

		'App\MyModel' => [

			'table' => 'my_model',

			'repository' => 'App\Repository\MyModel',

			'fields' => [

				'id' => [
					'type' => 'integer',
					'strategy' => 'identity'
				],

				'name' => [
					'type' => 'string',
					'nullable' => false,
				]

			],

			'indexes' => [
				'name'
			],

		],

	],

	/*
	 * By default, this package mimics the database configuration from Laravel.
	 *
	 * You can override it in whole or in part here.
	 *
	 * This array passes right through to the EntityManager factory. For
	 * example, here you can set additional connection details like "charset".
	 *
	 * http://doctrine-dbal.readthedocs.org/en/latest/reference/configuration.html#connection-details
	 */
	/*
	'connection' => [

		'driver' => '',
		'user' => '',
		'password' => '',
		'dbname' => '',
		'host' => '',
		'prefix' => ''

	],
	*/

	/*
	 * By default, this package mimics the cache configuration from Laravel.
	 *
	 * Cache providers, supports apc, xcache, memcache, redis.
	 */
	/*
	'cache' => [

		'provider' => 'redis',

		'redis' => [
			'host'     => '127.0.0.1',
			'port'     => 6379,
			'database' => 1
		],

		'memcache' => [
			'host' => '127.0.0.1',
			'port' => 11211
		]
	],
	*/

	/*
	|--------------------------------------------------------------------------
	| Sets the directory where Doctrine generates any proxy classes, including
	| with which namespace.
	|--------------------------------------------------------------------------
	|
	| http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/configuration.html
	|
	*/
	/*
	'proxy_classes' => [
		'auto_generate' => false,
		'directory' => null,
		'namespace' => null,
	],
	*/


	'migrations' => [
		'directory' => '/database/doctrine-migrations',
		'namespace'  => 'DoctrineMigrations',
		'table_name' => 'doctrine_migration_versions'
	],

 	/*
	|--------------------------------------------------------------------------
	| Use to specify the default repository
    | http://doctrine-orm.readthedocs.org/en/latest/reference/working-with-objects.html#custom-repositories
	|--------------------------------------------------------------------------
	*/
	/*
	'default_repository' => '\Doctrine\ORM\EntityRepository',
	*/

	/*
	|--------------------------------------------------------------------------
	| Use to specify the SQL Logger
	| To use with \Doctrine\DBAL\Logging\EchoSQLLogger, do:
	| 'sqlLogger' => new \Doctrine\DBAL\Logging\EchoSQLLogger();
	|
	| http://doctrine-orm.readthedocs.org/en/latest/reference/advanced-configuration.html#sql-logger-optional
	|--------------------------------------------------------------------------
	*/
	/*
	'sql_logger' => null,
	*/

];
