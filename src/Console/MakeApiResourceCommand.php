<?php

namespace Cheppers\LaravelApiGenerator\Console;

use Cheppers\LaravelApiGenerator\Generators\ControllerGenerator;
use Cheppers\LaravelApiGenerator\Generators\FactoryGenerator;
use Cheppers\LaravelApiGenerator\Generators\MigrationGenerator;
use Cheppers\LaravelApiGenerator\Generators\ModelGenerator;
use Cheppers\LaravelApiGenerator\Generators\PostRequestGenerator;
use Cheppers\LaravelApiGenerator\Generators\PutRequestGenerator;
use Cheppers\LaravelApiGenerator\Generators\RepositoryGenerator;
use Cheppers\LaravelApiGenerator\Generators\RequestBaseGenerator;
use Cheppers\LaravelApiGenerator\Generators\TestGenerator;
use Cheppers\LaravelApiGenerator\Generators\TransformerGenerator;
use Illuminate\Console\Command;

class MakeApiResourceCommand extends Command
{
    protected $signature = 'make:apiresource {modelName?}';

    protected $description = 'Create a new api resource pack';

    protected $modelDirectory = 'Models';

    private $fields = [];

    private $fieldTypes = [
        'string',
        'integer',
        'text',
        'datetime',
        'boolean',
    ];

    public function handle()
    {
        $stubDirectory = __DIR__ . '/../../stubs';
        $modelName = $this->argument('modelName');
        if (empty($modelName)) {
            $modelName = $this->ask("Give a model name");
            if (empty($modelName)) {
                return;
            }
        }
        do {
            $fieldName = $this->ask("Give a field name");
            if (!empty($fieldName)) {
                $fieldType = $this->anticipate("What type is it", $this->fieldTypes);
                $this->fields[] = [
                    'name' => $fieldName,
                    'type' => $fieldType,
                ];
            }
        } while (!empty($fieldName));
        foreach ($this->getGenerators($modelName) as $className => $destination) {
            $destinationPath = (new $className($modelName, $this->fields, $stubDirectory, $destination))->make();
            $this->line('Generated file: ' . $destinationPath);
        }
        $this->addResourceRoute($modelName);
    }

    private function addResourceRoute($modelName)
    {
        $file = fopen(app_path() . '/../routes/api.php', 'a+');
        fwrite($file, "Route::apiresource('" . snake_case($modelName) . "', 'Api\\" . $modelName . "Controller');\n");
        fclose($file);
        $this->line('Modified file: routes/api.php');
    }

    /**
     * @param $modelName
     * @return array
     */
    private function getGenerators($modelName): array
    {
        $generators = [
            ModelGenerator::class =>
                app_path() . '/Models/',
            MigrationGenerator::class =>
                app_path() . '/../database/migrations/',
            FactoryGenerator::class =>
                app_path() . '/../database/factories/',
            RepositoryGenerator::class =>
                app_path() . '/Repositories/',
            TransformerGenerator::class =>
                app_path() . '/Transformers/Api/',
            RequestBaseGenerator::class =>
                app_path() . '/Http/Requests/Api/' . $modelName . '/',
            PostRequestGenerator::class =>
                app_path() . '/Http/Requests/Api/' . $modelName . '/',
            PutRequestGenerator::class =>
                app_path() . '/Http/Requests/Api/' . $modelName . '/',
            ControllerGenerator::class =>
                app_path() . '/Http/Controllers/Api/',
            TestGenerator::class =>
                app_path() . '/../tests/Feature/Api/' . $modelName . '/',
        ];
        return $generators;
    }
}
