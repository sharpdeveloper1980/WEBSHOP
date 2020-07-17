<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:refresh';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refreshes all application cache. Useful in development.';

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
        $this->info('Cleaning cache...');
        $this->callSilent('cache:clear');
        $this->info('Cleaning route cache...');
        $this->callSilent('route:cache');
        $this->info('Cleaning config cache...');
        $this->callSilent('config:cache');
        $this->callSilent('config:clear');
        $this->info('Cleaning view cache...');
        $this->callSilent('view:clear');
        $this->info('Updating classmap...');
        $this->callSilent('clear-compiled');
        $this->callSilent('optimize');
        shell_exec('composer dump-autoload >> /dev/null 2>&1');
        $this->info('Cleaning config cache...');
        $this->callSilent('config:cache');
        $this->callSilent('config:clear');
        $this->info('Completed');
    }
}
