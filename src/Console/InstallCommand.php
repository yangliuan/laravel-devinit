<?php

namespace Yangliuan\LaravelDevinit\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'dev:init';

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
        $this->info('start install laravel/passport...');
        system('composer require laravel/passport');
        system('php artisan migrate');
        system('php artisan passport:install --uuids');
        $this->info('install laravel/passport successed!');

        $this->info('start install overtrue/laravel-lang');
        system('composer require overtrue/laravel-lang');
        system('php artisan lang:publish zh_CN');
        $this->info('install overtrue/laravel-lang successed!');

        $this->info('start install tucker-eric/eloquentfilter');
        system('composer require tucker-eric/eloquentfilter');
        system('php artisan vendor:publish --provider="EloquentFilter\ServiceProvider"');
        $this->info('install tucker-eric/eloquentfilter successed!');

        system('php artisan dev:publish --force');

        if ($this->choice('Do you want to install composer require laravel/horizon?', ['yes', 'no'], 0) === 'yes')
        {
            system('composer require laravel/horizon');
            system('php artisan horizon:install');
            system('php artisan migrate');
        }

        if ($this->choice('Do you want to install barryvdh/laravel-ide-helper?', ['yes', 'no'], 0) === 'yes')
        {
            $this->info('start install barryvdh/laravel-ide-helper...');
            system('composer require barryvdh/laravel-ide-helper --dev');
            system('php artisan ide-helper:generate');
            system('php artisan vendor:publish --provider="Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider" --tag=config');
            $this->info('install barryvdh/laravel-ide-helper successed!');
        }

        if ($this->choice('Do you want to install laravel/telescope?', ['yes', 'no'], 0) === 'yes')
        {
            $this->info('start install laravel/telescope...');
            system('composer require laravel/telescope --dev');
            system('php artisan telescope:install');
            system('php artisan migrate');
            $this->info('install laravel/telescope successed!');
        }

        system('php artisan dev:reset');
    }
}
