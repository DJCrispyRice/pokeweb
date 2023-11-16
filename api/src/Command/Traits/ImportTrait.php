<?php

declare(strict_types=1);

namespace App\Command\Traits;

use Symfony\Component\Finder\Finder;

trait ImportTrait
{
    private function openCsvFile(string $filename): ?\SplFileInfo
    {
        $finder = (new Finder())
            ->in($this->getParameterBag()->get('kernel.project_dir') . '/resources')
            ->name($filename)
            ->ignoreUnreadableDirs()
            ->sortByModifiedTime()
            ->reverseSorting()
            ->files();
        if ($finder->hasResults() === false) {
            return null;
        }

        $iterator = $finder->getIterator();
        $iterator->rewind();

        return $iterator->current();
    }
}
