<?php

namespace InetStudio\AdminPanel\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

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
            (! class_exists('LaratrustSetupTables')) ? [
                'type' => 'artisan',
                'description' => 'Laratrust setup',
                'command' => 'laratrust:setup',
            ] : [],
            [
                'type' => 'artisan',
                'description' => 'Meta setup',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'Phoenix\EloquentMeta\ServiceProvider',
                    '--tag' => 'migrations',
                ],
            ],
            [
                'type' => 'artisan',
                'description' => 'Medialibrary setup',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'Spatie\MediaLibrary\MediaLibraryServiceProvider',
                    '--tag' => 'migrations',
                ],
            ],
            [
                'type' => 'artisan',
                'description' => 'Publish migrations',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'InetStudio\AdminPanel\Providers\AdminPanelServiceProvider',
                    '--tag' => 'migrations',
                ],
            ],
            [
                'type' => 'artisan',
                'description' => 'Migration',
                'command' => 'migrate',
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
                'type' => 'artisan',
                'description' => 'Create admin user',
                'command' => 'inetstudio:panel:admin',
            ],
            [
                'type' => 'artisan',
                'description' => 'Create folders',
                'command' => 'inetstudio:panel:folders',
            ],
            [
                'type' => 'artisan',
                'description' => 'Publish public',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'InetStudio\AdminPanel\Providers\AdminPanelServiceProvider',
                    '--tag' => 'public',
                    '--force' => true,
                ],
            ],
            [
                'type' => 'artisan',
                'description' => 'Publish config',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'InetStudio\AdminPanel\Providers\AdminPanelServiceProvider',
                    '--tag' => 'config',
                ],
            ],
            [
                'type' => 'artisan',
                'description' => 'Publish medialibrary config',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'Spatie\MediaLibrary\MediaLibraryServiceProvider',
                    '--tag' => 'config',
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
