<?php

namespace Commands;

use Konzole\Command;
use Konzole\InputOutput\Output;

class Example extends Command
{
    public function run(array $params = []): void
    {
        $commandName = $this->toSnakeCase(basename(self::class));
        $arguments = [];
        $options = [];

        foreach ($params as $name => $data) {
            if ($data === null) {
                $arguments[] = $name;
                continue;
            }

            $options[] = [
                'text'  => $name,
                'level' => 1
            ];

            if (is_array($data)) {
                foreach ($data as $value) {
                    $options[] = [
                        'text'  => $value,
                        'level' => 2
                    ];
                }
            } else {
                $options[] = [
                    'text'  => $data,
                    'level' => 2
                ];
            }
        }

        $this->outputMessage(PHP_EOL . 'Called command: ' . $commandName);

        $this->outputMessage(PHP_EOL . 'Arguments: ');

        foreach ($arguments as $argument) {
            $this->outputMessage($argument, 1);
        }

        $this->outputMessage(PHP_EOL . 'Options: ');

        foreach ($options as $option) {
            $this->outputMessage($option['text'], $option['level']);
        }
    }

    public function help(): void
    {
        Output::output('This command shows structure of the passed params');
    }

    private function toSnakeCase(string $text): string
    {
        $text = preg_replace('/(?<!^)([A-Z])/', '_$1', $text);

        return strtolower($text);
    }

    private function outputMessage(string $text, int $level = 0): void
    {
        if ($level) {
            $text = str_repeat('  ', $level) . '- ' . $text;
        }

        Output::output($text);
    }
}