<?php
/**
 * 事件产生
 *
 * @descr   负责触发事件，增加观察者，事件通知
 * @author  qiyunhai<156457917@qq.com>
 */

namespace Qiyunhai\Event;

class EventGenerator
{
    /**
     * 观察者
     */
    private $obServers = [];

    /**
     * 增加观察者
     */
    public function addObServer($obServer)
    {
        $this->obServers[] = $obServer;
    }

    /**
     * 事件通知
     */
    public function notify($event)
    {
        $result = [];
        foreach ($this->obServers as $obServer) {
            $result[] = $obServer->run($event);
        }
        return $result;
    }

}