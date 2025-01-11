<?php

namespace Amirami\Localizator\Contracts;

interface Writable
{
    public function put(string $locale, Translatable $keys): void;
}
