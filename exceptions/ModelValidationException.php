<?php

namespace filsh\wamp\exceptions;

class ModelValidationException extends \Thruway\Exception\WampErrorException
{
    function __construct(\yii\base\Model $model)
    {
        parent::__construct('model.validation.error', null, $model->getErrors());
    }
}