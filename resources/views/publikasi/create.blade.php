@extends('layouts.app')

@section('title', 'Tambah Publikasi BPS Jawa Timur')

@section('content')
<div class="form-wrapper">
    <fieldset>
        <h3>Form Menambahkan Publikasi Baru</h3>
        @include('publikasi._form')
    </fieldset>
</div>
@endsection
