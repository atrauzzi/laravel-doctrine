## Laravel Doctrine

[![Join the chat at https://gitter.im/atrauzzi/laravel-doctrine](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/atrauzzi/laravel-doctrine?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/atrauzzi/laravel-doctrine/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/atrauzzi/laravel-doctrine/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/atrauzzi/laravel-doctrine/badges/build.png?b=master)](https://scrutinizer-ci.com/g/atrauzzi/laravel-doctrine/build-status/master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/aafa61ff-7e01-4c1a-be61-793f5b04cd35/mini.png)](https://insight.sensiolabs.com/projects/aafa61ff-7e01-4c1a-be61-793f5b04cd35)

### An ORM for a Framework for Web Artisans

Laravel's Eloquent ORM is excellent for lightweight use, however there's little out there that can beat [Doctrine](http://goo.gl/oWVD3) when you need a more full-featured ORM.

This is an integration of Doctrine 2.x to Laravel as a [composer](http://goo.gl/gp9HO) package. Doctrine's `EntityManager` instance is accessible through a facade named `Doctrine` as well as via dependency injection.

Metadata is obtained via the [annotation driver](http://goo.gl/dHy9a) or a custom _config_ driver that leverages a Laravel-like configuration syntax. 


#### Installation

Installation is the usual for Laravel packages.

Insert the following in the packages section of your `composer.json` file and run an update:

    "atrauzzi/laravel-doctrine": "dev-master",

Add the service provider to your Laravel application in `config/app.php`. In the `providers` array add:

    'Atrauzzi\LaravelDoctrine\ServiceProvider',

If desired, add the following to your `facades` array in the same file:

    'Doctrine' => 'Atrauzzi\LaravelDoctrine\Support\Facades\Doctrine',

You can also take a copy of the package's configuration by publishing package assets as per normal.


#### Usage

You can obtain the `EntityManager` instance for your connection simply by using the `Doctrine` facade:

Adapted from [Doctrine's documentation](http://goo.gl/XQ3qg):

```php
<?php
$user = new User;
$user->setName('Mr.Right');
Doctrine::persist($user);
Doctrine::flush();
```

It is recommended that you read through all of the [ORM documentation](http://goo.gl/kpAeX).  Try using Laravel's console to experiment and go through the tutorials.

Enjoy!

#### Doctrine Console

If you need run [ORM commands](http://doctrine-orm.readthedocs.org/en/latest/reference/tools.html?highlight=command#command-overview) it is necessary a `cli-config.php` file at root project folder having the following implementation:

```php
<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Illuminate\Foundation\Application;

require __DIR__.'/bootstrap/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

/** @var Illuminate\Contracts\Http\Kernel $kernel */
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$app->boot();

$entityManager = $app->make('Doctrine\ORM\EntityManager');
return ConsoleRunner::createHelperSet($entityManager);
```

For validate your schema, you can do:

```bash
$ vendor/bin/doctrine orm:validate-schema
```


### License

The Laravel framework is open-sourced software license under the [MIT license](http://goo.gl/tuwnQ)

This project is too to ensure maximum compatibility.

### Meta

I'm interested in hearing feedback and suggestions about this package.  Please feel free to [submit a ticket](http://goo.gl/KU6B8) at any time.

Visit laravel-doctrine:

* ...[on packagist](http://goo.gl/YH4C0)

* ...[at Sensio Labs Connect](http://goo.gl/IL6Em)

laravel-doctrine is made by [Alexander Trauzzi](http://goo.gl/QabWv) with help from all the people in `contributors.md`!
