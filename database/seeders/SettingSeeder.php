<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::setValue('duree_emprunt', '14');       // 14 jours par défaut
        Setting::setValue('penalite_journaliere', '100'); // 100 FCFA par jour
    }
}