<?php return [

	/*
	 * Metadata Driver Configuration
	 */

	'metadata' => [

		'driver' => 'config',

		//
		// Alternatively, if you want to use a chain, specify multiple drivers as nested arrays.
		//
		[
			'driver' => 'config'
		],
		[
			'driver' => 'annotation',
		//	'namespace' => 'App'
		//  'alias'  => 'DoctrineModel'
		],
		[
			'driver'=>'yaml',
		],
		[
			'driver'=>'xml'
		]
		//
		// ...accepting PRs for more!

	],
	/*

	'mappings' => [

		'App\MyModel' => [

			'table' => 'my_model',

			'abstract' => false,

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
*/
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
	'connection' => [

		'driver' => 'mysqli',
		'host'      => env('DB_HOST', 'localhost'),
		'dbname'  => env('DB_DATABASE', 'forge'),
		'user'  => env('DB_USERNAME', 'forge'),
		'password'  => env('DB_PASSWORD', ''),
		'prefix' => ''
	],

	/*
    | ---------------------------------
	| By default, this package mimics the cache configuration from Laravel.
	|
	| You can create your own cache provider by extending the
	| Atrauzzi\LaravelDoctrine\CacheProvider\CacheProvider class.
	|
	| Each provider requires a like named section with an array of configuration options.
	| ----------------------------------
	 */
	'cache' => [
        // Remove or set to null for no cache
		'provider' => 'array',

		'redis' => [
			'host'     => '127.0.0.1',
			'port'     => 6379,
			'database' => 1
		],

		'memcache' => [
			'host' => '127.0.0.1',
			'port' => 11211
		],

        'providers' => [
            'memcache' => 'Atrauzzi\LaravelDoctrine\CacheProvider\MemcacheProvider',
            'memcached' => 'Atrauzzi\LaravelDoctrine\CacheProvider\MemcachedProvider',
            'couchbase' => 'Atrauzzi\LaravelDoctrine\CacheProvider\CouchebaseProvider',
            'redis' => 'Atrauzzi\LaravelDoctrine\CacheProvider\RedisProvider',
            'apc' => 'Atrauzzi\LaravelDoctrine\CacheProvider\ApcProvider',
            'xcache' => 'Atrauzzi\LaravelDoctrine\CacheProvider\XcacheProvider',
            'array' => 'Atrauzzi\LaravelDoctrine\CacheProvider\ArrayProvider'
            //'custom' => 'Path\To\Your\Class'
        ]
	],


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

	/*
	 * In some circumstances, you may wish to diverge from what's configured in Laravel.
	 */
	//'debug' => false,

    /*
    | ---------------------------------
    | Add custom Doctrine types here
    | For more information: http://doctrine-dbal.readthedocs.org/en/latest/reference/types.html#custom-mapping-types
    | ---------------------------------
    */
    'custom_types' => [
        'json' => 'Atrauzzi\LaravelDoctrine\Type\Json'
    ]

];
