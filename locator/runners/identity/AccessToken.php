<?php

namespace filsh\wamp\locator\runners\identity;

class AccessToken extends \filsh\wamp\locator\runners\Identity
{
    public $type;
    
    public $accessToken;
    
    protected function getKwParams()
    {
        return [
            'type' => $this->type,
            'accessToken' => $this->accessToken
        ];
    }
}