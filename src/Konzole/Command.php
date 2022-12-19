<?php

namespace Konzole;

use Konzole\InputOutput\Output;

abstract class Command
{
    /**
     * Runs the main code of command
     * @param array $params passed parameters
     * @return void
     */
    abstract public function run(array $params = []): void;

    /**
    /* Shows documentation in console for current command
     * @return void
     */
    abstract public function help(): void;
}