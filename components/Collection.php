<?php

namespace filsh\wamp\components;

use Yii;
use yii\base\InvalidParamException;

class Collection extends \yii\base\Component
{
    private $_routers = [];

    public function setRouters(array $routers)
    {
        $this->_routers = $routers;
    }

    public function getRouters()
    {
        $routers = [];
        foreach ($this->_routers as $realm => $router) {
            $routers[$realm] = $this->getRouter($realm);
        }

        return $routers;
    }

    public function getRouter($realm)
    {
        if (!array_key_exists($realm, $this->_routers)) {
            throw new InvalidParamException("Unknown wamp router '{$realm}'.");
        }
        if (!is_object($this->_routers[$realm])) {
            $this->_routers[$realm]['realm'] = $realm;
            $this->_routers[$realm] = Yii::createObject($this->_routers[$realm]);
        }

        return $this->_routers[$realm];
    }

    public function hasRouter($realm)
    {
        return array_key_exists($realm, $this->_routers);
    }
}