<?php

namespace filsh\wamp\locator\runners\identity;

class Id extends \filsh\wamp\locator\runners\Identity
{
    public $id;
    
    protected function getKwParams()
    {
        return [
            'id' => $this->id
        ];
    }
}