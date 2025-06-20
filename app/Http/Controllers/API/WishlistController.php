<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function toggle(Request $request)
    {
        $user = Auth::user();
        $productId = $request->input('product_id');

        // Получаем текущий список избранного
        $wishlist = $user->wishlist_data[0]['items'] ?? [];

        // Проверяем есть ли товар в избранном
        $index = array_search($productId, $wishlist);

        if ($index === false) {
            // Добавляем товар в избранное
            $wishlist[] = $productId;
            $message = 'Товар добавлен в избранное';
            $inWishlist = true;
        } else {
            // Удаляем товар из избранного
            unset($wishlist[$index]);
            $message = 'Товар удален из избранного';
            $inWishlist = false;
        }

        // Обновляем wishlist_data пользователя
        $user->wishlist_data = [['items' => array_values($wishlist)]];
        $user->save();

        return response()->json([
            'success' => true,
            'message' => $message,
            'inWishlist' => $inWishlist,
            'wishlistCount' => count($wishlist)
        ]);
    }
}