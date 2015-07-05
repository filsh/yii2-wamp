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
    
    public $logging = true;
    
    public $loggingOutput = false;
    
    protected $_connection;
    
    protected $_session;
    
    public function init()
    {
        if(empty($this->host) || empty($this->port) || empty($this->realm)) {
            throw new \yii\base\InvalidConfigException('Invalid Wamp Router config.');
        }
        parent::init();
    }
    
    public function connect(\Closure $callback, array $options = [])
    {
        if($this->_connection !== null) {
            call_user_func_array($callback, [$this->_connection, $this->_connection->getClient()->getSession()]);
        } else {
            $options = array_merge([
                'logging' => $this->logging,
                'loggingOutput' => $this->loggingOutput,
                'connectionTimeout' => null,
                'connectionOptions' => [],
            ], $options);

            if(!$options['logging']) {
                Logger::set(new NullLogger());
            }

            if(!$options['loggingOutput']) {
                ob_start();
            }
            
            $this->_connection = $this->createConnection($options['connectionOptions']);
            $this->_connection->once('open', function (ClientSession $session) use($callback) {
                call_user_func_array($callback, [$this->_connection, $session]);
            });

            if($options['connectionTimeout'] !== null) {
                $loop = $this->_connection->getClient()->getLoop();
                $timer = $loop->addTimer($options['connectionTimeout'], function () use ($loop) {
                    $loop->stop();
                });
                $this->_connection->once('close', function() use($timer) {
                    $timer->cancel();
                    \Yii::warning('WAMP connection closed by timeout.');
                });
            }

            $this->_connection->open();

            if($options['logging']) {
                \Yii::info(ob_get_contents());
            }

            if(!$options['loggingOutput']) {
                ob_clean();
            }
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
