<?php

/**
 * 开发中
 */

namespace Yangliuan\LaravelDevinit\Console;

use Illuminate\Console\Command;

class CodeBuildCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'dev:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'build crud code inclued:migration,model,controller,request validator';


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
