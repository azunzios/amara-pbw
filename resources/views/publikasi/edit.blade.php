@extends('layouts.app')

@section('title', 'Edit Publikasi BPS Jawa Timur')

@section('content')
<div class="form-wrapper">
    <fieldset>
        <h3>Edit Publikasi</h3>
        @include('publikasi._form')
    </fieldset>
</div>
@endsection
