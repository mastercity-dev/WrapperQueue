<?php
namespace Mastercity\Queue;


use Mastercity\Queue\Provider\ProviderInterface;

class Queue
{
    protected $provider;

    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Отправка события в очередь
     * @param $event - символьный индификатор события
     * @param $data - Данные для отправки
     * @param $to - необязательный параметр, который индифицирует куда будет отправлено событие, если очередей много
     */
    public function send($event, $data, $to = null)
    {
        $this->provider->send($event, $data, $to);
    }

    /**
     * Получает новое событие из очереди
     * @return EventInterface
     */
    public function get()
    {
        return $this->provider->get();
    }
}