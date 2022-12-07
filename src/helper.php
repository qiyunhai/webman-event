<?php

if (!function_exists('event')) {

    function event($event, $config = 'plugin.qiyunhai.event.event')
    {
        return (new Qiyunhai\Event\Trigger($event, $config))->run();
    }
}