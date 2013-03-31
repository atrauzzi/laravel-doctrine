## Laravel Doctrine

### An ORM for a Framework for Web Artisans

Laravel's Eloquent ORM is nice for lightweight use, however there's little out there that can beat Doctrine when you need a more full-featured ORM.

This is an integration of Doctrine 2.x to Laravel 4.x as a composer package. Doctrine's `EntityManager` instance is accessible through a facade named `Doctrine`.

#### Installation

Installation is the usual process for Laravel packages.

Add the service provider to your Laravel application in `app/config/app.php`. In the `providers` array add:

    'Atrauzzi\LaravelDoctrine\LaravelDoctrineServiceProvider',

Then add the following to your `facades` array in the same file:

    'Doctrine' => 'Atrauzzi\LaravelDoctrine\Support\Facades\Doctrine',

You'll likely want to configure the database connection, which you can do by overriding the bundle's defaults with the following command:

    ./artisan config:publish atrauzzi/laravel-doctrine
    
This should get you a fresh copy of the configuration file in the directory `app/config/packages/vendor/atrauzzi/laravel-doctrine`.

### License

The Laravel framework is open-sourced software license under the [MIT license](http://opensource.org/licenses/MIT)

This project is too to ensure maximum compatibility.
