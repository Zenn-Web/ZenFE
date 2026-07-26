<?php

namespace App\Http\Controllers;

class LanguageController extends Controller
{
    /**
     * Switch the application locale and store it in session.
     */
    public function switch(string $locale)
    {
        if (in_array($locale, ['id', 'en'])) {
            session(['locale' => $locale]);
        }

        return response()->noContent();
    }
}
