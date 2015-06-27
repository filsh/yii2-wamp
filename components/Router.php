<?php

namespace filsh\wamp\components;

use Thruway\Connection;
use Thruway\ClientSession;
use Thruway\Logging\Logger;
use Psr\Log\NullLogger;

class Router extends \yii\base\Component
{
    public $host;
    
    public $port;
    
    public $realm;
    
    public $enableLoggingOutput = true;
    
    public function init()
    {
        if(empty($this->host) || empty($this->port) || empty($this->realm)) {
            throw new \yii\base\InvalidConfigException('Invalid Wamp Router config.');
        }
        parent::init();
    }
    
    public function connect(\Closure $callback, array $options = [])
    {
        $options = array_merge([
            'logging' => true,
            'loggingOutput' => false,
            'connectionTimeout' => null,
            'connectionOptions' => [],
        ], $options);
        
        if(!$options['logging']) {
            Logger::set(new NullLogger());
        }
        
        if(!$options['loggingOutput']) {
            ob_start();
        }
        
        $connection = $this->createConnection($options['connectionOptions']);
        $connection->once('open', function (ClientSession $session) use($callback, $connection) {
            call_user_func_array($callback, [$connection, $session]);
        });
        
        if($options['connectionTimeout'] !== null) {
            $loop = $connection->getClient()->getLoop();
            $timer = $loop->addTimer($options['connectionTimeout'], function () use ($loop) {
                $loop->stop();
            });
            $connection->once('close', function() use($timer) {
                $timer->cancel();
                \Yii::warning('WAMP connection closed by timeout.');
            });
        }

        $connection->open();
        
        if($options['logging']) {
            \Yii::info(ob_get_contents());
        }
        
        if(!$options['loggingOutput']) {
            ob_clean();
        }
    }
    
    protected function createConnection(array $options = [])
    {
        return new Connection(array_merge($options, [
            'realm'   => $this->realm,
            'url'     => strtr('ws://{host}:{port}', ['{host}' => $this->host, '{port}' => $this->port])
        ]));
    }
}
