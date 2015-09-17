<?php

namespace filsh\wamp;

use Yii;

class Module extends \yii\base\Module
{
    const VERSION = '0.0.2';
    
    /**
     * @var array Runner's map
     */
    public $runners = [];
    
    public $routers = [];
    
    protected $routerCollection;
    
    public function init()
    {
        parent::init();
        $this->routerCollection = Yii::createObject([
            'class' => \filsh\wamp\components\Collection::class,
            'routers' => $this->routers
        ]);
    }
    
    public function getRouter()
    {
        $realm = Yii::$app->configManager->get('routerRealm');
        return $this->routerCollection->getRouter($realm);
    }
    
    public function __call($name, $params)
    {
        $wampLocator = Yii::createObject([
            'class' => \filsh\wamp\locator\Locator::class,
            'router' => $this->getRouter()
        ]);
        
        if(isset($wampLocator->runnersMap[$name])) {
            $runParams = isset($params[0]) ? $params[0] : array();
            $successCallback = isset($params[1]) ? $params[1] : null;
            $errorCallback = isset($params[2]) ? $params[2] : null;
            
            return $wampLocator->createRunner($wampLocator->runnersMap[$name], $runParams)->run($successCallback, $errorCallback);
        }
        return parent::__call($name, $params);
    }
}