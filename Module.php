<?php

namespace filsh\wamp;

use Yii;

class Module extends \yii\base\Module
{
    const VERSION = '0.0.1';
    
    public $wampRouter;
    
    /**
     * @var array Runner's map
     */
    public $runnerMap = [];
}