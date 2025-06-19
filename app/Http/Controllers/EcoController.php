<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\EcoContributionService;

class EcoController extends Controller
{
    /**
     * Возвращает эко-рейтинг текущего пользователя.
     */
    public function rating(Request $request, EcoContributionService $ecoService)
    {
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }
        $rating = $ecoService->calculateEcoRating($user);
        // Для Blade-шаблона можно вернуть view, для API — JSON
        if ($request->wantsJson()) {
            return response()->json(['eco_rating' => $rating]);
        }
        return view('account.profile', ['eco_rating' => $rating]);
    }
}