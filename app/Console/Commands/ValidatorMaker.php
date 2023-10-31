<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use InvalidArgumentException;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Input\InputOption;

class ValidatorMaker extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:validator-maker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create validator class in folder /App/Validators';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Validator';

    protected function getNameInput()
    {
        return str_replace('.', '/', trim($this->argument('name')));
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        $model = $this->option('m');

        return $model ? $this->replaceModel($stub, $model) : $stub;
    }

    /**
     * Replace the model for the given stub.
     *
     * @param  string  $stub
     * @param  string  $model
     * @return string
     */
    protected function replaceModel($stub, $model)
    {
        $modelClass = $this->parseModel($model);

        $replace = [
            '{{ namespacedModel }}' => $modelClass,
            '{{ m }}' => class_basename($modelClass),
            '{{ modelVariable }}' => lcfirst(class_basename($modelClass)),
        ];

        return str_replace(
            array_keys($replace),
            array_values($replace),
            $stub
        );
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param  string  $model
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        return $this->qualifyModel($model);
    }

    protected function modelExistInDatabase($model)
    {

        $allTables = Schema::getAllTables();

        return in_array($model, $allTables);
    }

    protected function fileModelExist($model)
    {
        //MODEL EXISTS IN APP/MODELS
        $model = app_path() . '/Models/' . $model . '.php';
        return file_exists($model);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return  app_path() . '/Console/Stubs/ValidatorClass.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Validators';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Model'],
        ];
    }
}
