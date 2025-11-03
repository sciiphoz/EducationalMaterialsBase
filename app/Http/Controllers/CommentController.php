<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function addComment(Request $request, $materialId)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'text' => $request->text,
            'user_id' => auth()->id(),
            'material_id' => $materialId,
        ]);

        return redirect()->back()->with('success', 'Комментарий добавлен');
    }

        public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        
        if (Auth::user()->role === "admin" || Auth::id() === $comment->user_id) {
            $comment->delete();
            
            return redirect()->back()->with('success', 'Комментарий удален');
        }
        
        return redirect()->back()->with('error', 'У вас нет прав для удаления этого комментария');
    }
}