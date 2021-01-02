<?php

namespace Yangliuan\LaravelDevinit\Makes;

use Illuminate\Filesystem\Filesystem;
use Yangliuan\LaravelDevinit\Console\DevMakeCommand;

class MakeFormRequest
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
        $name = $this->devMakeCommandObj->getObjName('Name');
        //$this->makeRequest('ApiRequest', 'request');
        $this->makeRequest($name . 'Request', 'request_model');
    }

    protected function makeRequest($name, $stubname)
    {
        $path = $this->getPath($name, 'request');

        if ($this->files->exists($path))
        {
            return $this->devMakeCommandObj->comment("x $path" . ' (Skipped)');
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->compileStub($stubname));

        $this->devMakeCommandObj->info('+ ' . $path);
    }
}
