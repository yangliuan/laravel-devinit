<?php

namespace Yangliuan\LaravelDevinit;

class DevinitServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->commands([
                Console\InstallCommand::class,
                Console\PublishCommand::class,
                Console\StubPublishCommand::class,
            ]);

            $this->publishes([
                __DIR__ . '/../config/auth.php' => config_path('auth.php'),
                __DIR__ . '/../eloquentfilter.php' => config_path('eloquentfilter.php')
            ], 'devinit-config');

            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations')
            ], 'devinit-migrations');

            $this->publishes([
                __DIR__ . '/../resources/views/welcome.blade.php' => resource_path('views/welcome.blade.php')
            ], 'devinit-resources');

            $this->publishes([
                __DIR__ . '/stubs/routes/admin.stub' => base_path('routes/admin.php'),
                __DIR__ . '/stubs/routes/web.stub' => base_path('routes/web.php'),
            ], 'devinit-routes');

            $this->publishes([
                __DIR__ . '/stubs/app/Providers/AppServiceProvider.stub' => app_path('Providers/AppServiceProvider.php'),
                __DIR__ . '/stubs/app/Providers/AuthServiceProvider.stub' => app_path('Providers/AuthServiceProvider.php'),
                __DIR__ . '/stubs/app/Providers/RouteServiceProvider.stub' => app_path('Providers/RouteServiceProvider.php'),
            ], 'devinit-providers');

            $this->publishes([
                __DIR__ . '/stubs/app/Middleware/AdminRBAC.stub' => app_path('Http/Middleware/AdminRBAC.php'),
                __DIR__ . '/stubs/app/Middleware/Kernel.stub' => app_path('Http/Kernel.php'),
            ], 'devinit-middlewares');

            $this->publishes([
                __DIR__ . '/stubs/app/Models/BaseModel.stub' => app_path('Models/BaseModel.php'),
                __DIR__ . '/stubs/app/Models/Admin.stub' => app_path('Models/Admin.php'),
                __DIR__ . '/stubs/app/Models/AdminGroups.stub' => app_path('Models/AdminGroups.php'),
                __DIR__ . '/stubs/app/Models/AdminRules.stub' => app_path('Models/AdminRules.php'),
                __DIR__ . '/stubs/app/Models/AdminSyslog.stub' => app_path('Models/AdminSyslog.php'),
                __DIR__ . '/stubs/app/Models/User.stub' => app_path('Models/User.php'),
            ], 'devinit-models');

            $this->publishes([
                __DIR__ . '/stubs/app/Controllers/AdminController.stub' => app_path('Http/Controllers/Admin/AdminController.php'),
                __DIR__ . '/stubs/app/Controllers/GroupController.stub' => app_path('Http/Controllers/Admin/GroupController.php'),
                __DIR__ . '/stubs/app/Controllers/PageController.stub' => app_path('Http/Controllers/PageController.php'),
            ], 'devinit-controllers');

            $this->publishes([
                __DIR__ . '/stubs/app/Request/Request.stub' => app_path('Http/Requests/Request.php'),
                __DIR__ . '/stubs/app/Request/Admin/AdminRequest.stub' => app_path('Http/Requests/Admin/AdminRequest.php'),
            ], 'devinit-request');

            $this->publishes([
                __DIR__ . '/stubs/app/Resources/Resource.stub' => app_path('Resources/Resource.php'),
                __DIR__ . '/stubs/app/Resources/AdminGroupResource.stub' => app_path('Resources/AdminGroupResource.php'),
                __DIR__ . '/stubs/app/Resources/AdminResource.stub' => app_path('Resources/AdminResource.php'),
                __DIR__ . '/stubs/app/Resources/AdminSyslogResource.stub' => app_path('Resources/AdminSyslogResource.php'),
            ], 'devinit-app-resources');

            $this->publishes([
                __DIR__ . '/stubs/app/Traits/DateFormat.stub' => app_path('Traits/DateFormat.php'),
            ], 'devinit-traits');
        }
    }
}
