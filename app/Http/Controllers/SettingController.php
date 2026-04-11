<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // Page des paramètres
    public function index()
    {
        $settings = [
            'duree_emprunt'         => Setting::getValue('duree_emprunt', '14'),
            'penalite_journaliere'  => Setting::getValue('penalite_journaliere', '100'),
        ];

        return view('settings.index', compact('settings'));
    }

    // Mettre à jour un paramètre
    public function update(Request $request, string $key)
    {
        $request->validate([
            'value' => 'required|numeric|min:1',
        ]);

        Setting::setValue($key, $request->value);

        return back()->with('success', 'Paramètre mis à jour.');
    }
}
