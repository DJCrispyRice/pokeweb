<?php

declare(strict_types=1);

namespace App\Util\Import;

use App\Util\String\StringUtil;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

final class ImportUtil
{
    public const CSV_DELIMITER = ';';

    /**
     * Supprime les espaces en trop et remplace les champs vides par null dans toutes les colonnes.
     */
    public static function trimAndNullifyIfEmpty(array &$data): void
    {
        foreach ($data as $index => $value) {
            $data[$index] = StringUtil::isEmptyString($value) ? null : trim($value);
        }
    }

    /**
     * Récupère le nombre ligne du fichier sans charger tout son contenu en mémoire.
     *
     * @param string    $filepath      Chemin complet vers le fichier
     * @param null|bool $countFirstRow Compter la première ligne ?
     */
    public static function countRow(string $filepath, ?bool $countFirstRow = false): int
    {
        $file = new \SplFileObject($filepath, 'r');
        $file->seek(PHP_INT_MAX);
        $offset = $file->key();
        $file->seek($offset);

        if ($countFirstRow === false) {
            --$offset;
        }

        if ($file->fgets() === '') {
            return $offset;
        }

        return $offset + 1;
    }

    public static function readFile(
        string $filepath,
        ?\Closure $headerClosure = null,
        ?\Closure $rowClosure = null
    ): \Generator {
        $handle = fopen($filepath, 'r');

        // Skip BOM if present
        if (fgets($handle, 4) !== "\xef\xbb\xbf") {
            // Or rewind pointer to start of file
            rewind($handle);
        }

        $header = fgetcsv($handle, 1000, self::CSV_DELIMITER);

        if ($headerClosure !== null) {
            $headerClosure($header);
        }

        while (($row = fgetcsv($handle, 20000, self::CSV_DELIMITER)) !== false) {
            try {
                if ($rowClosure !== null) {
                    $rowClosure($row);
                }

                yield array_combine($header, $row);
            } catch (\ValueError) {
                // Wrong number of column or blank line or truncated data from fgetcsv()
                continue;
            }
        }

        fclose($handle);
    }

    public static function formatHeader(array &$header): void
    {
        foreach ($header as &$head) {
            $head = mb_strtolower(
                preg_replace(
                    '~_+~',
                    '_',
                    str_replace(
                        ['-', ' '],
                        '_',
                        trim(StringUtil::removeAccent($head))
                    )
                )
            );
        }
    }

    public static function translateEmptyColumnException(InvalidOptionsException $exception): string
    {
        if (
            preg_match(
                '~^the option "(?<column>\w+)" with value null is expected.+$~i',
                $exception->getMessage(),
                $matches
            ) === 1
        ) {
            return "La colonne {$matches['column']} est obligatoire";
        }

        return $exception->getMessage();
    }
}
