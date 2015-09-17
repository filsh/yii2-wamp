<?php

namespace filsh\wamp\locator\runners;

use filsh\wamp\locator\Runner;

abstract class Identity extends Runner
{
    public $identityClass = '\common\web\Identity';
    
    protected function onSuccess(\Thruway\Connection $connection, \Thruway\CallResult $result)
    {
        return \Yii::createObject(array_merge((array) $result->getArgumentsKw(), [
            'class' => $this->identityClass
        ]));
    }
}