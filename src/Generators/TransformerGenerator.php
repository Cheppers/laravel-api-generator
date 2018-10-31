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
        if ($this->timestamps) {
            $code .= $this->indentString("'created_at' => \$" . camel_case($this->modelName) . "->created_at->toDateTimeString(),", 3);
            $code .= $this->indentString("'updated_at' => \$" . camel_case($this->modelName) . "->updated_at->toDateTimeString(),", 3);
        }
        foreach ($this->fields as $fieldData) {
            switch ($fieldData['type']) {
                case 'datetime':
                    $code .= $this->indentString("'" . $fieldData['name'] . "' => \$" . camel_case($this->modelName) . '->' . $fieldData['name'] . '->toDateTimeString(),', 3);
                    break;
                default:
                    $code .= $this->indentString("'" . $fieldData['name'] . "' => \$" . camel_case($this->modelName) . '->' . $fieldData['name'] . ',', 3);
                    break;
            }
        }
        $this->stringsToReplace['%%code%%'] = rtrim($code);
    }
}
