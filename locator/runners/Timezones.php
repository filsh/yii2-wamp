<?php

namespace filsh\wamp\locator\runners;

class Timezones extends \filsh\wamp\locator\Runner
{
    public $limit;
    
    public $order;
    
    public $language;
    
    protected function onSuccess(\Thruway\Connection $connection, \Thruway\CallResult $result)
    {
        $connection->close();
        
        if($result !== null) {
            $return = [];
            foreach($result as $item) {
                $return[$item->name] = (array) $item;
            }
            return $return;
        }
    }
    
    protected function getKwParams()
    {
        return [
            'language' => $this->language,
            'order' => $this->order,
            'limit' => $this->limit
        ];
    }
}