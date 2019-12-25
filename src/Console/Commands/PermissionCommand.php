<?php

namespace Bitfumes\Multiauth\Console\Commands;

use Illuminate\Console\Command;

class PermissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multiauth:permissions {model} {--name=}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create permission CRUD part for given model';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $model                 = $this->argument('model');
        $permissionModel       = config('multiauth.models.permission');
        if ($name = $this->option('name')) {
            $tasks = [$name];
        } else {
            $tasks = ['Create', 'Read', 'Update', 'Delete'];
        }

        foreach ($tasks as $task) {
            $name       = "{$task}{$model}";
            $permission = $permissionModel::create(['name' => $name, 'parent'=>$model]);
            $this->info("created $name permission,");
        }

        $this->info('Done.');
    }
}
