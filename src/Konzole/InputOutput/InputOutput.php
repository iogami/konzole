<?php

namespace Konzole\InputOutput;

class InputOutput
{
    private const PARAM_SYNTAXES = [
        '/^\{([\w+-],?)+[\w+-]\}$/',
        '/^\[\w+=[^,]\{?([\w+.-],?)*[\w+.-]\}?\]$/'
    ];

    /**
     * @var string executed command
     */
    private string $command;

    /**
     * @var array parsed params
     */
    private array $params = [];

    public function __construct()
    {
        global $argv;

        $this->parseParams(array_slice($argv, 1));
    }

    private function parseParams(array $params): void
    {
        try {
            $this->command = array_shift($params);
        } catch (\TypeError $e) {
            $this->outputError('You must pass the command as the first parameter.');
            die();
        }

        foreach ($params as $param) {
            if (!$this->isParamValid($param)) {
                $this->outputError("Parameter $param has wrong syntax.");
                die();
            }

            $param = str_replace(['[', ']', '{', '}'], '', $param);
            list($keys, $values) = $this->getKeysValues($param);

            foreach ($keys as $key) {
                $this->params[$key] = $values;
            }
        }

        $this->output($this->command);
        $this->output($this->params);
    }

    private function getKeysValues(string $param): array
    {
        $key = null;

        if (strstr($param, '=') !== false) {
            list($key, $param) = explode('=', $param);
        }

        if (strstr($param, ',') !== false) {
            $param = explode(',', $param);
        }

        if (is_null($key)) {
            $key = $param;
            $param = null;
        }

        $key = !is_array($key) ? [$key] : $key;

        return [$key, $param];
    }

    private function isParamValid(string $param): bool
    {
        $valid = false;

        foreach (self::PARAM_SYNTAXES as $regex) {
            $valid |= preg_match($regex, $param);
        }

        return $valid;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function output($output, int $level = 0): void
    {
        if (is_array($output)) {
            foreach ($output as $item) {
                $this->output($item, $level + 1);
            }
        } else {
            if ($level > 1) {
                $output = str_repeat('  ', $level) . '- ' . $output;
            }

            echo $output . PHP_EOL;
        }
    }

    public function outputError(string $text): void
    {
        $this->output("\033[91m" . $text . "\033[0m");
    }
}