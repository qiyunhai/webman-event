<?php
/**
 * 触发
 *
 * @descr   负责触发事件
 * @author  qiyunhai<156457917@qq.com>
 */

namespace Qiyunhai\Event;

class Trigger extends EventGenerator
{
    /**
     * 触发的事件
     */
    public $event;

    /**
     * 事件配置
     */
    public $config;

    /**
     * Trigger constructor.
     */
    public function __construct($event, $config = 'plugin.qiyunhai.event.event')
    {
        // 保存事件
        $this->event = $event;
        // 获取event配置
        $this->config = config($config.'.'.get_class($event));
    }

    /**
     * 触发事件
     */
    public function run()
    {
        // 添加观察者
        foreach ($this->config as $obServer) {
            $this->addObServer(new $obServer);
        }
        // 通知观察者
        return $this->notify($this->event);
    }

}