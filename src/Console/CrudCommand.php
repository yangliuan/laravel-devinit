<?php

namespace Yangliuan\LaravelDevinit\Console;

use Illuminate\Console\Command;

class CrudCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'dev:crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate crud code';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->choice('Do you want to make migration?', ['yes', 'no'], 0) === 'yes')
        {
            if ($tableName = $this->ask('please input table name? ex:table_name'))
            {
                $this->call('make:migration create_' . $tableName . '_table', ['--create' => $tableName]);
            }
        }

        if ($this->choice('Do you want to make model?', ['yes', 'no'], 0) === 'yes')
        {
            if ($tableName = $this->ask('please input table name? ex:table_name'))
            {
                $this->call('make:model');
            }
        }
    }
}
