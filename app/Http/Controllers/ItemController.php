<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemMovement;
use App\Models\ActivityLog;
use App\Http\Requests\AdjustItemStockRequest;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class ItemController extends Controller
{
    /**
     * Menampilkan daftar barang.
     */
    public function index(Request $request): View
    {
        $items = Item::query()
            ->with('category')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.items.index', compact('items'));
    }

    /**
     * Menampilkan formulir tambah barang.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.items.create', compact('categories'));
    }

    /**
     * Menyimpan barang baru dan mencatat stok awal.
     */

/**
 * Menyimpan barang baru dan mencatat stok awal.
 */
public function store(StoreItemRequest $request): RedirectResponse
{
    $data = $request->validated();

    $initialStock = $data['initial_stock'];

    unset($data['initial_stock']);

    DB::transaction(function () use ($data, $initialStock) {
        // Menyimpan barang baru.
        $item = Item::create([
            ...$data,
            'stock' => $initialStock,
        ]);

        // Mencatat stok awal barang.
        ItemMovement::create([
            'item_id' => $item->id,
            'user_id' => auth()->id(),
            'movement_type' => 'initial_stock',
            'quantity' => $initialStock,
            'stock_before' => 0,
            'stock_after' => $initialStock,
            'description' => 'Stok awal saat barang dibuat.',
            'created_at' => now(),
        ]);

        // Mencatat aktivitas penambahan barang.
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action_type' => 'item_created',
            'description' => "Admin menambahkan barang {$item->name} dengan kode {$item->code}.",
            'created_at' => now(),
        ]);
    });

    return redirect()
        ->route('admin.items.index')
        ->with('success', 'Barang berhasil ditambahkan.');
}


    /**
     * Menampilkan detail barang.
     */
    public function show(Item $item): View
    {
        $item->load([
            'category',
            'itemMovements' => function ($query) {
                $query->with('user')->latest()->limit(20);
            },
        ]);

        return view('admin.items.show', compact('item'));
    }

    /**
     * Menampilkan formulir edit barang.
     */
    public function edit(Item $item): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.items.edit', compact('item', 'categories'));
    }

    /**
     * Memperbarui data barang tanpa mengubah stok.
     */
    public function update(
        UpdateItemRequest $request,
        Item $item
    ): RedirectResponse {
        $item->update($request->validated());

        return redirect()
            ->route('admin.items.index')
            ->with('success', 'Data barang berhasil diperbarui.');
    }

    /**
     * Menghapus barang menggunakan Soft Delete.
     */
    public function destroy(Item $item): RedirectResponse
    {
        $item->delete();

        return redirect()
            ->route('admin.items.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
    /**
         * Menyesuaikan stok barang.
     */
    public function adjustStock(
        AdjustItemStockRequest $request,
        Item $item
    ): RedirectResponse {
        $data = $request->validated();

        // stock_in hanya diperbolehkan untuk penambahan stok.
        if (
            $data['movement_type'] === 'stock_in' &&
            $data['direction'] !== 'increase'
        ) {
            throw ValidationException::withMessages([
                'movement_type' => 'Stok masuk hanya dapat digunakan untuk menambah stok.',
            ]);
        }

    DB::transaction(function () use ($data, $item) {

        // Mengunci baris barang selama transaksi berlangsung.
        $lockedItem = Item::whereKey($item->id)
            ->lockForUpdate()
            ->firstOrFail();

        $stockBefore = $lockedItem->stock;
        $quantity = (int) $data['quantity'];

        // Menghitung stok baru.
        if ($data['direction'] === 'increase') {
            $stockAfter = $stockBefore + $quantity;
            $movementQuantity = $quantity;

            // Batas maksimum unsigned integer pada kolom stock.
            if ($stockAfter > 2147483647) {
                throw ValidationException::withMessages([
                    'quantity' => 'Jumlah stok melebihi kapasitas maksimum.',
                ]);
            }
        } else {
            $stockAfter = $stockBefore - $quantity;
            $movementQuantity = -$quantity;

            if ($stockAfter < 0) {
                throw ValidationException::withMessages([
                    'quantity' => 'Stok tidak mencukupi untuk pengurangan tersebut.',
                ]);
            }
        }

        // Memperbarui stok barang.
        $lockedItem->update([
            'stock' => $stockAfter,
        ]);

        // Mencatat pergerakan stok.
        ItemMovement::create([
            'item_id' => $lockedItem->id,
            'user_id' => auth()->id(),
            'movement_type' => $data['movement_type'],
            'quantity' => $movementQuantity,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'description' => $data['description'],
            'created_at' => now(),
        ]);
    });

    return redirect()
        ->route('admin.items.show', $item)
        ->with('success', 'Stok barang berhasil disesuaikan.');
}
}