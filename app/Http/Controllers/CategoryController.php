<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori.
     */
    public function index(Request $request): View
    {
        $categories = Category::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->withCount('items')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Menampilkan formulir tambah kategori.
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Menyimpan kategori baru.
     */

public function store(StoreCategoryRequest $request): RedirectResponse
{
    $data = $request->validated();

    DB::transaction(function () use ($data) {
        // Menyimpan kategori baru.
        $category = Category::create($data);

        // Mencatat aktivitas penambahan kategori.
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action_type' => 'category_created',
            'description' => "Admin menambahkan kategori {$category->name}.",
            'created_at' => now(),
        ]);
    });

    return redirect()
        ->route('admin.categories.index')
        ->with('success', 'Kategori berhasil ditambahkan.');
}


    /**
     * Menampilkan formulir edit kategori.
     */
    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Memperbarui kategori.
     */
    public function update(
        UpdateCategoryRequest $request,
        Category $category
    ): RedirectResponse {
        $category->update($request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Menghapus kategori menggunakan Soft Delete.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->items()->withTrashed()->exists()) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh barang.');
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}