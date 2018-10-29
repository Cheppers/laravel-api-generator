<?php

namespace Cheppers\LaravelApiGenerator\Generators;

class ModelGenerator extends GeneratorAbstract
{
    protected function getStubFileName()
    {
        return 'Model.php.txt';
    }

    protected function getDestinationFileName()
    {
        return $this->modelName . '.php';
    }

    protected function extendReplaceData()
    {
        $code = $this->indentString('protected $fillable = [', 1);

        foreach ($this->fields as $fieldData) {
            $code .= $this->indentString("'" . $fieldData['name'] . "',", 2);
        }
        $code .= $this->indentString('];', 1);
        $this->stringsToReplace['%%code%%'] = rtrim($code);
    }
}
