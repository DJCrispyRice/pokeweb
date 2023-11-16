<?php

declare(strict_types=1);

namespace App\Util\String;

use App\Exception\InvalidEncodingException;
use App\Exception\UnexpectedValueException;

final class StringUtil
{
    private static \Transliterator $transliterator;

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

    public static function removeAccent(string $string): string
    {
        if (isset(self::$transliterator) === false) {
            self::$transliterator = \Transliterator::createFromRules(
                ':: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;',
                \Transliterator::FORWARD
            );
        }

        return trim(self::$transliterator->transliterate(self::convertToUtf8($string)));
    }

    public static function convertToUtf8(string $string): string
    {
        try {
            return mb_convert_encoding(
                $string,
                to_encoding: 'UTF-8',
                from_encoding: mb_detect_encoding($string, encodings: ['ASCII', 'ISO-8859-1', 'UTF-8'], strict: true)
            );
        } catch (\TypeError $e) {
            throw new InvalidEncodingException($e);
        }
    }

    public static function isEmptyString(mixed $string): bool
    {
        return \is_string($string) === false || trim($string) === '';
    }
}
