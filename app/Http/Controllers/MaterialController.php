<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Section;
use App\Models\Tag;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Laravel\Facades\Image;


class MaterialController extends Controller
{
    public function showMainPage(Request $request)
    {
        if (auth()->user()?->role == 'admin') {
            $query = Material::with(['like', 'tag', 'section'])
                ->withCount(['like as likes_sum' => function($query) {
                    $query->select(DB::raw('COALESCE(SUM(value), 0)'));
                }]);
        }
        else {
            $query = Material::with(['like', 'tag', 'section'])
                ->public()
                ->withCount(['like as likes_sum' => function($query) {
                    $query->select(DB::raw('COALESCE(SUM(value), 0)'));
                }]);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->has('tag') && $request->tag != '') {
            $query->where('tag_id', $request->tag);
        }

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

        $materials = $query->paginate(10);
        $tags = Tag::all();

        return view('pages.mainpage', compact('materials', 'tags'));
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
            'tag_id' => 'required|exists:tags,id',
            'isPrivate' => 'sometimes|boolean',
            'isDisabled' => 'sometimes|boolean',
            'sections' => 'required|array|min:1',
            'sections.*.type' => 'required|in:text,code,image',
            'sections.*.order' => 'required|integer',
            'sections.*.content' => 'required_if:sections.*.type,text,code',
            'sections.*.language' => 'required_if:sections.*.type,code',
            'sections.*.image' => 'required_if:sections.*.type,image|image|mimes:jpeg,png,jpg,gif|max:5120',
            'sections.*.image_alt' => 'nullable|string|max:255',
        ]);

        $material = Material::create([
            'title' => $validated['title'],
            'tag_id' => $validated['tag_id'],
            'isPrivate' => $request->has('isPrivate'),
            'isDisabled' => $request->has('isDisabled'),
            'user_id' => Auth::id(),
            'date' => now()->format('Y-m-d'),
        ]);

        foreach ($request->sections as $sectionData) {
            $section = new Section();
            $section->type = $sectionData['type'];
            $section->order = $sectionData['order'];
            $section->material_id = $material->id;

            if ($sectionData['type'] === 'text') {
                $section->content = $sectionData['content'];
            } elseif ($sectionData['type'] === 'code') {
                $section->content = $sectionData['content'];
                $section->language = $sectionData['language'];
            } elseif ($sectionData['type'] === 'image') {
                $section->setImageSmart($sectionData['image']);
                $section->image_alt = $sectionData['image_alt'] ?? null;
            }

            $section->save();
        }

        return redirect()->route('profile.show')->with('success', 'Материал успешно создан!');
    }

    public function show($id)
    {
        $material = Material::with([
            'like', 
            'tag', 
            'section',
            'comment.user'
        ])->findOrFail($id);
        
        return view('pages.material', compact('material'));
    }

    public function edit($id)
    {
        if (auth()->user()->role == 'admin') {
            $material = Material::findOrFail($id);
        }
        else {
            $material = Material::where('user_id', auth()->user()->id)->findOrFail($id);
        }
        $tags = Tag::all();
        
        return view('pages.edit_material', compact('material', 'tags'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role == 'admin') {
            $material = Material::findOrFail($id);
        }
        else {
            $material = Material::where('user_id', auth()->user()->id)->findOrFail($id);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'tag_id' => 'required|exists:tags,id',
            'isPrivate' => 'sometimes|boolean',
            'isDisabled' => 'sometimes|boolean',
            'sections' => 'required|array|min:1',
            'sections.*.type' => 'required|in:text,code,image',
            'sections.*.order' => 'required|integer',
            'sections.*.content' => 'required_if:sections.*.type,text,code',
            'sections.*.language' => 'required_if:sections.*.type,code',
            'sections.*.image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:5120',
            'sections.*.image_alt' => 'nullable|string|max:255',
            'sections.*.id' => 'sometimes|exists:sections,id',
        ]);

        $material->update([
            'title' => $validated['title'],
            'tag_id' => $validated['tag_id'],
            'isPrivate' => $request->has('isPrivate'),
            'isDisabled' => $request->has('isDisabled'),
        ]);

        $material->section()->delete();

        foreach ($request->sections as $sectionData) {
            $section = new Section();
            $section->type = $sectionData['type'];
            $section->order = $sectionData['order'];
            $section->material_id = $material->id;

            if ($sectionData['type'] === 'text') {
                $section->content = $sectionData['content'];
            } elseif ($sectionData['type'] === 'code') {
                $section->content = $sectionData['content'];
                $section->language = $sectionData['language'];
            } elseif ($sectionData['type'] === 'image') {
                if (isset($sectionData['image']) && $sectionData['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $section->setImageSmart($sectionData['image']);
                } else {
                    if (isset($sectionData['id'])) {
                        $existingSection = Section::find($sectionData['id']);
                        if ($existingSection && $existingSection->isImage()) {
                            $section->image_base64 = $existingSection->image_base64;
                            $section->image_mime_type = $existingSection->image_mime_type;
                            $section->image_name = $existingSection->image_name;
                        }
                    }
                }
                $section->image_alt = $sectionData['image_alt'] ?? null;
            }

            $section->save();
        }

        if (auth()->user()->role == 'admin') {
            return redirect()->route('view.mainpage');
        }
        else {
            return redirect()->route('profile.show')->with('success', 'Материал успешно обновлен!');
        }
    }

    public function destroy($id)
    {
        if (auth()->user()->role == 'admin') {
            $material = Material::findOrFail($id);
        }
        else {
            $material = Material::where('user_id', auth()->user()->id)->findOrFail($id);
        }
        
        $material->like()->delete();
        $material->comment()->delete();
        $material->section()->delete();
        
        $material->delete();

        if (auth()->user()->role == 'admin') {
            return redirect()->route('view.mainpage');
        }
        else {
            return redirect()->route('profile.show')->with('success', 'Материал успешно удалён!');
        }
    }

    public function showProfile()
    {
        $materials = Material::with(['like', 'tag', 'section'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('pages.profilepage', compact('materials'));
    }

}