<?php

namespace filsh\wamp\locator\runners\identity;

class Create extends \filsh\wamp\locator\runners\Identity
{
    public $email;
    
    public $password;
    
    public $username;
    
    public $accountId;
    
    protected function getKwParams()
    {
        $params = [
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password
        ];
        
        if($this->accountId !== null) {
            $params['accountId'] = $this->accountId;
        }
        
        return $params;
    }
}