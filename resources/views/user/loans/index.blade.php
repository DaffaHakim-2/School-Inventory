<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Peminjaman Saya
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                {{-- Notifikasi --}}
                @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
                @endif

                {{-- Header tabel --}}
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            Daftar Pengajuan
                        </h3>
                        <p class="text-sm text-gray-500">
                            Daftar seluruh pengajuan peminjaman yang pernah kamu buat.
                        </p>
                    </div>

                    <a href="{{ route('user.loans.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg
                              hover:bg-blue-700">
                        + Ajukan Peminjaman
                    </a>
                </div>

                @if ($loans->isEmpty())
                <div class="text-center py-10">
                    <p class="text-gray-500 mb-4">
                        Kamu belum memiliki riwayat peminjaman.
                    </p>

                    <a href="{{ route('user.loans.create') }}" class="text-blue-600 hover:underline">
                        Buat pengajuan pertama
                    </a>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs
                                               font-medium text-gray-500 uppercase">
                                    No.
                                </th>

                                <th class="px-4 py-3 text-left text-xs
                                               font-medium text-gray-500 uppercase">
                                    Kode Peminjaman
                                </th>

                                <th class="px-4 py-3 text-left text-xs
                                               font-medium text-gray-500 uppercase">
                                    Tanggal Peminjaman
                                </th>

                                <th class="px-4 py-3 text-left text-xs
                                               font-medium text-gray-500 uppercase">
                                    Rencana Pengembalian
                                </th>

                                <th class="px-4 py-3 text-left text-xs
                                               font-medium text-gray-500 uppercase">
                                    Jumlah Jenis Barang
                                </th>

                                <th class="px-4 py-3 text-left text-xs
                                               font-medium text-gray-500 uppercase">
                                    Status
                                </th>

                                <th class="px-4 py-3 text-center text-xs
                                               font-medium text-gray-500 uppercase">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($loans as $index => $loan)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $loans->firstItem() + $index }}
                                </td>

                                <td class="px-4 py-3 text-sm font-medium
                                                   text-gray-900">
                                    {{ $loan->loan_code }}
                                </td>

                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $loan->loan_date->format('d-m-Y') }}
                                </td>

                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $loan->planned_return_date->format('d-m-Y') }}
                                </td>

                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $loan->details_count }} jenis barang
                                </td>

                                <td class="px-4 py-3 text-sm">
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
                                    'pending' => 'Menunggu',
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    'partially_issued' => 'Sebagian Diserahkan',
                                    'borrowed' => 'Dipinjam',
                                    'partially_returned' => 'Sebagian Dikembalikan',
                                    'returned' => 'Dikembalikan',
                                    'cancelled' => 'Dibatalkan',
                                    ];
                                    @endphp

                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                {{ $statusClasses[$loan->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$loan->status] ?? $loan->status }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('user.loans.show', $loan) }}" class="text-blue-600 hover:text-blue-800
                                                      font-medium text-sm">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

                <div class="mt-5">
                    {{ $loans->links() }}
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>