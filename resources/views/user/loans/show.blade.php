<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Pengajuan Peminjaman
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                {{-- Notifikasi --}}
                @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
                @endif

                {{-- Informasi pengajuan --}}
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ $loan->loan_code }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Pengajuan dibuat pada
                            {{ $loan->created_at->format('d-m-Y H:i') }}
                        </p>
                    </div>

                    @php
                    $statusClasses = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'approved' => 'bg-blue-100 text-blue-800',
                    'rejected' => 'bg-red-100 text-red-800',
                    'partially_issued' => 'bg-indigo-100 text-indigo-800',
                    'borrowed' => 'bg-purple-100 text-purple-800',
                    'partially_returned' => 'bg-orange-100 text-orange-800',
                    'returned' => 'bg-green-100 text-green-800',
                    'cancelled' => 'bg-gray-100 text-gray-800',
                    ];

                    $statusLabels = [
                    'pending' => 'Menunggu Persetujuan',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    'partially_issued' => 'Sebagian Diserahkan',
                    'borrowed' => 'Sedang Dipinjam',
                    'partially_returned' => 'Sebagian Dikembalikan',
                    'returned' => 'Sudah Dikembalikan',
                    'cancelled' => 'Dibatalkan',
                    ];
                    @endphp

                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        {{ $statusClasses[$loan->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$loan->status] ?? $loan->status }}
                    </span>
                </div>

                <hr class="mb-6">

                {{-- Informasi tanggal dan tujuan --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">

                    <div>
                        <p class="text-sm text-gray-500">Tanggal Peminjaman</p>
                        <p class="font-medium text-gray-800">
                            {{ $loan->loan_date->format('d-m-Y') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Rencana Tanggal Pengembalian
                        </p>
                        <p class="font-medium text-gray-800">
                            {{ $loan->planned_return_date->format('d-m-Y') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Tujuan Peminjaman</p>
                        <p class="font-medium text-gray-800">
                            {{ $loan->purpose ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Disetujui Oleh</p>
                        <p class="font-medium text-gray-800">
                            {{ $loan->approver?->name ?? 'Belum diproses' }}
                        </p>
                    </div>
                </div>

                {{-- Daftar barang --}}
                <h3 class="font-semibold text-gray-800 mb-3">
                    Daftar Barang yang Diajukan
                </h3>

                <div class="overflow-x-auto mb-6">
                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs
                                           font-medium text-gray-500 uppercase">
                                    No.
                                </th>

                                <th class="px-4 py-3 text-left text-xs
                                           font-medium text-gray-500 uppercase">
                                    Barang
                                </th>

                                <th class="px-4 py-3 text-center text-xs
                                           font-medium text-gray-500 uppercase">
                                    Jumlah Diminta
                                </th>

                                <th class="px-4 py-3 text-center text-xs
                                           font-medium text-gray-500 uppercase">
                                    Jumlah Disetujui
                                </th>

                                <th class="px-4 py-3 text-center text-xs
                                           font-medium text-gray-500 uppercase">
                                    Jumlah Diserahkan
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($loan->details as $index => $detail)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-4 py-3">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $detail->item?->name ?? 'Barang tidak tersedia' }}
                                    </p>

                                    @if ($detail->item)
                                    <p class="text-xs text-gray-500">
                                        {{ $detail->item->code }}
                                        — {{ $detail->item->category->name }}
                                    </p>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-sm text-center">
                                    {{ $detail->quantity_requested }}
                                </td>

                                <td class="px-4 py-3 text-sm text-center">
                                    {{ $detail->quantity_approved }}
                                </td>

                                <td class="px-4 py-3 text-sm text-center">
                                    {{ $detail->quantity_issued }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

                {{-- Tombol kembali --}}
                <div class="flex justify-end">
                    <a href="{{ route('user.loans.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg
                              hover:bg-gray-100">
                        Kembali ke Riwayat
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>