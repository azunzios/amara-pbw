{{-- Dipakai bersama oleh create & edit. Variabel: $publikasi (null saat create) --}}
@php($edit = isset($publikasi))

@if ($errors->any())
    <p class="pesan-error">
        @foreach ($errors->all() as $pesan)
            {{ $pesan }}<br>
        @endforeach
    </p>
@endif

<form action="{{ $edit ? route('publikasi.update', $publikasi) : route('publikasi.store') }}"
      method="post" enctype="multipart/form-data">
    @csrf
    @if ($edit)
        @method('PUT')
    @endif

    <table>
        <tr>
            <td>Judul:</td>
            <td><input type="text" name="judul" value="{{ old('judul', $edit ? $publikasi->judul : '') }}" required></td>
        </tr>
        <tr>
            <td>Tanggal Rilis:</td>
            <td><input type="date" name="tanggal_rilis"
                       value="{{ old('tanggal_rilis', $edit ? $publikasi->tanggal_rilis->format('Y-m-d') : '') }}" required></td>
        </tr>
        @if ($edit)
            <tr>
                <td>Sampul Lama:</td>
                <td>
                    @if ($publikasi->sampul)
                        <img src="{{ asset('asset/'.$publikasi->sampul) }}" alt="Sampul Publikasi" width="100px">
                    @else
                        -
                    @endif
                </td>
            </tr>
        @endif
        <tr>
            <td>{{ $edit ? 'Sampul Baru:' : 'Sampul:' }}</td>
            <td>
                <input type="file" name="sampul" accept=".jpg,.jpeg,.png,.webp" {{ $edit ? '' : 'required' }}>
                @if ($edit)
                    <br><small>Kosongkan jika tidak ingin mengganti sampul.</small>
                @endif
            </td>
        </tr>
        <tr>
            <td>Abstraksi:</td>
            <td><textarea name="abstraksi" rows="{{ $edit ? 5 : 4 }}" required>{{ old('abstraksi', $edit ? $publikasi->abstraksi : '') }}</textarea></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="{{ $edit ? 'Simpan Perubahan' : 'Simpan' }}">
                @if ($edit)
                    <a href="{{ route('publikasi.index') }}" class="btn-kembali">Kembali</a>
                @endif
            </td>
        </tr>
    </table>
</form>
