<?php

/**
 * Created by PhpStorm.
 * User: fernandobritofl
 * Date: 4/22/15
 * Time: 10:34 PM
 */

namespace Yangliuan\LaravelDevinit\Makes;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Yangliuan\LaravelDevinit\Console\DevMakeCommand;
use Yangliuan\LaravelDevinit\Migrations\SchemaParser;
use Yangliuan\LaravelDevinit\Migrations\SyntaxBuilder;

class MakeMigration
{
    use MakerTrait;

    /**
     * Store scaffold command.
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
    public function __construct(DevMakeCommand $devMakeCommandObj, Filesystem $files)
    {
        $this->files = $files;
        $this->devMakeCommandObj = $devMakeCommandObj;

        $this->start();
    }

    /**
     * Start make migration.
     *
     * @return void
     */
    protected function start()
    {
        $name = 'create_' . strtolower($this->devMakeCommandObj->argument('name')) . '_table';

        $path = $this->getPath($name);

        if (!$this->classExists($name))
        {
            $this->makeDirectory($path);
            $this->files->put($path, $this->compileMigrationStub());
            return $this->devMakeCommandObj->info('+ ' . $path);
        }

        return $this->devMakeCommandObj->comment('x ' . $path);
    }

    /**
     * Get the path to where we should store the migration.
     *
     * @param  string $name
     * @return string
     */
    protected function getPath($name)
    {
        return './database/migrations/' . date('Y_m_d_His') . '_' . $name . '.php';
    }

    /**
     * Compile the migration stub.
     *
     * @return string
     */
    protected function compileMigrationStub()
    {
        $stub = $this->files->get(substr(__DIR__, 0, -5) . 'Stubs/migration.stub');

        $this->replaceSchema($stub);

        $this->buildStub($this->devMakeCommandObj->getMeta(), $stub);

        return $stub;
    }

    /**
     * Replace the schema for the stub.
     *
     * @param  string $stub
     * @param string $type
     * @return $this
     */
    protected function replaceSchema(&$stub)
    {
        if ($schema = $this->devMakeCommandObj->getMeta()['schema'])
        {
            $schema = (new SchemaParser)->parse($schema);
        }

        $schema = (new SyntaxBuilder)->create($schema, $this->devMakeCommandObj->getMeta());
        $stub = str_replace(['{{schema_up}}', '{{schema_down}}'], $schema, $stub);

        return $this;
    }

    public function classExists($name)
    {
        $files = $this->files->allFiles('./database/migrations/');
        foreach ($files as $file)
        {
            if (strpos($file->getFilename(), $name) !== false)
            {
                return true;
            }
        }

        return false;
    }
}
