<?php

namespace filsh\wamp\commands;

use Yii;
use Thruway\Connection;
use Thruway\ClientSession;

class RunnersController extends \yii\console\Controller
{
    public $serializer = 'filsh\wamp\components\Serializer';
    
    public function actionIndex()
    {
        $this->module->wampRouter->connect(function(Connection $connection, ClientSession $session) {
            $routes = array_keys($this->module->get('runner')->runners);
            
            foreach($routes as $route) {
                $session->register($route, function($args, $argsKw) use($route, $connection) {
                    $result = $this->module->get('runner')->run($route, [
                        'args' => $args,
                        'argsKw' => $argsKw
                    ]);
                    return $this->serializeData($result);
                });
            }
        }, [
            'loggingOutput' => true
        ]);
    }
    
    protected function serializeData($data)
    {
        return Yii::createObject($this->serializer)->serialize($data);
    }
}