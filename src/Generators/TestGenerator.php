<?php

namespace Cheppers\LaravelApiGenerator\Generators;

class TestGenerator extends GeneratorAbstract
{

    protected function getStubFileName()
    {
        return 'Test.php.txt';
    }

    protected function getDestinationFileName()
    {
        return $this->modelName . 'Test.php';
    }

    protected function extendReplaceData()
    {
    }
}
