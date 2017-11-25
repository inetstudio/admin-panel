<?php

namespace InetStudio\AdminPanel\Console\Commands;

use Illuminate\Console\Command;

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

            $this->line(PHP_EOL.$info['description']);
            $this->call($info['command'], $info['params']);
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
                'description' => 'Laratrust setup',
                'command' => 'laratrust:setup',
                'params' => [],
            ] : [],
            [
                'description' => 'Meta setup',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'Phoenix\EloquentMeta\ServiceProvider',
                    '--tag' => 'migrations',
                ],
            ],
            [
                'description' => 'Medialibrary setup',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'Spatie\MediaLibrary\MediaLibraryServiceProvider',
                    '--tag' => 'migrations',
                ],
            ],
            [
                'description' => 'Publish migrations',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'InetStudio\AdminPanel\Providers\AdminPanelServiceProvider',
                    '--tag' => 'migrations',
                ],
            ],
            [
                'description' => 'Migration',
                'command' => 'migrate',
                'params' => [],
            ],
            [
                'description' => 'Revisionable setup',
                'command' => 'migrate',
                'params' => [
                    '--path' => 'vendor/venturecraft/revisionable/src/migrations',
                ],
            ],
            [
                'description' => 'Create admin user',
                'command' => 'inetstudio:panel:admin',
                'params' => [],
            ],
            [
                'description' => 'Create folders',
                'command' => 'inetstudio:panel:folders',
                'params' => [],
            ],
            [
                'description' => 'Publish public',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'InetStudio\AdminPanel\Providers\AdminPanelServiceProvider',
                    '--tag' => 'public',
                    '--force' => true,
                ],
            ],
            [
                'description' => 'Publish config',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'InetStudio\AdminPanel\Providers\AdminPanelServiceProvider',
                    '--tag' => 'config',
                ],
            ],
            [
                'description' => 'Publish medialibrary config',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'Spatie\MediaLibrary\MediaLibraryServiceProvider',
                    '--tag' => 'config',
                ],
            ],
        ];
    }
}
