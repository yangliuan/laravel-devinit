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
        if (app()->environment() === 'production') {
            return $this->error('can not run application in production');
        }

        system('php artisan migrate:refresh'); //刷新数据
        system('php artisan passport:keys --force'); //重新生成passort-key文件
        system('php artisan passport:client --personal'); //重新生成passwort 个人客户端秘钥数据
        system('php artisan dev:refresh-rules'); //刷新权限路由
    }
}
