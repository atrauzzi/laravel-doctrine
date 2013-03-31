## Laravel Doctrine

### An ORM for a Framework for Web Artisans

Laravel's Eloquent ORM is nice for lightweight use, however there's little out there that can beat [Doctrine](http://www.doctrine-project.org/projects/orm.html) when you need a more full-featured ORM.

This is an integration of Doctrine 2.x to Laravel 4.x as a [composer](http://getcomposer.org) package. Doctrine's `EntityManager` instance is accessible through a facade named `Doctrine`.

Metadata is currently obtained via the [annotation driver](http://docs.doctrine-project.org/projects/doctrine-common/en/latest/reference/annotations.html).

#### Installation

Installation is the usual for Laravel packages.

Insert the following in the packages section of your `composer.json` file and run an update:

    "atrauzzi/laravel-doctrine": "dev-master",

Add the service provider to your Laravel application in `app/config/app.php`. In the `providers` array add:

    'Atrauzzi\LaravelDoctrine\LaravelDoctrineServiceProvider',

Then add the following to your `facades` array in the same file:

    'Doctrine' => 'Atrauzzi\LaravelDoctrine\Support\Facades\Doctrine',

You'll likely want to configure the database connection, which you can do by overriding the bundle's defaults with the following command:

    ./artisan config:publish atrauzzi/laravel-doctrine
    
This should get you a fresh copy of the configuration file in the directory `app/config/packages/vendor/atrauzzi/laravel-doctrine`.

#### Usage

Most of Doctrine's functionality derives from defining your schema (via annotations in your model classes in this case), performing manipulations on instances and then persisting them through the `EntityManager`.  You can obtain the `EntityManager` instance for your connection simply by using the `Doctrine` facade:

    // Adapted from http://goo.gl/XQ3qg
    <?php
    $user = new User;
    $user->setName('Mr.Right');
    Doctrine::persist($user);
    Doctrine::flush();

It is recommended that you read through the [ORM documentation](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/index.html).

Try using Laravel's console to experiment and go through tutorials!

Enjoy!


### License

The Laravel framework is open-sourced software license under the [MIT license](http://opensource.org/licenses/MIT)

This project is too to ensure maximum compatibility.

### Meta

I'm interested in hearing feedback and suggestions about this package.  Please feel free to [submit a ticket](https://github.com/atrauzzi/laravel-doctrine/issues) at any time.

Visit laravel-doctrine:

* ...[on packagist](https://packagist.org/packages/atrauzzi/laravel-doctrine).

* ...[at Sensio Labs Connect](https://connect.sensiolabs.com/profile/omega/project/laravel-doctrine)

laravel-doctrine is made by [Alexander Trauzzi](http://profiles.google.com/atrauzzi)
