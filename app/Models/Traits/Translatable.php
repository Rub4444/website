<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\App;

trait Translatable
{
    protected $defaultLocale = 'arm';

    public function __($field)
    {
        $locale = App::getLocale() ?: $this->defaultLocale;

        // если не en — всегда базовое поле
        if ($locale !== 'en') {
            return $this->$field ?? null;
        }

        $translatedField = $field . '_en';

        // если есть name_en и он не пустой
        if (
            array_key_exists($translatedField, $this->attributes) &&
            !empty($this->$translatedField)
        ) {
            return $this->$translatedField;
        }

        // fallback на name
        return $this->$field ?? null;
    }
}
