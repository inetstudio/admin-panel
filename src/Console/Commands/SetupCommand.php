<?php

namespace InetStudio\AdminPanel\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

/**
 * Class SetupCommand.
 */
class SetupCommand extends Command
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $name = 'inetstudio:panel:setup';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Setup admin panel';

    /**
     * Список дополнительных команд.
     *
     * @var array
     */
    protected $calls = [];

    /**
     * Запуск команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->initCommands();

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
     *
     * @return void
     */
    private function initCommands(): void
    {
        $this->calls = [
            [
                'type' => 'artisan',
                'description' => 'ACL setup',
                'command' => 'inetstudio:acl:setup',
            ],
            [
                'type' => 'artisan',
                'description' => 'Meta setup',
                'command' => 'inetstudio:meta:setup',
            ],
            [
                'type' => 'artisan',
                'description' => 'Uploads setup',
                'command' => 'inetstudio:uploads:setup',
            ],
            [
                'type' => 'artisan',
                'description' => 'Revisionable setup',
                'command' => 'migrate',
                'params' => [
                    '--path' => 'vendor/venturecraft/revisionable/src/migrations',
                ],
            ],
            [
                'type' => 'cli',
                'description' => 'Composer dump',
                'command' => 'composer dump-autoload',
            ],
        ];
    }
}
