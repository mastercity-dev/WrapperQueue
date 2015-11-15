<?php
/**
 * Created by PhpStorm.
 * User: sysolyatindima
 * Date: 15/11/15
 * Time: 15:06
 */

namespace Mastercity\Queue\Event;


class EventRabbit extends EventInteface
{

    /**
     * @var string
     */
    protected $deliveryTag;

    /**
     * @var \PhpAmqpLib\Channel\AMQPChannel
     */
    protected $channel;

    /**
     * @param mixed $deliveryTag
     * @return EventRabbit
     */
    public function setDeliveryTag($deliveryTag)
    {
        $this->deliveryTag = $deliveryTag;

        return $this;
    }

    /**
     * @param \PhpAmqpLib\Channel\AMQPChannel $channel
     * @return EventRabbit
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }




    /**
     * Метод, который будет вызыватся при успешной обработке события
     */
    public function success()
    {
        $this->channel->basic_ack($this->deliveryTag);
    }

    /**
     * Метод, который будет вызыватся при ошибке
     */
    public function error()
    {
        // TODO: Implement error() method.
    }
}