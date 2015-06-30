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

Insert the following configs in your `composer.json`:

```php
"minimum-stability": "dev",
"prefer-stable": true
```

In the packages section (require):

```php
"atrauzzi/laravel-doctrine": "dev-master"
```

After that, just run a `composer update`

Add the service provider to your Laravel application in `config/app.php`. In the `providers` array add:

```php
Atrauzzi\LaravelDoctrine\ServiceProvider::class,
```

If desired, add the following to your `facades` array in the same file:

```php
'EntityManager' => Atrauzzi\LaravelDoctrine\Support\Facades\Doctrine::class,
```

You need to run this command publish package configuration.

`php artisan vendor:publish --provider="Vendor\atrauzzi\LaravelDoctrine\src\ServiceProvider" --tag="config"`


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
Sample Entity in Laravel 5:

```php

namespace App\Lib\Domain\Entities;
use Doctrine\ORM\Mapping as ORM;
use Atrauzzi\LaravelDoctrine\Trait\Time;
/**
 * @ORM\Entity
 * @ORM\Table(name="Post")
 * @ORM\HasLifecycleCallbacks()
 */
class Post
{
    use Time;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $title;
    /**
     * @ORM\Column(type="text")
     */
    private $body;
    public function __construct($input)
    {
        $this->setTitle($input['title']);
        $this->setBody($input['body']);
    }
    public function getId()
    {
        return $this->id;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function setTitle($title)
    {
        $this->title=$title;
    }
    public function setBody($body)
    {
        $this->body=$body;
    }
    public function getBody()
    {
        return $this->body;
    }
}
```

It is recommended that you read through all of the [ORM documentation](http://goo.gl/kpAeX).  Try using Laravel's console to experiment and go through the tutorials.

Enjoy!


#### Doctrine Console

If you need to run [ORM commands](http://doctrine-orm.readthedocs.org/en/latest/reference/tools.html?highlight=command#command-overview) it is necessary a `cli-config.php` file at root project folder having the following implementation:

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

#### Authentication driver

This package allows you to customize the authentication driver using your own user model.
In order to use doctrine authentication driver you need to keep in mind the following structure.

* Having **user model** representing an authenticatable user into your application
* Edit `/config/doctrine.php` config file to set authentication model and user provider
* Edit `/config/auth.php` config file to set authentication driver. 

Now, let's understand how this driver works.

#####User model
Your application must has a model implementing `Illuminate\Contracts\Auth\Authenticatable`. By default, this package
comes with a Doctrine Authentication provider that works with a model using its `email` and `password` as unique valid
 credentials. The code below shows a valid user model:
 
```php
<?php namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Class User
 * @entity
 * @table(name="users")
 */
class User implements Authenticatable {

    /**
     * @var int
     * @column(type="integer", name="id_user")
     * @generatedValue(strategy="AUTO")
     * @id
     */
    protected $id;

    /**
     * @var string
     * @column(type="string", unique=true)
     */
    protected $email;

    /**
     * @var string
     * @column(type="string")
     */
    protected $password;

    /**
     * @var string
     * @column(type="string")
     */
    protected $token;

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }


    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->token;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->token = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_me_token';
    }
}
```

It is important to know that laravel needs that our model accomplishes with some rules provided by this interface:

* getAuthIdentifier()
* getRememberToken()
* setRememberToken($value)
* getRememberTokenName()
                                                        
#####doctrine.php
Once you have created a valid user model, you are able to specify it in doctrine config file as below:

```php
'auth' => [
    'authenticator' => 'Atrauzzi\LaravelDoctrine\DoctrineAuthenticator',
    'model' => 'App\Models\User',
]
```

* If you want to base your authentication system by `email` and `password` you can use the default doctrine authenticator.
* If you need to implement your own doctrine authenticator then set `authenticator` key by passing the classname.
* If you want to use the native laravel auth driver, then set `authenticator` key a `null` value or just comment it.

#####auth.php
Finally, to set doctrine driver as default authentication system you need to set the value as `doctrine.auth`:
 
```php
'driver' => 'doctrine.auth',
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
