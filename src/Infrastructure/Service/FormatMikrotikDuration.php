<?php
declare(strict_types=1);

namespace App\Infrastructure\Service;

final class FormatMikrotikDuration
{
    public static function fromStringToInt(string $duration): int
    {
        $text = \str_replace(
            ['s', 'm', 'h', 'd'],
            '|',
            $duration
        );

        $timeCode = \explode('|', $text);
        $timeCode = \array_reverse($timeCode);

        $timeCodeClear = [];
        foreach ($timeCode as $number) {
            if ('' === $number) {
                continue;
            }

            $timeCodeClear[] = $number;
        }

        $multiples = [
            0 => 1,            // Seconds
            1 => 60,           // Minutes
            2 => 60 * 60,      // Hours
            3 => 24 * 60 * 60  // Days
        ];

        $seconds = 0;
        foreach ($timeCodeClear as $key => $value) {
            $multiple = $multiples[$key];
            $seconds += $multiple * $value;
        }

        return $seconds;
    }
}
