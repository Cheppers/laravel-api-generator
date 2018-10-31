<?php

namespace Cheppers\LaravelApiGenerator\Generators;

class MigrationGenerator extends GeneratorAbstract
{

    protected function getStubFileName()
    {
        return 'migration.php.txt';
    }

    protected function getDestinationFileName()
    {
        return date('Y_m_d_His') . '_create_' . snake_case(str_plural($this->modelName)) . '_table.php';
    }

    protected function extendReplaceData()
    {
        $this->stringsToReplace['%%machine_name_studly_plural%%'] = studly_case(str_plural($this->modelName));
        $this->stringsToReplace['%%machine_name_snake_plural%%'] = snake_case(str_plural($this->modelName));
        $code = '';
        if ($this->timestamps) {
            $code .= $this->indentString("\$table->timestamps();", 3);
        }
        foreach ($this->fields as $fieldData) {
            $code .= $this->indentString("\$table->" . $fieldData['type'] . "('" . $fieldData['name'] . "');", 3);
        }
        $this->stringsToReplace['%%code%%'] = rtrim($code);
    }
}
