<?php

namespace Yangliuan\LaravelDevinit;

class DevinitServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole())
        {

            $this->commands([
                Console\InstallCommand::class,
                Console\ResetCommand::class,
                Console\PublishCommand::class,
            ]);

            $this->publishes([
                __DIR__ . '/../config/app.php' => config_path('app.php'),
            ], 'app-config');

            $this->publishes([
                __DIR__ . '/../config/auth.php' => config_path('auth.php'),
                __DIR__ . '/../config/eloquentfilter.php' => config_path('eloquentfilter.php')
            ], 'devinit-config');

            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations')
            ], 'devinit-migrations');

            $this->publishes([
                __DIR__ . '/../resources/views/welcome.blade.php' => resource_path('views/welcome.blade.php')
            ], 'devinit-resources');

            $this->publishes([
                __DIR__ . '/../routes/common.php' => base_path('routes/common.php'),
                __DIR__ . '/../routes/admin.php' => base_path('routes/admin.php'),
                __DIR__ . '/../routes/web.php' => base_path('routes/web.php'),
            ], 'devinit-routes');

            $this->publishes([
                __DIR__ . '/../app/Providers/AppServiceProvider.php' => app_path('Providers/AppServiceProvider.php'),
                __DIR__ . '/../app/Providers/AuthServiceProvider.php' => app_path('Providers/AuthServiceProvider.php'),
                __DIR__ . '/../app/Providers/RouteServiceProvider.php' => app_path('Providers/RouteServiceProvider.php'),
            ], 'devinit-providers');

            $this->publishes([
                __DIR__ . '/../app/Http/Middleware/AdminRBAC.php' => app_path('Http/Middleware/AdminRBAC.php'),
                __DIR__ . '/../app/Http/Kernel.php' => app_path('Http/Kernel.php'),
            ], 'devinit-middlewares');

            $this->publishes([
                __DIR__ . '/../app/Models/BaseModel.php' => app_path('Models/BaseModel.php'),
                __DIR__ . '/../app/Models/Admin.php' => app_path('Models/Admin.php'),
                __DIR__ . '/../app/Models/AdminGroups.php' => app_path('Models/AdminGroups.php'),
                __DIR__ . '/../app/Models/AdminRules.php' => app_path('Models/AdminRules.php'),
                __DIR__ . '/../app/Models/AdminSyslog.php' => app_path('Models/AdminSyslog.php'),
                __DIR__ . '/../app/Models/User.php' => app_path('Models/User.php'),
            ], 'devinit-models');

            $this->publishes([
                __DIR__ . '/../app/Http/Controllers/AdminController.php' => app_path('Http/Controllers/Admin/AdminController.php'),
                __DIR__ . '/../app/Http/Controllers/GroupController.php' => app_path('Http/Controllers/Admin/GroupController.php'),
                __DIR__ . '/../app/Http/Controllers/PageController.php' => app_path('Http/Controllers/PageController.php'),
            ], 'devinit-controllers');

            $this->publishes([
                __DIR__ . '/../app/Http/Request/ApiRequest.php' => app_path('Http/Requests/ApiRequest.php'),
                __DIR__ . '/../app/Http/Request/Admin/AdminRequest.php' => app_path('Http/Requests/Admin/AdminRequest.php'),
            ], 'devinit-request');

            $this->publishes([
                __DIR__ . '/../app/Http/Resources/Resource.php' => app_path('Http/Resources/Resource.php'),
                __DIR__ . '/../app/Http/Resources/AdminGroupResource.php' => app_path('Http/Resources/AdminGroupResource.php'),
                __DIR__ . '/../app/Http/Resources/AdminResource.php' => app_path('Http/Resources/AdminResource.php'),
                __DIR__ . '/../app/Http/Resources/AdminSyslogResource.php' => app_path('Http/Resources/AdminSyslogResource.php'),
            ], 'devinit-app-resources');

            $this->publishes([
                __DIR__ . '/../app/Traits/DateFormat.php' => app_path('Traits/DateFormat.php'),
                __DIR__ . '/../app/Traits/Helps.php' => app_path('Traits/Helps.php'),
            ], 'devinit-traits');
        }
    }
}
