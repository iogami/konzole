<?php

namespace Konzole;

use Konzole\InputOutput\Output;
use \RegexIterator;

class Router
{
    /**
     * @var array list of registered commands
     */
    private array $commands = [];

    /**
     * @var string current executed command
     */
    private string $currentCommand;

    /**
     * @param string $commandsDirPath path to the commands directory
     */
    public function __construct(string $commandsDirPath = '')
    {
        try {
            $filesIterator = new \RecursiveDirectoryIterator($commandsDirPath);
            $scriptsIterator = new \RegexIterator($filesIterator, '/^.*[\\\\\/](\w+).php$/i', \RegexIterator::GET_MATCH);
        } catch (\Exception $e) {
            Output::output($e->getMessage(), Output::COLOR_ERROR, true);
        }

        foreach ($scriptsIterator as $fileName) {
            $this->commands[$this->toSnakeCase($fileName[1])] = [
                'class' => $fileName[1],
                'file'  => $fileName[0]
            ];
        }
    }

    /**
     * Check if current command exists
     * @return bool
     */
    public function commandExists(): bool
    {
        return array_key_exists($this->currentCommand, $this->commands);
    }

    /**
     * Set the current command
     * @param string $commandName name of executed command
     * @return void
     */
    public function setCommand(string $commandName): void
    {
        $this->currentCommand = $commandName;
    }

    /**
     * Return the full path (and filename) that stores the code of the invoked command
     * @return string
     */
    public function getFile(): string
    {
        return  $this->commands[$this->currentCommand]['file'];
    }

    /**
     * Return the classname (and namespace) of class that stores the invoked command
     * @return string
     */
    public function getClassName(): string
    {
        return  $this->commands[$this->currentCommand]['class'];
    }

    /**
     * converts string from CamelCase to snake_case
     * @param string $path
     * @return string
     */
    private function toSnakeCase(string $text): string
    {
        $text = preg_replace('/(?<!^)([A-Z])/', '_$1', $text);

        return strtolower($text);
    }
}