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

class MakeSeed
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
     * Start make seed.
     *
     * @return void
     */
    protected function start()
    {
        $this->generateFactory();
        $this->generateSeed();
        $this->updateDatabaseSeeder();
    }

    protected function generateFactory()
    {
        $name = $this->devMakeCommandObj->getObjName('Name');
        $path = $this->getPath($name, 'factory');

        if (!$this->files->exists($path))
        {
            $this->makeDirectory($path);
            $this->files->put($path, $this->compileStub('factory'));

            return $this->devMakeCommandObj->info("+ $path");
        }

        return $this->devMakeCommandObj->comment("x $path");
    }

    protected function generateSeed()
    {
        $path = $this->getPath($this->devMakeCommandObj->getObjName('Names') . 'TableSeeder', 'seed');

        if ($this->files->exists($path))
        {
            return $this->devMakeCommandObj->comment('x ' . $path);
        }

        $this->makeDirectory($path);
        $this->files->put($path, $this->compileStub('seed'));
        $this->devMakeCommandObj->info('+ ' . $path);
    }

    protected function updateDatabaseSeeder()
    {
        $path = './database/seeders/DatabaseSeeder.php';
        $content = $this->files->get($path);
        $name = $this->devMakeCommandObj->getObjName('Names') . 'TableSeeder';

        if (strpos($content, $name) === false)
        {

            $content = str_replace(
                '(UsersTableSeeder::class);',
                "(UsersTableSeeder::class);\n\t\t\$this->call($name::class);",
                $content
            );
            $this->files->put($path, $content);

            return $this->devMakeCommandObj->info('+ ' . $path . ' (Updated)');
        }

        return $this->devMakeCommandObj->comment("x " . $path . ' (Skipped)');
    }
}
