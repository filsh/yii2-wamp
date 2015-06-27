<?php

namespace filsh\wamp;

use filsh\wamp\components\Router;
use filsh\yii2\runner\RunnerComponent;

class Bootstrap implements \yii\base\BootstrapInterface
{
    /**
     * @var array Runner's map
     */
    private $_runnerMap = [];
    
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        /** @var $module Module */
        if ($app->hasModule('wamp') && ($module = $app->getModule('wamp')) instanceof Module) {
            $module->wampRouter = \yii\di\Instance::ensure($module->wampRouter, Router::class);
            
            if(!$module->has('runner')) {
                $this->_runnerMap = array_merge($this->_runnerMap, $module->runnerMap);
                foreach ($this->_runnerMap as $name => $definition) {
                    $module->runnerMap[$name] = is_array($definition) ? $definition['class'] : $definition;
                }

                $module->set('runner', [
                    'class' => RunnerComponent::className(),
                    'runners' => $module->runnerMap
                ]);
            }
            
            if ($app instanceof \yii\console\Application) {
                $module->controllerNamespace = 'filsh\wamp\commands';
            }
        }
    }
}