<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
    public function showMainPage(Request $request) 
    {
        $query = Material::with(['like', 'tag'])
            ->public() // Только публичные материалы
            ->withCount(['like as likes_sum' => function($query) {
                $query->select(DB::raw('COALESCE(SUM(value), 0)'));
            }]);

        // Поиск по названию
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        // Фильтр по тегу
        if ($request->has('tag') && $request->tag != '') {
            $query->where('tag_id', $request->tag);
        }

        // Сортировка
        switch ($request->get('sort', 'newest')) {
            case 'oldest':
                $query->orderBy('date', 'asc');
                break;
            case 'popular':
                $query->orderBy('likes_sum', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('date', 'desc');
                break;
        }

        $materials = $query->paginate(10); // Пагинация по 10 материалов
        $tags = Tag::all(); // Все теги для фильтра

        return view('pages.mainpage', compact('materials', 'tags'));
    }

    public function show($id)
    {
        $material = Material::with([
        'like', 
        'tag', 
        'comment.user' 
        ])->findOrFail($id);
        
        return view('pages.material', compact('material'));
    }

    public function create()
    {
        $tags = Tag::all();
        return view('pages.add_material', compact('tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'text' => 'required|string',
            'tag_id' => 'required|exists:tags,id',
            'isPrivate' => 'sometimes|boolean',
            'isDisabled' => 'sometimes|boolean',
        ]);

        Material::create([
            'title' => $validated['title'],
            'text' => $validated['text'],
            'tag_id' => $validated['tag_id'],
            'isPrivate' => $request->has('isPrivate'),
            'isDisabled' => $request->has('isDisabled'),
            'user_id' => Auth::id(),
            'date' => now()->format('Y-m-d'),
        ]);

        return redirect()->route('profile.show')->with('success', 'Материал успешно создан!');
    }

    public function edit($id)
    {
        $material = Material::where('user_id', auth()->id())->findOrFail($id);
        $tags = Tag::all();
        
        return view('pages.edit_material', compact('material', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $material = Material::where('user_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'text' => 'required|string',
            'tag_id' => 'required|exists:tags,id',
            'isPrivate' => 'sometimes|boolean',
            'isDisabled' => 'sometimes|boolean',
        ]);

        $material->update([
            'title' => $validated['title'],
            'text' => $validated['text'],
            'tag_id' => $validated['tag_id'],
            'isPrivate' => $request->has('isPrivate'),
            'isDisabled' => $request->has('isDisabled'),
        ]);

        return redirect()->route('profile.show')->with('success', 'Материал успешно обновлен!');
    }

    public function destroy($id)
    {
        $material = Material::where('user_id', auth()->id())->findOrFail($id);
        
        // Удаляем связанные лайки и комментарии
        $material->like()->delete();
        $material->comment()->delete();
        
        $material->delete();

        return redirect()->route('profile.show')->with('success', 'Материал успешно удален!');
    }
}