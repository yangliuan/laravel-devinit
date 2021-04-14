<?php

namespace Yangliuan\LaravelDevinit\Traits;

trait Register
{
    public function regAppConfig()
    {
        $this->replaceInFile(
            config_path('app.php'),
            ['\'timezone\' => \'UTC\'', '\'locale\' => \'en\'', '\'fallback_locale\' => \'en\'', '\'faker_locale\' => \'en_US\''],
            ['\'timezone\' => \'PRC\'', '\'locale\' => \'zh_CN\'', '\'fallback_locale\' => \'zh_CN\'', '\'faker_locale\' => \'zh_CN\'']
        );
    }

    public function regCorsConfig()
    {
        $this->replaceInFile(
            config_path('cors.php'),
            ['\'paths\' => [\'api/*\', \'sanctum/csrf-cookie\']'],
            ['\'paths\' => [\'api/*\', \'admin/*\', \'common/*\',  \'sanctum/csrf-cookie\']']
        );
    }

    public function regEloquentfilterConfig()
    {
        $this->replaceInFile(
            config_path('eloquentfilter.php'),
            'ModelFilters',
            'Filters'
        );
    }

    public function regAuthConfigPassport()
    {
        $this->replaceInFile(
            config_path('auth.php'),
            [
                '\'api\' => [' . PHP_EOL . '            \'driver\' => \'token\',' . PHP_EOL . '            \'provider\' => \'users\',' . PHP_EOL . '            \'hash\' => false,' . PHP_EOL . '        ],',
                '\'users\' => [' . PHP_EOL . '            \'driver\' => \'eloquent\',' . PHP_EOL . '            \'model\' => App\Models\User::class,' . PHP_EOL . '        ],'
            ],
            [
                '\'api\' => [' . PHP_EOL . '            \'driver\' => \'passport\',' . PHP_EOL . '            \'provider\' => \'users\',' . PHP_EOL . '            \'hash\' => false,' . PHP_EOL . '        ],' . PHP_EOL . PHP_EOL . '        \'admin\' => [' . PHP_EOL . '            \'driver\' => \'passport\',' . PHP_EOL . '            \'provider\' => \'admin\',' . PHP_EOL . '            \'hash\' => false,' . PHP_EOL . '        ],' . PHP_EOL,
                '\'users\' => [' . PHP_EOL . '            \'driver\' => \'eloquent\',' . PHP_EOL . '            \'model\' => App\Models\User::class,' . PHP_EOL . '        ],' . PHP_EOL . PHP_EOL . '        \'admin\' => [' . PHP_EOL . '            \'driver\' => \'eloquent\',' . PHP_EOL . '            \'model\' => App\Models\Admin::class,' . PHP_EOL . '        ],' . PHP_EOL
            ]
        );
    }

    public function regConfigLoggingBySms()
    {
        $this->replaceInFile(
            config_path('logging.php'),
            [
                '\'emergency\' => [' . PHP_EOL . '            \'path\' => storage_path(\'logs/laravel.log\'),' . PHP_EOL . '        ],'
            ],
            [
                '\'emergency\' => [' . PHP_EOL . '            \'path\' => storage_path(\'logs/laravel.log\'),' . PHP_EOL . '        ],' . PHP_EOL . PHP_EOL . '        \'smscode\' => [' . PHP_EOL . '            \'driver\' => \'daily\',' . PHP_EOL . '            \'path\' => storage_path(\'logs/smscode/easysms.log\'),' . PHP_EOL . '            \'level\' => \'info\',' . PHP_EOL . '            \'days\' => 30' . PHP_EOL . '        ],'
            ]
        );
    }

    public function regAppServiceProvider()
    {
        $this->replaceInFile(
            app_path('Providers/AppServiceProvider.php'),
            [
                'use Illuminate\Support\ServiceProvider;',
                'public function register()' . PHP_EOL . '    {' . PHP_EOL . '        //' . PHP_EOL . '    }'
            ],
            [
                'use Illuminate\Support\ServiceProvider;' . PHP_EOL . 'use Illuminate\Support\Carbon;' . PHP_EOL . 'use Illuminate\Http\Resources\Json\JsonResource;' . PHP_EOL . 'use App\Http\Middleware\AdminRBAC;',
                'public function register()' . PHP_EOL . '    {' . PHP_EOL . '        Carbon::setLocale(\'zh\');' . PHP_EOL . '        JsonResource::withoutWrapping();' . PHP_EOL . '        $this->app->singleton(AdminRBAC::class);' . PHP_EOL . '    }'
            ]
        );
    }

    public function regAppServiceProviderByEasysms()
    {

        $this->replaceInFile(
            app_path('Providers/AppServiceProvider.php'),
            [
                'use App\Http\Middleware\AdminRBAC;',
                'public function boot()' . PHP_EOL . '    {' . PHP_EOL . '        //' . PHP_EOL . '    }'
            ],
            [
                'use App\Http\Middleware\AdminRBAC;' . PHP_EOL . 'use Overtrue\EasySms\EasySms;',
                'public function boot()' . PHP_EOL . '    {' . PHP_EOL . '        $this->app->singleton(EasySms::class, function () {' . PHP_EOL . '            return new EasySms(config(\'easysms\'));' . PHP_EOL . '        });' . PHP_EOL . '        $this->app->alias(EasySms::class, \'easysms\');' . PHP_EOL . '    }',
            ]
        );
    }

    public function regAuthServiceProviderByPassort()
    {
        $this->replaceInFile(
            app_path('Providers/AuthServiceProvider.php'),
            [
                'use Illuminate\Support\Facades\Gate;',
                '$this->registerPolicies();'
            ],
            [
                'use Illuminate\Support\Facades\Gate;' . PHP_EOL . 'use Laravel\Passport\Passport;',
                '$this->registerPolicies();' . PHP_EOL . '        Passport::routes();' . PHP_EOL . '        Passport::loadKeysFrom(\'\');' . PHP_EOL . '        Passport::personalAccessTokensExpireIn(now()->addDays(30));' . PHP_EOL . '        Passport::tokensCan([' . PHP_EOL . '            \'api\' => \'Request Api\',' . PHP_EOL . '            \'admin\' => \'Request Admin\',' . PHP_EOL . '            \'common\' => \'Request Commo\',' . PHP_EOL . '        ]);'
            ]

        );
    }

    public function regHttpKernelByPassport()
    {
        $this->replaceInFile(
            app_path('Http/Kernel.php'),
            [
                '\'admin.rbac\' => \App\Http\Middleware\AdminRBAC::class,'
            ],
            [
                '\'admin.rbac\' => \App\Http\Middleware\AdminRBAC::class,' . PHP_EOL . '        \'scopes\' => \Laravel\Passport\Http\Middleware\CheckScopes::class,' . PHP_EOL . '        \'scope\' => \Laravel\Passport\Http\Middleware\CheckForAnyScope::class,'
            ]

        );
    }

    /**
     * Replace a given string in a given file.
     *
     * @param  string  $path
     * @param  string  $search
     * @param  string  $replace
     * @return void
     */
    protected function replaceInFile($path, $search, $replace)
    {
        return file_put_contents(
            $path,
            str_replace($search, $replace, file_get_contents($path))
        );
    }
}
