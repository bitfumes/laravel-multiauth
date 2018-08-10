<?php

namespace Bitfumes\Multiauth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class Authname extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multiauth:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Name for multiauth, like if choosed admin then https://localhost:8000/admin';

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
        $guardName = __DIR__.'/../../../config/guardName.php';
        // File::append($guardName, ['guards' => 'name']);
        dd(require $guardName);
    }
}
