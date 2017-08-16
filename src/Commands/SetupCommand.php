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
    protected $calls = [
        [
            'description' => 'Laratrust setup',
            'command' => 'laratrust:setup',
            'params' => [],
        ],
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
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        foreach ($this->calls as $info) {
            $this->line(PHP_EOL.$info['description']);
            $this->call($info['command'], $info['params']);
        }
    }
}
