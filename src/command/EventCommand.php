<?php
/**
 * 事件命令
 *
 * @descr   可使用命令检查并生成事件
 * @author  qiyunhai<156457917@qq.com>
 */

namespace Qiyunhai\Event\command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class EventCommand extends Command
{
    protected static $defaultName = 'qiyunhai:event';
    protected static $defaultDescription = '操作事件';
    private $default_event = 'plugin.qiyunhai.event.event';

    /**
     * @return void
     */
    protected function configure()
    {
        // 检查并生成事件
        $this->addOption('listen', 'l',InputOption::VALUE_NONE, '检查并生成配置文件里面的事件');
        // 事件配置
        $this->addOption('config', 'c',InputOption::VALUE_REQUIRED, '指定事件配置，默认：'.$this->default_event);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // 配置文件
        $events = $input->getOption('config') ?: $this->default_event;
        $config = config($events);
        if(!$config) {
            $output->writeln("配置: {$events} 不存在");
            return 0;
        }
        // 生成未生成的事件
        if($input->getOption('listen')) {
            // 获取
            $event_tpl = '';
            $listen_tpl = '';
            foreach ($config as $event => $listen) {
                if(!class_exists($event)) {
                    if($event_tpl === '') $event_tpl = file_get_contents(dirname(__DIR__).'/tpl/event.tpl');
                    $file_path = $this->createEventFile($event, $event_tpl);
                    $output->writeln("生成文件：{$file_path}");
                }
                foreach ($listen as $value) {
                    if(!class_exists($value)) {
                        if($listen_tpl === '') $listen_tpl = file_get_contents(dirname(__DIR__).'/tpl/listen.tpl');
                        $file_path = $this->createEventFile($value, $listen_tpl, $event);
                        $output->writeln("生成文件：{$file_path}");
                    }
                }
            }
        }
        return self::SUCCESS;
    }

    /**
     * 创建事件文件
     */
    private function createEventFile($path = '', $content = '', $event = false)
    {
        $namespace = substr($path, 0, strrpos($path, '\\'));
        $class = ltrim(substr($path, strrpos($path, '\\'), strlen($path)), '\\');
        $content = str_replace(['{NAMESPACE}', '{CLASS}'], [$namespace, $class], $content);
        if($event) {
            $event_name = ltrim(substr($event, strrpos($event, '\\'), strlen($event)), '\\');
            $content = str_replace(['{EVENT}', '{EVENT_NAME}'], [$event, $event_name], $content);
        }
        $dir = base_path().DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
        @mkdir(iconv("UTF-8", "GBK", $dir),0777,true);
        $file = $dir.DIRECTORY_SEPARATOR.$class.'.php';
        if(!is_file($file)) {
            file_put_contents($file, $content);
            return $file;
        }
    }

}