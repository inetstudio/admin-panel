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
        'laratrust:setup' => [
            'description' => 'Laratrust setup',
            'params' => [],
        ],
        'migrate' => [
            'description' => 'Migration',
            'params' => [],
        ],
        'optimize' => [
            'description' => 'Optimize',
            'params' => [],
        ],
        'inetstudio:panel:admin' => [
            'description' => 'Create admin user',
            'params' => [],
        ],
        'inetstudio:panel:folders' => [
            'description' => 'Create folders',
            'params' => [],
        ],
        'vendor:publish' => [
            'description' => 'Publish public',
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
        foreach ($this->calls as $command => $info) {
            $this->line(PHP_EOL.$info['description']);
            $this->call($command, $info['params']);
        }
    }
}
