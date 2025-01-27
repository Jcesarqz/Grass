<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearCacheCommand extends Command
{
    protected $signature = 'cache:clear-all'; //command name
    protected $description = 'Run these commands: 
                                cache:clear
                                config:clear
                                route:clear
                                view:clear
                                vent:clear
                                optimize:clear';

    public function handle()
    {
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('route:clear');
        $this->call('view:clear');
        $this->call('event:clear');
        $this->call('optimize:clear');

        $this->info('All caches cleared successfully.');
    }
}
