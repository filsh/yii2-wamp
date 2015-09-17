<?php

namespace filsh\wamp\locator\runners\user;

class Identity extends \filsh\wamp\locator\runners\User
{
    public $id;
    
    public $authKey;
    
    protected function getKwParams()
    {
        return [
            'id' => $this->id,
            'authKey' => $this->authKey
        ];
    }
}