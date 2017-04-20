<?php

namespace common\helpers;


class ConfigHelper
{
    public function loadConfig(string $filename)
    {
        if (!is_file($filename)) {
            echo PHP_EOL . PHP_EOL . 'Config Error: No Config for ' . YII_ENV . ' [' . $filename . ']' . PHP_EOL . PHP_EOL;
            exit(1);
        }

        $cfg = file_get_contents($filename);
        $cfg = json_decode($cfg, true);
        if (!count($cfg)) {
            echo PHP_EOL . PHP_EOL . 'Config Error: Invalid Config' . PHP_EOL . PHP_EOL;
            exit(1);
        }

        return $cfg;
    }
}
