# yii2-wamp-router

## Installation

It is recommended that you install the Gearman library [through composer](http://getcomposer.org/). To do so, add the following lines to your ``composer.json`` file.

```json
{
    "require": {
       "filsh/yii2-wamp-router": "dev-master"
    }
}
```

## Examples

```php
// configure component
'components' => [
  'wampRouter' => [
      'class' => 'filsh\wamprouter\WampRouter',
      'realm' => 'realm',
      'host' => '172.17.0.20',
      'port' => '8000',
  ]
],

// run examples
Yii::$app->wampRouter->connect(function(Connection $connection, ClientSession $session) {
    $session->call('com.myapp.add2', [2, 3])
        ->then(function (CallResult $result) use($connection) {
                echo $result;
                $connection->close();
            },
            function (ErrorMessage $error) use($connection) {
                echo $error;
                $connection->close();
            }
        );
});

```
