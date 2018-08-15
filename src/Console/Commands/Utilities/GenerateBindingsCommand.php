<?php

namespace InetStudio\AdminPanel\Console\Commands\Utilities;

use Illuminate\Console\Command;

/**
 * Class GenerateBindingsCommand.
 */
class GenerateBindingsCommand extends Command
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $name = 'inetstudio:make:bindings';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Setup admin panel';

    /**
     * Запуск команды.
     *
     * @throws \Throwable
     */
    public function handle(): void
    {
        $namespace = $this->ask('NameSpace');
        $namespace = 'InetStudio\\'.$namespace.'\\Providers';

        $className = $this->ask('ClassName');
        $className = $className.'BindingsServiceProvider';

        $path = $this->ask('Path to file');
        $path = 'packages/inetstudio/'.$path.'/src/Providers';
        
        $bindings = \BindingsHelpers::getPackageBindings(base_path($path).'/../Contracts');

        $data = view(
            'admin::stubs.BindingsServiceProvider',
            compact('namespace', 'className', 'bindings')
        )->render();

        $filepath = base_path($path).'/'.$className.'.php';

        file_put_contents($filepath, $data);
    }
}
