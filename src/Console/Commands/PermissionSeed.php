<?php

namespace Bitfumes\Multiauth\Console\Commands;

use Illuminate\Console\Command;
use Bitfumes\Multiauth\Model\Permission;

class PermissionSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multiauth:seed-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed default permissions for multiauth package';

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
        $this->info('Seeding of started.');
        factory(Permission::class, 5)->create();
        $this->info('Seeding of permission done.');
    }
}
