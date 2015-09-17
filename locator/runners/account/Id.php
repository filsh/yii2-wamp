<?php

namespace filsh\wamp\locator\runners\account;

class Id extends \filsh\wamp\locator\runners\Account
{
    public $id;
    
    protected function getKwParams()
    {
        return [
            'id' => $this->id
        ];
    }
}