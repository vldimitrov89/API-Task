<?php

namespace Api\app\Helpers;

//load database config file
final class Database
{
    private $data = array();

    private $filename = 'database';

    public function get($key)
    {
        return ($this->data[$key] ?? null);
    }

    public function load()
    {
        $file = __DIR__ . '/../../config/' . $this->filename . '.php';

        if (file_exists($file)) {
            $loaded_file = require($file);
            is_array($loaded_file) ? $this->data = array_merge($this->data, $loaded_file) : false;
        } else {
            trigger_error('Error: Could not load database settings!');
            exit();
        }
    }
}
