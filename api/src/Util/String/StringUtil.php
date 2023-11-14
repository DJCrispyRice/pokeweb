<?php

declare(strict_types=1);

namespace App\Util\String;

use App\Exception\UnexpectedValueException;

final class StringUtil
{
    /**
     * Generates a random string of specified length.
     * The string generated matches [A-Za-z0-9_-]+ and is transparent to URL-encoding.
     */
    public static function generateRandomString(int $length = 10): string
    {
        return mb_substr(strtr(base64_encode(random_bytes($length)), '+/', '-_'), 0, $length);
    }

    public static function removeFirstPart(string $haystack, string $firstPart): string
    {
        return str_starts_with($haystack, $firstPart) ? mb_substr($haystack, mb_strlen($firstPart)) : $haystack;
    }

    public static function replaceSingle(string $search, string $replace, string $subject): string
    {
        $return = str_replace($search, $replace, $subject);
        if (\is_string($return) === false) {
            throw new UnexpectedValueException($return);
        }

        return $return;
    }

    public static function removeLastPart(string $haystack, string $lastPart): string
    {
        return str_ends_with($haystack, $lastPart)
            ? mb_substr($haystack, 0, mb_strlen($haystack) - mb_strlen($lastPart))
            : $haystack;
    }
}
