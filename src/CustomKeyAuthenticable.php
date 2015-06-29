<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Atrauzzi\LaravelDoctrine;

use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Description of DoctrineAuthenticable
 *
 * @author José Nicodemos Maia Neto<jose at nicomaia.com.br>
 */
interface CustomKeyAuthenticable extends Authenticatable {
    /**
     * This method must return the field name that will be used for the auth
     * 
     * @return string
     */
    public function getAuthKeyName();
    
    /**
     * This method must return the field's value that will be used for the auth
     * 
     * @return string
     */
    public function getAuthKeyValue();
}
