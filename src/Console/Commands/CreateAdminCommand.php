<?php

namespace InetStudio\AdminPanel\Console\Commands;

use App\Role;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateAdminCommand extends Command
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $name = 'inetstudio:panel:admin';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Create admin user';

    /**
     * Запуск команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $role = Role::where('name', 'admin')->first();
        $role = ($role) ?: Role::create([
            'name' => 'admin',
            'display_name' => 'Администратор',
            'description' => 'Пользователь, у которого есть доступ в административную панель',
        ]);

        $user = User::where('name', 'admin')->first();
        $user = ($user) ?: User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        if (DB::table('role_user')->where('user_id', $user->id)->where('role_id', $role->id)->where('user_type', get_class($user))->count() == 0) {
            DB::table('role_user')->insert([
                [
                    'user_id' => $user->id,
                    'role_id' => $role->id,
                    'user_type' => get_class($user),
                ],
            ]);
        }
    }
}
