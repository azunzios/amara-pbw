@extends('layouts.app')

@section('title', 'Daftar Publikasi BPS Jawa Timur')

@section('content')
    <h2>Daftar Publikasi BPS Provinsi Jawa Timur</h2>

    <div class="search-container">
        <label for="search">Cari Publikasi:</label>
        <input type="text" id="search" placeholder="Ketik judul publikasi..."
               data-url="{{ route('publikasi.cari') }}" autocomplete="off">
        <div id="txtHint"></div>
    </div>

    <div class="publikasi">
        <div class="isi">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Tanggal Rilis</th>
                        <th>Sampul</th>
                        <th>Abstraksi</th>
                        <th>Edit/Hapus</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($publikasi as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->judul }}</td>
                        <td>{{ $item->tanggal_rilis->format('Y-m-d') }}</td>
                        <td>
                            @if ($item->sampul)
                                <img src="{{ asset('asset/'.$item->sampul) }}" alt="No Image" width="70px">
                            @endif
                        </td>
                        <td>{{ $item->abstraksi }}</td>
                        <td>
                            <a href="{{ route('publikasi.edit', $item) }}">
                                <img src="{{ asset('asset/edit.png') }}" style="width:25px;height:25px;" alt="Edit">
                            </a>
                            &nbsp;
                            <form action="{{ route('publikasi.destroy', $item) }}" method="POST" class="form-hapus"
                                  onsubmit="return confirm('Yakin ingin menghapus publikasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-ikon">
                                    <img src="{{ asset('asset/hapus.png') }}" style="width:30px;height:30px;" alt="Hapus">
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
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
