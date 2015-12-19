# Wrapper Queue #

## Для использование пакета неоходимо установить ##

Для использования RabbitProvider:
- [rabbitmq-server](https://www.rabbitmq.com/download.html)
- Плагин [RabbitMQ Reverse Topic Exchange Type](https://github.com/videlalvaro/rabbitmq-rtopic-exchange)

## Установка пакета ##

```
{
    ...
    "repositories": [
        {
          "type": "vcs",
          "url": "git@github.com:mastercity-dev/WrapperQueue.git"
        }
      ],
    
    "require": {
        "mastercity/wrapperqueue" : "dev-master"
      }
    ...
}
```

## Использование ##
```php
<?php
use PhpAmqpLib\Connection\AMQPStreamConnection;

include __DIR__."/vendor/autoload.php";

// Коннектимся к rabbitmq
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

// имя точки доступа
$exchange = "exchange";

// Индификатор очереди
$queueId = "exchange.insert.product";

//Объявляем provider для Queue
$provider = new \Mastercity\Queue\Provider\RabbitProvider($connection,$exchange, $queueId, false);

// Создаём Queue
$queue = new \Mastercity\Queue\Queue($provider);

// insertUser - отправить в очередь сообщение с
// event = updateAvatar,
// data = ['test' => 1],
// to - # (отправить во все очереди),
//      exchange.# - отправить всем очередям с индификатор которых имеет префикс exchange
//      exchange.insert.# - отправить всем очередям с индификатор которых имеет префикс exchange.insert

$queue->send("updateAvatar", ['test' => 1], '#');


// Если необходимо повысить приоритет сообщения, то в data устанавливаем приоритет в дипазоне [1..10]
$queue->send('updateTest', ['test' => 1, 'priority' => 4], '#');

// Получить сообщения из очереди
$message = $queue->get();

// Имя события
$message->getEvent();

// Данные
$message->getData();

// Откуда было отправлено сообщение
$message->getFrom();

// Если сообщение было успешно обработано, без этого новые данные получены не будут
$message->success();
```


