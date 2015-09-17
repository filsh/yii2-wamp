<?php

namespace filsh\wamp\locator;

use filsh\wamp\components\Router;
use filsh\wamp\exceptions\ErrorException;
use Thruway\Connection;
use Thruway\ClientSession;
use Thruway\CallResult;
use Thruway\Message\ErrorMessage;

class Runner extends \filsh\yii2\runner\BaseRunner
{
    public $name;
    
    /**
     * @var \filsh\wamp\components\Router
     */
    public $router;
    
    public $timeout = 5;
    
    public function run(\Closure $successCallback = null, \Closure $errorCallback = null)
    {
        if(false === $this->beforeRun()) {
            return false;
        }
        
        $result = $this->doRun($successCallback, $errorCallback);
        $this->afterRun();
        
        return $result;
    }
    
    protected function doRun(\Closure $successCallback = null, \Closure $errorCallback = null)
    {
        if($successCallback === null) {
            $successCallback = [$this, 'onSuccess'];
        }
        if($errorCallback === null) {
            $errorCallback = [$this, 'onError'];
        }
        
        $returnData = null;
        $this->router->connect(function(Connection $connection, ClientSession $session) use($successCallback, $errorCallback, &$returnData) {
            $session->call(
                $this->name,
                [],
                $this->getKwParams()
            )->then(function (CallResult $result) use($connection, $successCallback, &$returnData) {
                    try {
                        $returnData = call_user_func_array($successCallback, [$connection, $result]);
                    } catch(\Exception $e) {
                        $connection->close();
                        throw $e;
                    }
                    $connection->close();
                },
                function (ErrorMessage $error) use($connection, $errorCallback) {
                    try {
                        call_user_func_array($errorCallback, [$connection, $error]);
                    } catch(\Exception $e) {
                        $connection->close();
                        throw $e;
                    }
                    $connection->close();
                }
            );
        }, [
            'connectionTimeout' => $this->timeout
        ]);
        
        return $returnData;
    }
    
    public function beforeRun()
    {
        if(parent::beforeRun() === false) {
            return false;
        }
        
        if($this->name === null) {
            throw new \yii\base\InvalidParamException('Invalid wamp runner name.');
        }
    }
    
    public function setRouter(Router $router)
    {
        $this->router = $router;
    }
    
    protected function getKwParams()
    {
        return [];
    }
    
    protected function onSuccess(Connection $connection, CallResult $result)
    {
        return $result;
    }
    
    protected function onError(Connection $connection, ErrorMessage $error)
    {
        throw new ErrorException($error);
    }
}