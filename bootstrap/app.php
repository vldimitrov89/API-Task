<?php

namespace Api\bootstrap;

final class App
{
    private $filename = 'libs';
    private $excluded_files = [
        "Database.php"
    ];

    public function load()
    {
        $config = __DIR__ . '/../config/' . $this->filename . '.php';

        $libs = [];

        if (file_exists($config)) {
            $loaded_file = require($config);
            is_array($loaded_file) ? $libs = array_merge($libs, $loaded_file) : false;
        } else {
            trigger_error('Error: Could not load libs!');
            exit();
        }

        foreach ($libs as $lib) {
            $path = ROOT_PATH . DIRECTORY_SEPARATOR . $lib;

            if ($fh = opendir($path)) {
                while (($entry = readdir($fh)) !== false) {
                    if ($entry !== "." && $entry !== ".." && !in_array($entry, $this->excluded_files)) {
                        require_once $path . DIRECTORY_SEPARATOR . $entry;
                    }
                }
            }
        }
    }
}
