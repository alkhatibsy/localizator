<?php

namespace Amirami\Localizator\Contracts;

use Illuminate\Support\Collection;

interface Collectable
{
    public function getTranslated(string $locale): Collection;
}
