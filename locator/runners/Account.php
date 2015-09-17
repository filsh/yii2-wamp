<?php

namespace filsh\wamp\locator\runners;

use filsh\wamp\locator\Runner;

abstract class Account extends Runner
{
    protected function onSuccess(\Thruway\Connection $connection, \Thruway\CallResult $result)
    {
        return $result->getArgumentsKw();
    }
}