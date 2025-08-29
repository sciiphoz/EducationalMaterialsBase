<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Tag;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function showMainPage() 
    {
        $materials = Material::all();
        return view('pages.main', compact('materials'));
    }

    public function index()
    {
        $materials = Material::where('isPrivate', false)
            ->with(['user', 'tags'])
            ->latest()
            ->paginate(10);

        return view('materials.index', compact('materials'));
    }

    public function show(Material $material)
    {
        return view('materials.show', [
            'material' => $material->load('tag', 'comment.user')
        ]);
    }

    public function create()
    {
        $this->authorize('create', Material::class);
        $tags = Tag::all();
        return view('materials.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Material::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'isPrivate' => 'boolean',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id_tag',
        ]);

        $material = Material::create([
            'id_material' => uniqid(),
            'name' => $validated['name'],
            'isPrivate' => $validated['isPrivate'] ?? false,
            'id_user' => auth()->id(),
            'date' => now(),
        ]);

        $material->tags()->attach($validated['tags'] ?? []);

        return redirect()->route('materials.show', $material);
    }

    public function edit(Material $material)
    {
        $this->authorize('update', $material);
        $tags = Tag::all();
        return view('materials.edit', compact('material', 'tags'));
    }

    public function update(Request $request, Material $material)
    {
        $this->authorize('update', $material);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'isPrivate' => 'boolean',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id_tag',
        ]);

        $material->update($validated);
        $material->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('materials.show', $material);
    }

    public function delete(Material $material)
    {
        $this->authorize('delete', $material);
        $material->delete();
        return redirect()->route('materials.index');
    }
}