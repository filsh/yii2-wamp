<?php

namespace filsh\wamp\locator\runners\file;

class Upload extends \filsh\wamp\locator\runners\File
{
    public $url;
    
    public $type;
    
    public $domain;
    
    public $https = true;
    
    protected function getKwParams()
    {
        return [
            'url' => $this->url,
            'type' => $this->type,
            'domain' => $this->domain,
            'https' => $this->https
        ];
    }
}