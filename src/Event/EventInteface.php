<?php

namespace Mastercity\Queue\Event;


abstract class EventInteface
{

    /**
     * Индификатор события
     * @var string
     */
    protected $event;

    /**
     * Данные события
     * @var mixed
     */
    protected $data;

    /**
     * Индификатор отправителя
     * @var string
     */
    protected $from;

    /**
     * Метод, который будет вызыватся при успешной обработке события
     */
    abstract public function success();

    /**
     * Метод, который будет вызыватся при ошибке
     */
    abstract public function error();

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param string $event
     * @return EventInteface
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return EventInteface
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     * @return EventInteface
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }


}