<?php

namespace Amirami\Localizator\Tests\Concerns;

trait ImportsLangFiles
{
    protected function getLangFilePath(string $fileName): string
    {
        return lang_path($fileName);
    }

    /**
     * @noinspection PhpIncludeInspection
     */
    protected function getDefaultLangContents(string $locale, string $fileName): array
    {
        return require $this->getLangFilePath($locale.DIRECTORY_SEPARATOR."{$fileName}.php");
    }

    protected function getJsonLangContents(string $locale): array
    {
        return json_decode(
            file_get_contents($this->getLangFilePath("{$locale}.json")),
            true
        );
    }
}
