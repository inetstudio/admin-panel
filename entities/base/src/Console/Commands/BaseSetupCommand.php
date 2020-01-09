<?php

namespace InetStudio\AdminPanel\Base\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

/**
 * Class BaseSetupCommand.
 */
class BaseSetupCommand extends Command
{
    /**
     * Список дополнительных команд.
     *
     * @var array
     */
    protected $calls = [];

    /**
     * Запуск команды.
     */
    public function handle(): void
    {
        $this->initCommands();
        $this->startCommands();
    }

    /**
     * Запуск дополнительных команд.
     */
    protected function startCommands(): void
    {
        foreach ($this->calls as $info) {
            if (! isset($info['command'])) {
                continue;
            }

            $params = (isset($info['params'])) ? $info['params'] : [];

            $this->line(PHP_EOL.$info['description']);

            switch ($info['type']) {
                case 'artisan':
                    $this->call($info['command'], $params);
                    break;
                case 'cli':
                    $process = new Process($info['command']);
                    $process->run();
                    break;
            }
        }
    }

    /**
     * Инициализация команд.
     */
    protected function initCommands(): void
    {
        $this->calls = [];
    }
}
