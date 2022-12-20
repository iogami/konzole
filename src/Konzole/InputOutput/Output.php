<?php

namespace Konzole\InputOutput;

class Output
{
    /**
     * @var int color constant for error text (red)
     */
    public const COLOR_ERROR = 91;

    /**
     * @var int color constant for warning text (yellow)
     */
    public const COLOR_WARNING = 93;

    /**
     * Output the message
     * @param string $output text message
     * @param int $color text color
     * @param bool $die stop executing script after showing error
     * @return void
     */
    public static function output(string $output, int $color = 0, bool $die = false): void
    {
        if ($color) {
            $output = "\033[" . $color . "m" . $output . "\033[0m";
        }

        echo $output . PHP_EOL;

        if ($die) {
            die();
        }
    }
}