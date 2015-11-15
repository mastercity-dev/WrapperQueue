<?php

namespace Mastercity\Queue\Provider;


interface ProviderInterface
{
    /**
     * Отправка события в очередь
     * @param $event - символьный индификатор события
     * @param $data - Данные для отправки
     * @param $to - необязательный параметр, который индифицирует куда будет отправлено событие, если очередей много
     */
    public function send($event, $data, $to = null);

    /**
     * Получает новое событие из очереди
     * @return EventInterface
     */
    public function get();
}