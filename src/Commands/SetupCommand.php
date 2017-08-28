<?php

namespace InetStudio\AdminPanel\Commands;

use Illuminate\Console\Command;

class SetupCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'inetstudio:panel:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup admin panel';

    /**
     * Commands to call with their description.
     *
     * @var array
     */
    protected $calls = [];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
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
    private function initCommands()
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
                'description' => 'Revisionable setup',
                'command' => 'migrate',
                'params' => [
                    '--path' => 'vendor/venturecraft/revisionable/src/migrations',
                ],
            ],
            [
                'description' => 'Migration',
                'command' => 'migrate',
                'params' => [],
            ],
            [
                'description' => 'Optimize',
                'command' => 'optimize',
                'params' => [],
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
                    '--provider' => 'InetStudio\AdminPanel\AdminPanelServiceProvider',
                    '--tag' => 'public',
                    '--force' => true,
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
