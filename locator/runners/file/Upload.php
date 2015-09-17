<?php

namespace filsh\wamp\locator\runners\file;

class Upload extends \filsh\wamp\locator\runners\File
{
    public $url;
    
    public $type;
    
    protected function getKwParams()
    {
        return [
            'url' => $this->url,
            'type' => $this->type,
        ];
    }
}