
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ajukan Peminjaman Barang
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if ($items->isEmpty())
                    <div class="p-4 bg-yellow-50 text-yellow-700 rounded-lg">
                        Belum ada barang dengan stok tersedia untuk dipinjam.
                    </div>
                @else

                    @if ($errors->any())
                        <div class="mb-5 p-4 bg-red-50 text-red-700 rounded-lg">
                            <p class="font-semibold mb-2">
                                Terdapat kesalahan pada pengajuan:
                            </p>
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user.loans.store') }}" method="POST">
                        @csrf

                        {{-- Tanggal peminjaman --}}
                        <div class="mb-4">
                            <label for="loan_date"
                                   class="block font-medium text-sm text-gray-700 mb-2">
                                Tanggal Peminjaman
                            </label>

                            <input type="date"
                                   name="loan_date"
                                   id="loan_date"
                                   value="{{ old('loan_date', now()->toDateString()) }}"
                                   min="{{ now()->toDateString() }}"
                                   required
                                   class="w-full rounded-lg border-gray-300
                                          focus:border-blue-500 focus:ring-blue-500">

                            @error('loan_date')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Rencana tanggal pengembalian --}}
                        <div class="mb-4">
                            <label for="planned_return_date"
                                   class="block font-medium text-sm text-gray-700 mb-2">
                                Rencana Tanggal Pengembalian
                            </label>

                            <input type="date"
                                   name="planned_return_date"
                                   id="planned_return_date"
                                   value="{{ old('planned_return_date') }}"
                                   required
                                   class="w-full rounded-lg border-gray-300
                                          focus:border-blue-500 focus:ring-blue-500">

                            @error('planned_return_date')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Daftar barang --}}
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="font-semibold text-gray-800">
                                    Barang yang Dipinjam
                                </h3>

                                <button type="button"
                                        id="add-item"
                                        class="px-3 py-2 bg-green-600 text-white
                                               rounded-lg hover:bg-green-700">
                                    + Tambah Barang
                                </button>
                            </div>

                            <div id="item-container" class="space-y-4">
                                @php
                                    $oldItems = old('items', [
                                        ['item_id' => '', 'quantity_requested' => 1]
                                    ]);
                                @endphp

                                @foreach ($oldItems as $index => $oldItem)
                                    <div class="item-row border border-gray-200
                                                rounded-lg p-4">

                                        <div class="flex justify-between items-center mb-3">
                                            <h4 class="font-medium text-gray-700">
                                                Barang <span class="row-number">{{ $index + 1 }}</span>
                                            </h4>

                                            <button type="button"
                                                    class="remove-item text-red-600
                                                           hover:text-red-800">
                                                Hapus
                                            </button>
                                        </div>

                                        <div class="mb-3">
                                            <label class="block text-sm font-medium
                                                          text-gray-700 mb-2">
                                                Pilih Barang
                                            </label>

                                            <select name="items[{{ $index }}][item_id]"
                                                    class="item-select w-full rounded-lg
                                                           border-gray-300
                                                           focus:border-blue-500
                                                           focus:ring-blue-500"
                                                    required>

                                                <option value="">-- Pilih Barang --</option>

                                                @foreach ($items as $item)
                                                    <option value="{{ $item->id }}"
                                                            data-stock="{{ $item->stock }}"
                                                        {{ (string) ($oldItem['item_id'] ?? '') === (string) $item->id
                                                            ? 'selected' : '' }}>
                                                        {{ $item->name }}
                                                        ({{ $item->code }})
                                                        - {{ $item->category->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @error("items.$index.item_id")
                                                <p class="text-sm text-red-600 mt-1">
                                                    {{ $message }}
                                                </p>
                                            @enderror

                                            <p class="stock-info text-xs text-gray-500 mt-1">
                                                Pilih barang untuk melihat stok tersedia.
                                            </p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium
                                                          text-gray-700 mb-2">
                                                Jumlah yang Dipinjam
                                            </label>

                                            <input type="number"
                                                   name="items[{{ $index }}][quantity_requested]"
                                                   value="{{ $oldItem['quantity_requested'] ?? 1 }}"
                                                   min="1"
                                                   required
                                                   class="quantity-input w-full rounded-lg
                                                          border-gray-300
                                                          focus:border-blue-500
                                                          focus:ring-blue-500">

                                            @error("items.$index.quantity_requested")
                                                <p class="text-sm text-red-600 mt-1">
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Tujuan peminjaman --}}
                        <div class="mb-6">
                            <label for="purpose"
                                   class="block font-medium text-sm text-gray-700 mb-2">
                                Tujuan Peminjaman
                            </label>

                            <textarea name="purpose"
                                      id="purpose"
                                      rows="4"
                                      maxlength="1000"
                                      placeholder="Jelaskan tujuan peminjaman barang (opsional)"
                                      class="w-full rounded-lg border-gray-300
                                             focus:border-blue-500 focus:ring-blue-500">{{ old('purpose') }}</textarea>

                            @error('purpose')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tombol --}}
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('user.loans.index') }}"
                               class="px-4 py-2 border border-gray-300 rounded-lg
                                      hover:bg-gray-100">
                                Batal
                            </a>

                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white
                                           rounded-lg hover:bg-blue-700">
                                Kirim Pengajuan
                            </button>
                        </div>

                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Template baris barang tambahan --}}
    <template id="item-template">
        <div class="item-row border border-gray-200 rounded-lg p-4">

            <div class="flex justify-between items-center mb-3">
                <h4 class="font-medium text-gray-700">
                    Barang <span class="row-number"></span>
                </h4>

                <button type="button"
                        class="remove-item text-red-600 hover:text-red-800">
                    Hapus
                </button>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Barang
                </label>

                <select name="items[__INDEX__][item_id]"
                        class="item-select w-full rounded-lg border-gray-300
                               focus:border-blue-500 focus:ring-blue-500"
                        required>

                    <option value="">-- Pilih Barang --</option>

                    @foreach ($items as $item)
                        <option value="{{ $item->id }}"
                                data-stock="{{ $item->stock }}">
                            {{ $item->name }}
                            ({{ $item->code }})
                            - {{ $item->category->name }}
                        </option>
                    @endforeach
                </select>

                <p class="stock-info text-xs text-gray-500 mt-1">
                    Pilih barang untuk melihat stok tersedia.
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Jumlah yang Dipinjam
                </label>

                <input type="number"
                       name="items[__INDEX__][quantity_requested]"
                       value="1"
                       min="1"
                       required
                       class="quantity-input w-full rounded-lg border-gray-300
                              focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('item-container');
            const addButton = document.getElementById('add-item');
            const template = document.getElementById('item-template');

            let nextIndex = {{ count($oldItems) }};

            function updateRows() {
                const rows = container.querySelectorAll('.item-row');

                rows.forEach((row, index) => {
                    row.querySelector('.row-number').textContent = index + 1;

                    const select = row.querySelector('.item-select');
                    const selectedId = select.value;

                    const stockInfo = row.querySelector('.stock-info');
                    const quantityInput = row.querySelector('.quantity-input');
                    const selectedOption = select.options[select.selectedIndex];

                    if (selectedId && selectedOption.dataset.stock) {
                        const stock = Number(selectedOption.dataset.stock);

                        stockInfo.textContent = `Stok tersedia: ${stock} unit`;
                        quantityInput.max = stock;
                    } else {
                        stockInfo.textContent =
                            'Pilih barang untuk melihat stok tersedia.';
                        quantityInput.removeAttribute('max');
                    }
                });

                // Cegah barang yang sama dipilih pada baris lain.
                const selects = [...container.querySelectorAll('.item-select')];

                selects.forEach(select => {
                    const selectedByOthers = selects
                        .filter(other => other !== select)
                        .map(other => other.value)
                        .filter(value => value !== '');

                    [...select.options].forEach(option => {
                        option.disabled =
                            option.value !== '' &&
                            selectedByOthers.includes(option.value);
                    });
                });

                // Minimal satu baris barang harus tersedia.
                container.querySelectorAll('.remove-item').forEach(button => {
                    button.disabled = rows.length === 1;
                    button.classList.toggle('opacity-50', rows.length === 1);
                    button.classList.toggle('cursor-not-allowed', rows.length === 1);
                });
            }

            addButton?.addEventListener('click', function () {
                const newRow = template.innerHTML
                    .replaceAll('__INDEX__', nextIndex);

                container.insertAdjacentHTML('beforeend', newRow);
                nextIndex++;

                updateRows();
            });

            container.addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-item')) {
                    event.target.closest('.item-row').remove();
                    updateRows();
                }
            });

            container.addEventListener('change', function (event) {
                if (event.target.classList.contains('item-select')) {
                    updateRows();
                }
            });

            updateRows();
        });
    </script>
</x-app-layout>
