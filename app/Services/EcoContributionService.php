<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Carbon;

use App\Models\User;

class EcoContributionService
{
    /**
     * Рассчитывает эко-рейтинг пользователя.
     *
     * @param  \App\Models\User|int  $user
     * @param  int  $pointsPerEcoProduct
     * @return int
     */
    public function calculateEcoRating($user, int $pointsPerEcoProduct = 10): int
    {
        $userId = $user instanceof User ? $user->id : $user;
        $orders = \App\Models\Order::where('user_id', $userId)
            ->with(['orderItems.product.ecoFeatures'])
            ->get();

        $score = 0;

        foreach ($orders as $order) {
            foreach ($order->orderItems as $item) {
                $product = $item->product;
                if (!$product) continue;
                if ($product->ecoFeatures && $product->ecoFeatures->count() > 0) {
                    $score += $pointsPerEcoProduct * $item->quantity;
                }
            }
        }

        return min($score, 1000);
    }
    /**
     * Возвращает структуру экологического вклада пользователя по user_id.
     */
    public function getUserEcoContribution(int $userId): array
    {
        $orders = Order::where('user_id', $userId)
            ->with(['orderItems.product.ecoFeatures'])
            ->orderBy('created_at')
            ->get();

        $totalScore = 0;
        $ecoFeatureDetails = [];
        $ecoPurchases = [];
        $monthlyStats = [];

        foreach ($orders as $order) {
            foreach ($order->orderItems as $item) {
                $product = $item->product;
                if (!$product) continue;
                $weight = $product->weight ?? 1;

                $ecoFeatures = $product->ecoFeatures;
                $ecoFeatureList = [];

                foreach ($ecoFeatures as $feature) {
                    $coef = $feature->pivot->value ?? 1;
                    $score = $item->quantity * $weight * $coef;

                    // Детализация по eco_features
                    if (!isset($ecoFeatureDetails[$feature->id])) {
                        $ecoFeatureDetails[$feature->id] = [
                            'id' => $feature->id,
                            'name' => $feature->name,
                            'score' => 0,
                        ];
                    }
                    $ecoFeatureDetails[$feature->id]['score'] += $score;
                    $ecoFeatureList[] = [
                        'id' => $feature->id,
                        'name' => $feature->name,
                        'coef' => $coef,
                        'score' => $score,
                    ];
                    $totalScore += $score;
                }

                $ecoPurchases[] = [
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                    ],
                    'date' => $order->created_at->toDateString(),
                    'eco_features' => $ecoFeatureList,
                ];

                // Динамика по месяцам
                $month = Carbon::parse($order->created_at)->format('Y-m');
                if (!isset($monthlyStats[$month])) {
                    $monthlyStats[$month] = 0;
                }
                foreach ($ecoFeatures as $feature) {
                    $coef = $feature->pivot->value ?? 1;
                    $monthlyStats[$month] += $item->quantity * $weight * $coef;
                }
            }
        }

        return [
            'total_score' => $totalScore,
            'eco_features' => array_values($ecoFeatureDetails),
            'eco_purchases' => $ecoPurchases,
            'monthly_stats' => $monthlyStats,
        ];
    }
}