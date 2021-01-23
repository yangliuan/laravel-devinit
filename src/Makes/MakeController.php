<?php

namespace Yangliuan\LaravelDevinit\Makes;

use Illuminate\Filesystem\Filesystem;
use Yangliuan\LaravelDevinit\Console\DevMakeCommand;


class MakeController
{
    use MakerTrait;


    protected $devMakeCommandObj;

    /**
     * Create a new instance.
     *
     * @param DevMakeCommand $devMakeCommand
     * @param Filesystem $files
     * @return void
     */
    function __construct(DevMakeCommand $devMakeCommand, Filesystem $files)
    {
        $this->files = $files;
        $this->devMakeCommandObj = $devMakeCommand;

        $this->start();
    }

    /**
     * Start make controller.
     *
     * @return void
     */
    private function start()
    {
        $name = 'Admin/' . $this->devMakeCommandObj->getObjName('Names') . 'Controller';
        $path = $this->getPath($name, 'controller');

        if ($this->files->exists($path))
        {
            return $this->devMakeCommandObj->comment("x " . $path);
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->compileControllerStub());

        $this->devMakeCommandObj->info('+ ' . $path);
    }

    /**
     * Compile the controller stub.
     *
     * @return string
     */
    protected function compileControllerStub()
    {
        $stub = $this->files->get(substr(__DIR__, 0, -5) . 'Stubs/controller.stub');

        $this->buildStub($this->devMakeCommandObj->getMeta(), $stub);

        return $stub;
    }
}
