<?php

namespace Konzole;

use Konzole\InputOutput\InputOutput;

class Konzole
{
    /**
     * @var array list of registered commands
     */
    private array $commands = [];

    private InputOutput $inputOutput;

    public function __construct()
    {
        $this->inputOutput = new InputOutput();
    }
}