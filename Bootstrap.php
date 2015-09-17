<?php

namespace filsh\wamp;

use filsh\yii2\runner\RunnerComponent;

class Bootstrap implements \yii\base\BootstrapInterface
{
    /**
     * @var array Runner's map
     */
    private $_runners = [];
    
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        /** @var $module Module */
        if ($app->hasModule('wamp') && ($module = $app->getModule('wamp')) instanceof Module) {
            if(!$module->has('runner')) {
                $this->_runners = array_merge($this->_runners, $module->runners);
                foreach ($this->_runners as $name => $definition) {
                    $module->runners[$name] = is_array($definition) ? $definition['class'] : $definition;
                }

                $module->set('runner', [
                    'class' => RunnerComponent::class,
                    'runners' => $module->runners
                ]);
            }
            
            if ($app instanceof \yii\console\Application) {
                $module->controllerNamespace = 'filsh\wamp\commands';
            }
        }
    }
}