<?php

namespace InetStudio\AdminPanel\Console\Commands;

/**
 * Class SetupCommand.
 */
class SetupCommand extends BaseSetupCommand
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
     * Инициализация команд.
     *
     * @return void
     */
    protected function initCommands(): void
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
                'description' => 'Audit setup config',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'OwenIt\Auditing\AuditingServiceProvider',
                    '--tag' => 'config',
                ],
            ],
            [
                'type' => 'artisan',
                'description' => 'Audit setup migrations',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'OwenIt\Auditing\AuditingServiceProvider',
                    '--tag' => 'migrations',
                ],
            ],
            [
                'type' => 'artisan',
                'description' => 'Migration',
                'command' => 'migrate',
            ],
        ];
    }
}
