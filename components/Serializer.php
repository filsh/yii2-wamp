<?php

namespace filsh\wamp\components;

use yii\base\Arrayable;
use yii\base\Component;
use yii\base\Model;
use yii\data\DataProviderInterface;
use yii\helpers\ArrayHelper;
use filsh\wamp\exceptions\ErrorException;
use filsh\wamp\exceptions\ModelValidationException;

class Serializer extends Component
{
    public $fields;
    
    public $expand;
    
    public $collectionEnvelope = 'items';
    
    public $metaEnvelope = '_meta';
    
    public function serialize($data)
    {
        if ($data instanceof Model && $data->hasErrors()) {
            return $this->serializeModelErrors($data);
        } elseif ($data instanceof Arrayable) {
            return $this->serializeModel($data);
        } elseif ($data instanceof DataProviderInterface) {
            return $this->serializeDataProvider($data);
        } else {
            return $data;
        }
    }
    
    protected function serializeModel($model)
    {
        list ($fields, $expand) = $this->getRequestedFields();
        return $model->toArray($fields, $expand);
    }
    
    protected function serializeModels(array $models)
    {
        list ($fields, $expand) = $this->getRequestedFields();
        foreach ($models as $i => $model) {
            if ($model instanceof Arrayable) {
                $models[$i] = $model->toArray($fields, $expand);
            } elseif (is_array($model)) {
                $models[$i] = ArrayHelper::toArray($model);
            }
        }

        return $models;
    }
    
    protected function serializeModelErrors($model)
    {
        if($model->hasErrors()) {
            throw new ModelValidationException($model);
        }
        throw new ErrorException('Failed process model operation for unknown reason.');
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
    
    protected function serializePagination($pagination)
    {
        return [
            $this->metaEnvelope => [
                'totalCount' => $pagination->totalCount,
                'pageCount' => $pagination->getPageCount(),
                'currentPage' => $pagination->getPage() + 1,
                'perPage' => $pagination->getPageSize(),
            ],
        ];
    }
    
    protected function getRequestedFields()
    {
        return [
            preg_split('/\s*,\s*/', $this->fields, -1, PREG_SPLIT_NO_EMPTY),
            preg_split('/\s*,\s*/', $this->expand, -1, PREG_SPLIT_NO_EMPTY),
        ];
    }
}