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
        'laratrust:setup' => 'Laratrust setup',
        'migrate' => 'Migration',
        'optimize' => 'Optimize',
        'inetstudio:panel:admin' => 'Create admin user',
        'inetstudio:panel:folders' => 'Create folders',
        'vandor:publish --provider="InetStudio\AdminPanel\AdminPanelServiceProvider" --tag="public" --force' => 'Publish public',
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        foreach ($this->calls as $command => $info) {
            $this->line(PHP_EOL.$info);
            $this->call($command);
        }
    }
}
