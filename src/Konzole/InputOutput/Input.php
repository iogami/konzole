<?php

namespace Konzole\InputOutput;

use Konzole\InputOutput\Output;

class Input
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
            Output::output('You must pass the command as the first parameter.', Output::COLOR_ERROR, true);
        }

        foreach ($params as $param) {
            if (!$this->isParamValid($param)) {
                Output::output("Parameter $param has wrong syntax.", Output::COLOR_ERROR, true);
            }

            $param = str_replace(['[', ']', '{', '}'], '', $param);
            list($keys, $values) = $this->getKeysValues($param);

            foreach ($keys as $key) {
                $this->params[$key] = $values;
            }
        }
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

    /**
     * Check if param was passed
     * @param string $paramName parameter being searched for
     * @return bool
     */
    public function hasParam(string $paramName): bool
    {
        return array_key_exists($paramName, $this->params);
    }
}