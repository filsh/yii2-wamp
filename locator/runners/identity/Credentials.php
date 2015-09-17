<?php

namespace filsh\wamp\locator\runners\identity;

class Credentials extends \filsh\wamp\locator\runners\Identity
{
    public $login;

    public $password;
    
    protected function getKwParams()
    {
        return [
            'login' => $this->login,
            'password' => $this->password
        ];
    }
}