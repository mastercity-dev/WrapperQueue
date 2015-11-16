# Mastercity Queue #

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
          "url": "git@github.com:mastercity-dev/mastercity-queue.git"
        }
      ],
    
    "require": {
        "mastercity/mastercity-queue" : "dev-master"
      }
    ...
}
```

## Использование ##
```php
use PhpAmqpLib\Connection\AMQPStreamConnection;

include __DIR__."/vendor/autoload.php";

// Коннектимся к rabbitmq
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

// имя точки доступа
$exchange = "mastercity";

// Индификатор очереди
$queueId = "mastercity.insert.blog";

//Объявляем provider для Queue
$provider = new \Mastercity\Queue\Provider\RabbitProvider($connection,$exchange, $queueId, false);

// Создаём Queue
$queue = new \Mastercity\Queue\Queue($provider);

// insertUser - отправить в очередь сообщение с
// event = updateAvatar,
// data = ['test' => 1],
// to - # (отправить во все очереди),
//      mastercity.# - отправить всем очередям с индификатор которых имеет префикс mastercity
//      mastercity.insert.# - отправить всем очередям с индификатор которых имеет префикс mastercity.insert

$queue->send("updateAvatar", ['test' => 1], '#');


// Получить сообщения из очереди
$message = $queue->get();

// Имя события
$message->getEvent();

// Данные
$message->getData();

// Откуда было отправлено сообщение
$message->getFrom();


```


