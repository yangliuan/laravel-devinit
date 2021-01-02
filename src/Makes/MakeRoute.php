<?php

/**
 * Created by PhpStorm.
 * User: fernandobritofl
 * Date: 4/22/15
 * Time: 10:34 PM
 */

namespace Yangliuan\LaravelDevinit\Makes;

use Illuminate\Filesystem\Filesystem;
use Yangliuan\LaravelDevinit\Console\DevMakeCommand;
use Yangliuan\LaravelDevinit\Migrations\SchemaParser;
use Yangliuan\LaravelDevinit\Migrations\SyntaxBuilder;

class MakeRoute
{
    use MakerTrait;

    /**
     * Create a new instance.
     *
     * @param DevMakeCommand $devMakeCommandObj
     * @param Filesystem $files
     * @return void
     */
    public function __construct(DevMakeCommand $devMakeCommandObj, Filesystem $files)
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
        $route_name = floatval(app()::VERSION) < 5.3 ? 'route_old' : 'route';
        $path = $this->getPath($name, $route_name);
        $stub = $this->compileRouteStub();

        if (strpos($this->files->get($path), $stub) === false)
        {
            $this->files->append($path, $this->compileRouteStub());
            return $this->devMakeCommandObj->info('+ ' . $path . ' (Updated)');
        }

        return $this->devMakeCommandObj->comment("x $path" . ' (Skipped)');
    }

    /**
     * Compile the migration stub.
     *
     * @return string
     */
    protected function compileRouteStub()
    {
        $stub = $this->files->get(substr(__DIR__, 0, -5) . 'Stubs/route.stub');

        $this->buildStub($this->devMakeCommandObj->getMeta(), $stub);

        return $stub;
    }
}
