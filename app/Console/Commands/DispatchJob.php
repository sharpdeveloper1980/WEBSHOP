<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DispatchJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job:dispatch {job} {queue} {parameter?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch job';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $class = '\\App\\Jobs\\' . $this->argument('job');
        if(!class_exists($class)){
            $this->error("{$class} class Not exists");
        } else {
            if ($this->argument('parameter')) {
                $job = new $class($this->argument('parameter'));
            } else {
                $job = new $class();
            }
            
            dispatch($job)->onQueue($this->argument('queue'));
            $this->info("Successfully Dispatch {$class} ");
        }
    }
}