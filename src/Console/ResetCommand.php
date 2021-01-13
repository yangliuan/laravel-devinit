<?php

namespace Yangliuan\LaravelDevinit\Console;

use Illuminate\Console\Command;

class ResetCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'dev:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reset app data and key';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('migrate:refresh'); //刷新数据
        $this->call('passport:keys', ['--force' => 'force']); //重新生成passort-key文件
        $this->call('passport:client', ['--personal' => 'personal']); //重新生成passwort 个人客户端秘钥数据
        $this->call('refresh:adminrules');
    }
}
