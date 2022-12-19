<?php

namespace Konzole;

use Konzole\InputOutput\Input;
use Konzole\InputOutput\Output;

class Konzole
{
    private Input $input;

    private Router $router;

    /**
     * @param string $commandsDirPath path to the commands directory
     */
    public function __construct(string $commandsDirPath = '')
    {
        if (empty($commandsDirPath)) {
            $commandsDirPath = dirname($this->getCallingFilePath()) . '/Commands';
        } elseif (defined('COMMANDS_DIR')) {
            $commandsDirPath = COMMANDS_DIR;
        }

        $this->input = new Input();
        $this->router = new Router($commandsDirPath);
    }

    /**
     * Gets path script which called this class
     * @return string|null
     */
    private function getCallingFilePath(): ?string
    {
        $trace = debug_backtrace();
        $file = null;

        for ($i = 1; $i < count($trace); $i++) {
            if (isset($trace[$i]) && isset($trace[$i]['file'])) {
                if ($file !== $trace[$i]['file']) {
                    $file = $trace[$i]['file'];
                }
            }
        }

        return $file;
    }

    /**
     * Execute command
     * @return void
     */
    public function execute(): void
    {
        $this->router->setCommand($this->input->getCommand());

        if (!$this->router->commandExists()) {
            Output::output('The command you specified does not exists!', Output::COLOR_ERROR, true);
        }

        require_once $this->router->getFile();

        $class = '\Commands\\' . $this->router->getClassName();
        $command = new $class();

        try {
            if ($this->input->hasParam('help')) {
                $command->help();
            } else {
                $command->run($this->input->getParams());
            }
        } catch (\Exception $e) {
            Output::output($e->getMessage(), Output::COLOR_ERROR);
        }
    }
}