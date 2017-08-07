<?php

namespace InetStudio\AdminPanel\Commands;

use App\Role;
use App\User;
use Illuminate\Console\Command;

class CreateAdminCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'inetstudio:panel:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $roles = Role::where('name', 'admin')->get();
        if ($roles->count() == 1) {
            $role = $roles->first();
        } else {
            $role = new Role();
            $role->name = 'admin';
            $role->display_name = 'Администратор';
            $role->description = 'Пользователь, у которого есть доступ в административную панель';
            $role->save();
        }

        $users = User::where('name', 'admin')->get();
        if ($users->count() == 1) {
            $user = $users->first();
        } else {
            $user = new User();
            $user->name = 'admin';
            $user->email = 'admin@example.com';
            $user->password = bcrypt('password');
            $user->save();
        }

        $user->syncRoles($role);
    }
}
