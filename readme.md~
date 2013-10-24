## Laravel Doctrine

### An ORM for a Framework for Web Artisans

Laravel's Eloquent ORM is nice for lightweight use, however there's little out there that can beat [Doctrine](http://goo.gl/oWVD3) when you need a more full-featured ORM.

This is an integration of Doctrine 2.x to Laravel 4.x as a [composer](http://goo.gl/gp9HO) package. Doctrine's `EntityManager` instance is accessible through a facade named `Doctrine`.

Metadata is currently obtained via the [annotation driver](http://goo.gl/dHy9a).

#### Installation

Installation is the usual for Laravel packages.

Insert the following in the packages section of your `composer.json` file and run an update:

    "atrauzzi/laravel-doctrine": "dev-master",

Add the service provider to your Laravel application in `app/config/app.php`. In the `providers` array add:

    'Atrauzzi\LaravelDoctrine\ServiceProvider',

Then add the following to your `facades` array in the same file:

    'Doctrine' => 'Atrauzzi\LaravelDoctrine\Support\Facades\Doctrine',

You'll likely want to configure the database connection, which you can do by overriding the bundle's defaults with the following command:

    ./artisan config:publish atrauzzi/laravel-doctrine

This should get you a fresh copy of the configuration file in the directory `app/config/packages/vendor/atrauzzi/laravel-doctrine`.

#### Usage

Most of Doctrine's functionality derives from defining your schema (via annotations in your model classes in this case), performing manipulations on instances and then persisting them through the `EntityManager`.  You can obtain the `EntityManager` instance for your connection simply by using the `Doctrine` facade:

Adapted from [Doctrine's documentation](http://goo.gl/XQ3qg):

    <?php
    $user = new User;
    $user->setName('Mr.Right');
    Doctrine::persist($user);
    Doctrine::flush();

It is recommended that you read through all of the [ORM documentation](http://goo.gl/kpAeX).  Try using Laravel's console to experiment and go through the tutorials.

Enjoy!


### License

The Laravel framework is open-sourced software license under the [MIT license](http://goo.gl/tuwnQ)

This project is too to ensure maximum compatibility.

### Meta

I'm interested in hearing feedback and suggestions about this package.  Please feel free to [submit a ticket](http://goo.gl/KU6B8) at any time.

Visit laravel-doctrine:

* ...[on packagist](http://goo.gl/YH4C0)

* ...[at Sensio Labs Connect](http://goo.gl/IL6Em)

laravel-doctrine is made by [Alexander Trauzzi](http://goo.gl/QabWv) with help from all the people in `contributors.md`!
