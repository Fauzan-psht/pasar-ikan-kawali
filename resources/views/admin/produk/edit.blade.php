@extends('layouts.dashboard')

@section('title', 'Edit Produk')
@section('page_title', 'Edit Produk: ' . $produk->nama)

@section('content')
<form action="{{ route('admin.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('admin.produk.form')
    <div class="mt-3">
        <button type="submit" class="btn btn-primary-custom px-4 py-2"><i class="bi bi-save me-2"></i> Simpan Perubahan</button>
        <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary px-4 py-2 ms-2">Batal</a>
    </div>
</form>
@endsection