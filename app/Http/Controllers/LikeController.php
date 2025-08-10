<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Material;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    // public function toggle($materialId)
    // {
    //     $material = Material::findOrFail($materialId);

    //     if ($material->isPrivate && $material->id_user !== Auth::id()) {
    //         abort(403);
    //     }

    //     $like = Like::where('id_material', $materialId)
    //         ->where('id_user', Auth::id())
    //         ->first();

    //     if ($like) {
    //         $like->delete();
    //     } else {
    //         Like::create([
    //             'id_material' => $materialId,
    //             'id_user' => Auth::id()
    //         ]);
    //     }

    //     return back();
    // }
}