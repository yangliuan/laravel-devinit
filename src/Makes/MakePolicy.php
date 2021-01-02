<?php

namespace Yangliuan\LaravelDevinit\Makes;

use Illuminate\Filesystem\Filesystem;
use Yangliuan\LaravelDevinit\Console\DevMakeCommand;
use Yangliuan\LaravelDevinit\Validators\SchemaParser as ValidatorParser;
use Yangliuan\LaravelDevinit\Validators\SyntaxBuilder as ValidatorSyntax;

class MakePolicy
{
    use MakerTrait;

    /**
     * Store name from Model
     *
     * @var DevMakeCommand
     */
    protected $devMakeCommandObj;

    /**
     * Create a new instance.
     *
     * @param DevMakeCommand $devMakeCommandObj
     * @param Filesystem $files
     * @return void
     */
    function __construct(DevMakeCommand $devMakeCommandObj, Filesystem $files)
    {
        $this->files = $files;
        $this->devMakeCommandObj = $devMakeCommandObj;

        $this->start();
    }

    /**
     * Start make controller.
     *
     * @return void
     */
    private function start()
    {
        $model = $this->devMakeCommandObj->getObjName('Name');
        $policy_name = $model . 'Policy';
        $this->makePolicy('Policy', 'base_policy');
        $this->makePolicy($policy_name, 'policy');

        $this->registerPolicy($model, $policy_name);
    }

    protected function makePolicy($name, $stubname)
    {
        $path = $this->getPath($name, 'policy');

        if ($this->files->exists($path))
        {
            return $this->devMakeCommandObj->comment("x " . $path);
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->compileStub($stubname));

        $this->devMakeCommandObj->info('+ ' . $path);
    }

    protected function compileStub($filename)
    {
        $stub = $this->files->get(substr(__DIR__, 0, -5) . 'Stubs/' . $filename . '.stub');

        $this->buildStub($this->devMakeCommandObj->getMeta(), $stub);
        // $this->replaceValidator($stub);

        return $stub;
    }

    protected function registerPolicy($model, $policy_name)
    {
        $path = './app/Providers/AuthServiceProvider.php';
        $content = $this->files->get($path);

        if (strpos($content, $policy_name) === false)
        {
            $content = str_replace(
                'policies = [',
                "policies = [\n\t\t \App\Models\\$model::class => \App\Policies\\$policy_name::class,",
                $content
            );
            $this->files->put($path, $content);

            return $this->devMakeCommandObj->info('+ ' . $path . ' (Updated)');
        }

        return $this->devMakeCommandObj->comment("x " . $path . ' (Skipped)');
    }


    // /**
    //  * Replace validator in the controller stub.
    //  *
    //  * @return $this
    //  */
    // private function replaceValidator(&$stub)
    // {
    //     if($schema = $this->devMakeCommandObj->option('validator')){
    //         $schema = (new ValidatorParser)->parse($schema);
    //     }

    //     $schema = (new ValidatorSyntax)->create($schema, $this->devMakeCommandObj->getMeta(), 'validation');
    //     $stub = str_replace('{{validation_fields}}', $schema, $stub);

    //     return $this;
    // }


}
