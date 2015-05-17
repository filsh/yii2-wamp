<?php

namespace filsh\wamprouter;

use Yii;

class Controller extends \yii\base\Controller
{
    public $serializer = 'filsh\wamprouter\Serializer';
    
    protected function serializeData($data)
    {
        return Yii::createObject($this->serializer)->serialize($data);
    }
}