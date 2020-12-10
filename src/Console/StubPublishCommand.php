<?php

namespace Yangliuan\LaravelDevinit\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class StubPublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:publish {--force : Overwrite any existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish all stubs that are available for customization';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (!is_dir($stubsPath = $this->laravel->basePath('stubs')))
        {
            (new Filesystem)->makeDirectory($stubsPath);
        }

        $files = [
            __DIR__ . '/../devstubs/job.queued.stub' => $stubsPath . '/job.queued.stub',
            __DIR__ . '/../devstubs/job.stub' => $stubsPath . '/job.stub',
            __DIR__ . '/../devstubs/model.pivot.stub' => $stubsPath . '/model.pivot.stub',
            __DIR__ . '/../devstubs/model.stub' => $stubsPath . '/model.stub',
            __DIR__ . '/../devstubs/request.stub' => $stubsPath . '/request.stub',
            __DIR__ . '/../devstubs/resource.stub' => $stubsPath . '/resource.stub',
            __DIR__ . '/../devstubs/resource-collection.stub' => $stubsPath . '/resource-collection.stub',
            __DIR__ . '/../devstubs/test.stub' => $stubsPath . '/test.stub',
            __DIR__ . '/../devstubs/test.unit.stub' => $stubsPath . '/test.unit.stub',
            __DIR__ . '/../devstubs/factory.stub' => $stubsPath . '/factory.stub',
            __DIR__ . '/../devstubs/seeder.stub' => $stubsPath . '/seeder.stub',
            __DIR__ . '/../devstubs/migration.create.stub' => $stubsPath . '/migration.create.stub',
            __DIR__ . '/../devstubs/migration.stub' => $stubsPath . '/migration.stub',
            __DIR__ . '/../devstubs/migration.update.stub' => $stubsPath . '/migration.update.stub',
            __DIR__ . '/../devstubs/console.stub' => $stubsPath . '/console.stub',
            __DIR__ . '/../devstubs/policy.plain.stub' => $stubsPath . '/policy.plain.stub',
            __DIR__ . '/../devstubs/policy.stub' => $stubsPath . '/policy.stub',
            __DIR__ . '/../devstubs/rule.stub' => $stubsPath . '/rule.stub',
            __DIR__ . '/../devstubs/controller.api.stub' => $stubsPath . '/controller.api.stub',
            __DIR__ . '/../devstubs/controller.invokable.stub' => $stubsPath . '/controller.invokable.stub',
            __DIR__ . '/../devstubs/controller.model.api.stub' => $stubsPath . '/controller.model.api.stub',
            __DIR__ . '/../devstubs/controller.model.stub' => $stubsPath . '/controller.model.stub',
            __DIR__ . '/../devstubs/controller.nested.api.stub' => $stubsPath . '/controller.nested.api.stub',
            __DIR__ . '/../devstubs/controller.nested.stub' => $stubsPath . '/controller.nested.stub',
            __DIR__ . '/../devstubs/controller.plain.stub' => $stubsPath . '/controller.plain.stub',
            __DIR__ . '/../devstubs/controller.stub' => $stubsPath . '/controller.stub',
            __DIR__ . '/../devstubs/middleware.stub' => $stubsPath . '/middleware.stub',
        ];

        foreach ($files as $from => $to)
        {
            if (!file_exists($to) || $this->option('force'))
            {
                file_put_contents($to, file_get_contents($from));
            }
        }

        $this->info('Devstubs published successfully.');
    }
}
