<?php

namespace Yangliuan\LaravelDevinit\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'devinit:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the laravel devtools package';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('migrate');
        $this->info('start install laravel/passport...');
        system('composer require laravel/passport && php artisan passport:install --uuids');
        $this->info('install laravel/passport successed!');

        $this->info('start install barryvdh/laravel-ide-helper...');
        system('composer require barryvdh/laravel-ide-helper --dev');
        $this->info('install barryvdh/laravel-ide-helper successed!');

        $this->info('start install laravel/telescope...');
        system('composer require laravel/telescope --dev && php artisan telescope:install');
        $this->call('migrate');
        $this->info('install laravel/telescope successed!');

        $this->info('start install fruitcake/laravel-telescope-toolbar...');
        system('composer require fruitcake/laravel-telescope-toolbar --dev');
        system('php artisan vendor:publish --provider="Fruitcake\\TelescopeToolbar\\ToolbarServiceProvider"');
        $this->info('install fruitcake/laravel-telescope-toolbar successed!');

        $this->info('start install tucker-eric/eloquentfilter');
        system('composer require tucker-eric/eloquentfilter');
        $this->info('install tucker-eric/eloquentfilter successed!');

        $this->call('devinit:publish', ['--force' => 'force']);
        $this->call('devstub:publish', ['--force' => 'force']);
    }
}
