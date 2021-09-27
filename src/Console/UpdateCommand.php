<?php

namespace Yangliuan\LaravelDevinit\Console;

use Illuminate\Console\Command;
use Yangliuan\LaravelDevinit\Traits\Register;

class UpdateCommand extends Command
{
    use Register;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'dev:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update base code';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('vendor:publish', [
            '--tag' => 'devinit-base',
            '--force' => 'force',
        ]);
        $this->updateLoginLockKernel();
    }
}
