<?php

namespace Cheppers\LaravelApiGenerator\Generators;

use Cheppers\LaravelApiGenerator\Generators\Config\ConfigStore;
use Faker\Generator;

abstract class GeneratorAbstract
{
    const TAB_SIZE = 4;

    protected $stubDirectory;

    protected $stubPath;

    protected $content;

    protected $modelName;

    protected $stringsToReplace;

    protected $destinationDirectory;

    protected $destinationPath;

    protected $fields;

    protected $timestamps;

    /**
     * @var Generator
     */
    protected $faker;

    abstract protected function getStubFileName();

    abstract protected function getDestinationFileName();

    abstract protected function extendReplaceData();

    public function __construct(ConfigStore $config, $stubDirectory, $destinationDirectory, Generator $faker)
    {
        $this->modelName = $config->modelName;
        $this->fields = $config->fields;
        $this->timestamps = $config->timestamps;
        $this->stubDirectory = $stubDirectory;
        $this->destinationDirectory = $destinationDirectory;
        $this->stubPath = $this->stubDirectory . '/' . $this->getStubFileName();
        $this->destinationPath = $this->destinationDirectory . '/' . $this->getDestinationFileName();
        $this->faker = $faker;
    }

    public function make()
    {
        $this->loadStub();
        $this->prepareReplaceData();
        $this->prepareContent();
        $this->saveFile();
        return $this->destinationPath;
    }

    protected function loadStub()
    {
        $this->content = file_get_contents($this->stubPath);
    }

    protected function prepareContent()
    {
        $this->content = str_replace(
            array_keys($this->stringsToReplace),
            array_values($this->stringsToReplace),
            $this->content
        );
    }

    protected function prepareReplaceData()
    {
        $this->stringsToReplace = [
            '%%model%%' => $this->modelName,
            '%%machine_name_snake%%' => snake_case($this->modelName),
            '%%machine_name_camel%%' => camel_case($this->modelName),
        ];
        $this->extendReplaceData();
    }

    protected function saveFile()
    {
        if (!is_dir($this->destinationDirectory)) {
            mkdir($this->destinationDirectory, 0777, true);
        }
        file_put_contents($this->destinationPath, $this->content);
    }

    protected function indentString($string, $indentSize)
    {
        return str_repeat(" ", static::TAB_SIZE * $indentSize) . $string . "\n";
    }
}
