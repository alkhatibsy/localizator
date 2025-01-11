<?php

namespace Amirami\Localizator\Services\Collectors;

use Amirami\Localizator\Collections\DefaultKeyCollection;
use Amirami\Localizator\Contracts\Collectable;
use Composer\InstalledVersions;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class DefaultKeyCollector implements Collectable
{
    public function getTranslated(string $locale): Collection
    {
        $translated = new DefaultKeyCollection;

        $this->getFiles($locale)
            ->each(function (SplFileInfo $fileInfo) use ($locale, $translated) {
                $translated->put(
                    preg_replace('/\.\w+$/', '', $fileInfo->getRelativePathname()),
                    $this->requireFile($locale, $fileInfo)
                );
            });

        return $translated;
    }

    protected function getFiles(string $locale): Collection
    {
        $dir = lang_path($locale);

        if (! file_exists($dir)) {
            if (config('localizator.publish')) {
                if (InstalledVersions::isInstalled('laravel-lang/common') && config('localizator.publish_common')) {
                    Artisan::call("lang:add $locale");
                } elseif ($locale === 'en') {
                    Artisan::call('lang:publish');
                } else {
                    File::ensureDirectoryExists($dir);

                    return new Collection;
                }
            } else {
                File::ensureDirectoryExists($dir);

                return new Collection;
            }

        }

        return new Collection(
            (new Finder)->in($dir)->name('*.php')->files()
        );
    }

    /**
     * @noinspection PhpIncludeInspection
     */
    protected function requireFile(string $locale, SplFileInfo $fileInfo): array
    {
        return require lang_path(
            $locale.DIRECTORY_SEPARATOR.$fileInfo->getRelativePathname()
        );
    }
}
