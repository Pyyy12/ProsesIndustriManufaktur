@extends('layouts.app')

@section('title', 'Data PLP')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-xl">
    <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Daftar Data PLP (Staf Laboratorium)</h2>
    
    @if ($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ $message }}</span>
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('plp.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
            ➕ Tambah PLP
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full leading-normal border-collapse">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">NIP</th>
                    <th class="py-3 px-6 text-left">Nama PLP</th>
                    <th class="py-3 px-6 text-left">Tanggal Lahir</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @forelse ($plps as $plp)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $plp->nip }}</td>
                        <td class="py-3 px-6 text-left">{{ $plp->nama }}</td>
                        <td class="py-3 px-6 text-left">{{ \Carbon\Carbon::parse($plp->tgl_lahir)->format('d M Y') }}</td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center space-x-2">
                                {{-- Tombol Lihat --}}
                                <a href="{{ route('plp.show', $plp->nip) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-1 px-3 rounded text-xs transition duration-200">
                                    Lihat
                                </a>
                                {{-- Tombol Ubah --}}
                                <a href="{{ route('plp.edit', $plp->nip) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-xs transition duration-200">
                                    Ubah
                                </a>
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('plp.destroy', $plp->nip) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini? Aksi ini akan menghapus semua data peminjaman terkait.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs transition duration-200">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-3 px-6 text-center text-gray-500">Belum ada data PLP.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection