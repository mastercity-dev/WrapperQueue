<?php

namespace Mastercity\Queue\Provider;


use Mastercity\Queue\Event\EventRabbit;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitProvider implements ProviderInterface
{


    /**
     * @var string
     */
    protected $queueId;

    /**
     * @var \PhpAmqpLib\Channel\AMQPChannel
     */
    protected $channel;

    /**
     * @var string
     */
    protected $exchange;

    /**
     * @var EventInterface
     */
    protected $nextMessage;

    /**
     * RabbitProvider constructor.
     * @param AMQPStreamConnection $connection
     * @param string $queueId - индификатор очереди
     * @param boolean $debug - включить режим debug
     */
    public function __construct(AMQPStreamConnection $connection, $exchange, $queueId, $debug = false)
    {
        if ($debug === true && !defined("AMQP_DEBUG")) {
            define("AMQP_DEBUG", 1);
        }

        $this->exchange = $exchange;
        $this->channel = $connection->channel();
        $this->channel->exchange_declare($exchange, "x-rtopic", false, true, false, false);
        $this->channel->queue_declare($queueId, false, true, false, false, false, ['x-max-priority' => ['I', 10]]);
        $this->channel->queue_bind($queueId, $this->exchange, $queueId);

        $this->channel->basic_consume($queueId, '', false, false, false, false, [$this, 'callbackRabbit']);

        $this->queueId = $queueId;
    }

    public function callbackRabbit(AMQPMessage $msg)
    {
        $eventRabbit = new EventRabbit();
        $data = json_decode($msg->body, true);

        $eventRabbit
            ->setChannel($this->channel)
            ->setDeliveryTag($msg->delivery_info['delivery_tag'])
            ->setEvent($data['event'])
            ->setData($data['data'])
            ->setFrom($data['from']);

        $this->nextMessage = $eventRabbit;
    }

    /**
     * Отправка события в очередь
     * @param $event - символьный индификатор события
     * @param $data - Данные для отправки
     * @param $to - ('#' - отправить всем очередь)
     */
    public function send($event, $data, $to = "exchange.#")
    {
        $priority = 1;
        if (isset($data['priority'])) {
            $priority = $data['priority'];
        }

        $data = json_encode([
            'event' => $event,
            'data' => $data,
            'from' => $this->queueId
        ]);

        $msg = new AMQPMessage($data, [
            'delivery_mode' => 2,
            'priority' => $priority
        ]);

        $this->channel->basic_publish($msg, $this->exchange, $to, true);
    }

    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * Получает новое событие из очереди
     * @return EventInterface
     */
    public function get()
    {
        try {
            $this->channel->wait(null, true, 0.1);
        } catch (AMQPTimeoutException $e) {
            return null;
        }

        $message = $this->nextMessage;
        $this->nextMessage = null;

        return $message;
    }
}