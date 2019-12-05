<?php

namespace Bitfumes\Multiauth\Console\Commands;

use Illuminate\Console\Command;

class SeedCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multiauth:seed {--r|role=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed one super admin for multiauth package
                            {--role= : Give any role name to create new role}';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->roleModel       = config('multiauth.models.role');
        $this->adminModel      = config('multiauth.models.admin');
        $this->permissionModel = config('multiauth.models.permission');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $rolename  = $this->option('role');

        $role      = $this->roleModel::whereName($rolename)->first();
        if (!$rolename) {
            $this->error("please provide role as --role='roleName'");

            return;
        }
        $admin = $this->createSuperAdmin($role, $rolename);

        $this->info("You have created an admin name '{$admin->name}' with role of '{$admin->roles->first()->name}' ");
        $this->info("Now log-in with {$admin->email} email and password as 'secret'");
    }

    protected function createSuperAdmin($role, $rolename)
    {
        $prefix = config('multiauth.prefix');
        $admin  = factory($this->adminModel)
            ->create(['email' => "super@{$prefix}.com", 'name' => 'Super ' . ucfirst($prefix)]);
        if (!$role) {
            $role                = factory($this->roleModel)->create(['name' => $rolename]);
            $this->createAndLinkPermissionsTo($role);
        }
        $admin->roles()->attach($role);

        return $admin;
    }

    protected function createAndLinkPermissionsTo($role)
    {
        $models        = ['Admin', 'Role'];
        $tasks         = ['Create', 'Read', 'Update', 'Delete'];
        foreach ($tasks as $task) {
            foreach ($models as $model) {
                $name       = "{$task}{$model}";
                $permission = factory($this->permissionModel)->create(['name' => $name]);
                $role->addPermission([$permission->id]);
            }
        }
    }
}
