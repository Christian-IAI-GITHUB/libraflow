<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    // Méthode statique pour lire un paramètre facilement
    // Exemple : Setting::getValue('duree_emprunt') → "14"
    public static function getValue(string $key, $default = null): ?string
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    // Méthode statique pour modifier un paramètre
    // Exemple : Setting::setValue('duree_emprunt', '21')
    public static function setValue(string $key, string $value): void
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
