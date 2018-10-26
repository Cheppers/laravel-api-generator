<?php

namespace Cheppers\LaravelApiGenerator\Console;

use Illuminate\Console\Command;

class MakeApiResourceCommand extends Command
{
    protected $signature = 'make:apiresource {modelName}';

    protected $description = 'Create a new api resource pack';

    protected $modelDirectory = 'Models';

    private $stringsToReplace = [];

    public function handle()
    {
        $stubDirectory = __DIR__ . '/../../stubs';
        $modelName = $this->argument('modelName');
        $this->line('Generating model: ' . $modelName);
        $this->call('make:model', ['name' => $this->modelDirectory . '/' . $modelName, '-m' => true, '-f' => true]);
        $this->stringsToReplace = [
            '%%model%%' => $modelName,
            '%%machine_name_snake%%' => snake_case($modelName),
            '%%machine_name_camel%%' => camel_case($modelName),
        ];
        $filesToMake = [
            'Repository.php.txt' =>
              app_path() . '/Repositories/' . $modelName . 'Repository.php',
            'Transformer.php.txt' =>
              app_path() . '/Transformers/Api/' . $modelName . 'Transformer.php',
            'RequestBase.php.txt' =>
              app_path() . '/Http/Requests/Api/' . $modelName . '/' . $modelName . 'RequestBase.php',
            'PostRequest.php.txt' =>
              app_path() . '/Http/Requests/Api/' . $modelName . '/' . $modelName . 'PostRequest.php',
            'PutRequest.php.txt' =>
              app_path() . '/Http/Requests/Api/' . $modelName . '/' . $modelName . 'PutRequest.php',
            'Controller.php.txt' =>
              app_path() . '/Http/Controllers/Api/' . $modelName . 'Controller.php',
            'Test.php.txt' =>
              app_path() . '/../tests/Feature/Api/' . $modelName . '/' . $modelName . 'Test.php',
        ];
        foreach ($filesToMake as $source => $destination) {
            $this->copyFromStub($stubDirectory . '/' . $source, $destination);
        }
        $this->addResourceRoute($modelName);
    }

    /**
     * @param $stubFile
     * @param $destinationFile
     */
    private function copyFromStub($stubFile, $destinationFile)
    {
        if (!is_dir(dirname($destinationFile))) {
            mkdir(dirname($destinationFile), 0777, true);
        }
        $content = file_get_contents($stubFile);
        $content = str_replace(
            array_keys($this->stringsToReplace),
            array_values($this->stringsToReplace),
            $content
        );
        file_put_contents($destinationFile, $content);
    }

    private function addResourceRoute($modelName)
    {
        $file = fopen(app_path() . '/../routes/api.php', 'a+');
        fwrite($file, "\nRoute::apiresource('" . snake_case($modelName) . "', 'Api\\" . $modelName . "Controller');\n");
        fclose($file);
    }
}
