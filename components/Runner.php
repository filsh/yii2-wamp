<?php

namespace filsh\wamp\components;

abstract class Runner extends \filsh\yii2\runner\BaseRunner
{
    public $fields;
    
    public $expand;
    
    /**
     * @inheritdoc
     */
    public $serializer = 'filsh\wamp\components\Serializer';
    
    public function run()
    {
        if(false === $this->beforeRun()) {
            return false;
        }
        
        $result = $this->doRun();
        list($arguments, $argumentsKw) = is_array($result) ? $result : [null, null];
        $this->afterRun();
        
        return new \Thruway\Result($arguments, $this->serializeData($argumentsKw));
    }
    
    public function setArgs($args)
    {
    }
    
    public function setArgsKw($argsKw)
    {
        foreach((array) $argsKw as $name => $value) {
            if($this->canSetProperty($name)) {
                $this->$name = $value;
            }
        }
    }
    
    protected function serializeData($data)
    {
        $serializer = \Yii::createObject($this->serializer, [
            'fields' => $this->fields,
            'expand' => $this->expand,
        ]);
        return $serializer->serialize($data);
    }
}