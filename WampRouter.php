<?php

namespace filsh\wamprouter;

use Thruway\Connection;
use Thruway\ClientSession;
use Thruway\Logging\Logger;
use Psr\Log\NullLogger;

class WampRouter extends \yii\base\Component
{
    public $host;
    
    public $port;
    
    public $realm;
    
    public $timeuot = 5;
    
    public $enableLogging = true;
    
    public function init()
    {
        if(empty($this->host) || empty($this->port) || empty($this->realm)) {
            throw new \yii\base\InvalidConfigException('Invalid Wamp Router config.');
        }
        parent::init();
    }
    
    public function connect(\Closure $callback, array $connectionOptions = [])
    {
        if(!$this->enableLogging) {
            Logger::set(new NullLogger());
        }
        
        ob_start();
        
        $connection = $this->createConnection($connectionOptions);
        $connection->once('open', function (ClientSession $session) use($callback, $connection) {
            call_user_func_array($callback, [$connection, $session]);
        });

        $connection->open();
        
        if($this->enableLogging) {
            \Yii::info(ob_get_clean());
        } else {
            ob_clean();
        }
    }
    
    protected function createConnection(array $options = [])
    {
        $connection = new Connection(array_merge($options, [
            'realm'   => $this->realm,
            'url'     => strtr('ws://{host}:{port}', ['{host}' => $this->host, '{port}' => $this->port])
        ]));
        
        $loop = $connection->getClient()->getLoop();
        $timer = $loop->addTimer($this->timeuot, function () use ($loop) {
            $loop->stop();
        });
        $connection->once('close', function() use($timer) {
            $timer->cancel();
            \Yii::warning('WAMP connection closed by timout.');
        });
        
        return $connection;
    }
}
