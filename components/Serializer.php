<?php

namespace filsh\wamp\components;

class Serializer extends \yii\rest\Serializer
{
    public function init()
    {
    }
    
    protected function getRequestedFields()
    {
        return [[], []];
    }
    
    protected function serializeDataProvider($dataProvider)
    {
        $models = $this->serializeModels($dataProvider->getModels());

        if ($this->collectionEnvelope === null) {
            return $models;
        } else {
            $result = [
                $this->collectionEnvelope => $models,
            ];
            if (($pagination = $dataProvider->getPagination()) !== false) {
                return array_merge($result, $this->serializePagination($pagination));
            } else {
                return $result;
            }
        }
    }
    
    protected function serializeModel($model)
    {
        list ($fields, $expand) = $this->getRequestedFields();
        return $model->toArray($fields, $expand);
    }
    
    protected function serializeModelErrors($model)
    {
        $result = [];
        foreach ($model->getFirstErrors() as $name => $message) {
            $result[] = [
                'field' => $name,
                'message' => $message,
            ];
        }

        return $result;
    }
}