<?php

namespace Cheppers\LaravelApiGenerator\Generators;

class TransformerGenerator extends GeneratorAbstract
{

    protected function getStubFileName()
    {
        return 'Transformer.php.txt';
    }

    protected function getDestinationFileName()
    {
        return $this->modelName . 'Transformer.php';
    }

    protected function extendReplaceData()
    {
        $code = '';
        foreach ($this->fields as $fieldData) {
            $code .= $this->indentString("'" . $fieldData['name'] . "' => \$" . camel_case($this->modelName) . '->' . $fieldData['name'] . ',', 3);
        }
        $this->stringsToReplace['%%code%%'] = rtrim($code);
    }
}
