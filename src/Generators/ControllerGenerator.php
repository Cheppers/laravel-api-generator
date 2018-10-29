<?php

namespace Cheppers\LaravelApiGenerator\Generators;

class ControllerGenerator extends GeneratorAbstract
{
    protected function getStubFileName()
    {
        return 'Controller.php.txt';
    }

    protected function getDestinationFileName()
    {
        return $this->modelName . 'Controller.php';
    }

    protected function extendReplaceData()
    {
        $code = $this->indentString("protected function getFilterInfo(Request \$request)", 1);
        $code .= $this->indentString('{', 1);
        $code .= $this->indentString("\$filters = [", 2);
        foreach ($this->fields as $fieldData) {
            if ($fieldData['type'] == 'boolean') {
                continue;
            }
            $code .= $this->indentString("'" . $fieldData['name'] . "' => \$request->get('" . $fieldData['name'] . "'),", 3);
        }
        $code .= $this->indentString('];', 2);
        foreach ($this->fields as $fieldData) {
            switch ($fieldData['type']) {
                case 'boolean':
                    $code .= $this->indentString("if (\$request->exists('" . $fieldData['name'] . "')) {", 2);
                    $code .= $this->indentString("\$filters['" . $fieldData['name'] . "'] = \$request->get('" . $fieldData['name'] . "') ? 1 : -1;", 3);
                    $code .= $this->indentString('}', 2);
                    break;
            }
        }
        $code .= $this->indentString("return \$filters;", 2);
        $code .= $this->indentString('}', 1);
        $this->stringsToReplace['%%code%%'] = rtrim($code);
    }
}
