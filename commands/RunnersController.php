<?php

namespace filsh\wamp\commands;

use Yii;
use Thruway\Connection;
use Thruway\ClientSession;

class RunnersController extends \yii\console\Controller
{
    public $serializer = 'filsh\wamp\components\Serializer';
    
    public function actionIndex($app)
    {
        Yii::$app->configManager->rules = [
            $app => \filsh\config\rule\DummyRule::class
        ];
        
        $this->module->getRouter()->connect(function(Connection $connection, ClientSession $session) {
            $routes = array_keys($this->module->get('runner')->runners);

            foreach($routes as $route) {
                $session->register($route, function($args, $argsKw) use($route, $connection) {
                    $result = $this->module->get('runner')->run($route, [
                        'args' => $args,
                        'argsKw' => $argsKw
                    ]);
                    return Yii::createObject($this->serializer)->serialize($result);
                });
            }
        }, [
            'loggingOutput' => true
        ]);
    }
}