<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Material;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
public function addLike($materialId)
    {
        $user = Auth::user();
        
        // Ищем существующий лайк пользователя для этого материала
        $existingLike = Like::where('user_id', $user->id)
            ->where('material_id', $materialId)
            ->first();

        if ($existingLike) {
            if ($existingLike->value == 1) {
                // Если уже стоит лайк - удаляем его
                $existingLike->delete();
            } else {
                // Если стоит дизлайк - меняем на лайк
                $existingLike->update(['value' => 1]);
            }
        } else {
            // Создаем новый лайк
            Like::create([
                'user_id' => $user->id,
                'material_id' => $materialId,
                'value' => 1
            ]);
        }

        return redirect()->back();
    }

    public function addDislike($materialId)
    {
        $user = Auth::user();
        
        // Ищем существующий лайк пользователя для этого материала
        $existingLike = Like::where('user_id', $user->id)
            ->where('material_id', $materialId)
            ->first();

        if ($existingLike) {
            if ($existingLike->value == -1) {
                // Если уже стоит дизлайк - удаляем его
                $existingLike->delete();
            } else {
                // Если стоит лайк - меняем на дизлайк
                $existingLike->update(['value' => -1]);
            }
        } else {
            // Создаем новый дизлайк
            Like::create([
                'user_id' => $user->id,
                'material_id' => $materialId,
                'value' => -1
            ]);
        }

        return redirect()->back();
    }

}