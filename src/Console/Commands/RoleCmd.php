<?php

namespace Bitfumes\Multiauth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;

class RoleCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multiauth:role {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new role in your database
                            {name : The name of the Role}';

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
        $role      = $this->argument('name');
        $roleModel = config('multiauth.models.role');

        try {
            factory($roleModel)->create(['name' => $role]);
            $this->info("Role with the name of $role is created");
        } catch (QueryException $e) {
            $this->error("Role name '{$role}' is already exist, choose another name");
        }
    }
}
