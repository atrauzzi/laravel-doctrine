<?php namespace Atrauzzi\LaravelDoctrine;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class DoctrineAuthenticator  implements UserProvider{

    protected $userModel;

    public function __construct($userModel){
        $this->userModel = $userModel;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        return app('\Doctrine\ORM\EntityManager')->find($this->userModel, $identifier);
    }

    /**
     * Retrieve a user by by their unique identifier and "remember me" token.
     *
     * @param  mixed $identifier
     * @param  string $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        return app('\Doctrine\ORM\EntityManager')->find($this->userModel, $identifier);
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  string $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setToken($token);
        app('\Doctrine\ORM\EntityManager')->flush($user);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if(in_array(CustomKeyAuthenticable::class, class_implements($this->userModel))) {
            $userObj = new $this->userModel;
            $field = $userObj->getAuthKeyName();
            unset($userObj);
        } else {
            $field = 'email';
        }

        $user = app('\Doctrine\ORM\EntityManager')->getRepository($this->userModel)->findOneBy([$field => $credentials['email']]);
        
        return $user;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if($user instanceof CustomKeyAuthenticable) {
            $method = 'get' . ucfirst($user->getAuthKeyName());
        } else {
            $method = 'getEmail';
        }
        
        return app('hash')->check($credentials['password'], $user->getAuthPassword())
        && trim(strtolower($credentials['email'])) === trim(strtolower($user->{$method}()));
    }
}