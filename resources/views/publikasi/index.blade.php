@extends('layouts.app')

@section('title', 'Daftar Publikasi BPS Jawa Timur')

@section('content')
    <div class="publikasi-topbar">
        <h2>Daftar Publikasi BPS Provinsi Jawa Timur</h2>
        <a href="{{ route('publikasi.create') }}" class="btn-tambah-utama" title="Tambah Publikasi Baru">
            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Tambah Publikasi
        </a>
    </div>

    <div class="search-container">
        <label for="search">Cari Publikasi:</label>
        <div class="search-box-wrapper">
            <svg class="search-icon-left" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
            <input type="text" id="search" placeholder="Ketik judul publikasi (misal: Sensus, Direktori, Air Bersih)..."
                   data-url="{{ route('publikasi.cari') }}" autocomplete="off">
            <button type="button" id="searchClearBtn" class="search-clear-btn" title="Hapus pencarian">&times;</button>
        </div>
        <div id="txtHint"></div>
    </div>

    <div class="publikasi">
        <div class="isi">
            <table id="tabelPublikasi">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 25%;">Judul</th>
                        <th style="width: 13%;">Tanggal Rilis</th>
                        <th style="width: 12%;">Sampul</th>
                        <th style="width: 35%;">Abstraksi</th>
                        <th style="width: 10%;">Edit/Hapus</th>
                    </tr>
                </thead>
                <tbody id="tbodyPublikasi">
                @forelse ($publikasi as $item)
                    <tr class="publikasi-row" data-judul="{{ strtolower($item->judul) }}" data-abstraksi="{{ strtolower($item->abstraksi) }}">
                        <td>{{ $loop->iteration }}</td>
                        <td class="col-judul"><strong>{{ $item->judul }}</strong></td>
                        <td>{{ $item->tanggal_rilis->format('Y-m-d') }}</td>
                        <td>
                            @if ($item->sampul)
                                <img src="{{ asset('asset/'.$item->sampul) }}" alt="Sampul {{ $item->judul }}" width="70px" class="sampul-img">
                            @else
                                <span class="no-sampul">-</span>
                            @endif
                        </td>
                        <td class="abstrak">{{ $item->abstraksi }}</td>
                        <td class="col-aksi">
                            <div class="aksi-container">
                                <a href="{{ route('publikasi.edit', $item) }}" class="btn-3d-circle btn-3d-edit" title="Edit Publikasi">
                                    <img src="{{ asset('asset/edit.png') }}" alt="Edit" class="icon-3d">
                                </a>
                                <form action="{{ route('publikasi.destroy', $item) }}" method="POST" class="form-hapus"
                                      onsubmit="return confirm('Yakin ingin menghapus publikasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-3d-circle btn-3d-delete" title="Hapus Publikasi">
                                        <img src="{{ asset('asset/hapus.png') }}" alt="Hapus" class="icon-3d">
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr id="emptyRow">
                        <td colspan="6">Belum ada data publikasi.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/pencarian.js') }}"></script>
@endpush
