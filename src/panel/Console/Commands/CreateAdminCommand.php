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
        $adminRole = Role::where('name', 'admin')->first();
        $adminRole = ($adminRole) ?: Role::create([
            'name' => 'admin',
            'display_name' => 'Администратор',
            'description' => 'Пользователь, у которого есть доступ в административную панель.',
        ]);

        $userRole = Role::where('name', 'user')->first();
        $userRole = ($userRole) ?: Role::create([
            'name' => 'user',
            'display_name' => 'Пользователь',
            'description' => 'Пользователь, зарегистрировавшийся через сайт.',
        ]);

        $socialUserRole = Role::where('name', 'social_user')->first();
        $socialUserRole = ($socialUserRole) ?: Role::create([
            'name' => 'social_user',
            'display_name' => 'Пользователь социльной сети',
            'description' => 'Пользователь, зарегистрировавшийся через социальную сеть.',
        ]);

        $user = User::where('name', 'admin')->first();
        $user = ($user) ?: User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        if (DB::table('role_user')->where('user_id', $user->id)->where('role_id', $adminRole->id)->where('user_type', get_class($user))->count() == 0) {
            DB::table('role_user')->insert([
                [
                    'user_id' => $user->id,
                    'role_id' => $adminRole->id,
                    'user_type' => get_class($user),
                ],
            ]);
        }
    }
}
