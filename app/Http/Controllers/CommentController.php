<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    // public function store(Request $request, $materialId)
    // {
    //     $material = Material::findOrFail($materialId);

    //     if ($material->isPrivate && $material->id_user !== Auth::id()) {
    //         abort(403);
    //     }

    //     $validated = $request->validate([
    //         'text' => 'required|string|max:1000'
    //     ]);

    //     Comment::create([
    //         'text' => $validated['text'],
    //         'id_user' => Auth::id(),
    //         'id_material' => $materialId
    //     ]);

    //     return back()->with('success', 'Комментарий добавлен');
    // }

    // public function destroy($id)
    // {
    //     $comment = Comment::findOrFail($id);

    //     if ($comment->id_user !== Auth::id() && !Auth::user()->isAdmin()) {
    //         abort(403);
    //     }

    //     $comment->delete();
    //     return back()->with('success', 'Комментарий удалён');
    // }
}