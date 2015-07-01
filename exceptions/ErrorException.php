<?php

namespace filsh\wamp\exceptions;

use Thruway\Message\ErrorMessage;

class ErrorException extends \yii\base\Exception
{
    public $errorUri;
    
    public $errorMessage;
    
    public function __construct(ErrorMessage $error, \Exception $previous = null)
    {
        $this->errorUri = $error->getErrorURI();
        $this->errorMessage = ucfirst(implode(': ', $error->getArguments()));
        
        parent::__construct($this->errorMessage, $error->getMsgCode(), $previous);
    }
    
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        $this->errorUri;
    }
}