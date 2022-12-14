<?php

namespace Konzole\InputOutput;

class InputOutput
{
    public function __construct()
    {
        global $argv;

        //parse_str(implode('&', array_slice($argv, 1)), $params);
        var_dump($argv);
    }
}