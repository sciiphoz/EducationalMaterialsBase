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
        
        $existingLike = Like::where('user_id', $user->id)
            ->where('material_id', $materialId)
            ->first();

        if ($existingLike) {
            if ($existingLike->value == 1) {
                $existingLike->delete();
            } else {
                $existingLike->update(['value' => 1]);
            }
        } else {
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
        
        $existingLike = Like::where('user_id', $user->id)
            ->where('material_id', $materialId)
            ->first();

        if ($existingLike) {
            if ($existingLike->value == -1) {
                $existingLike->delete();
            } else {
                $existingLike->update(['value' => -1]);
            }
        } else {
            Like::create([
                'user_id' => $user->id,
                'material_id' => $materialId,
                'value' => -1
            ]);
        }

        return redirect()->back();
    }

}