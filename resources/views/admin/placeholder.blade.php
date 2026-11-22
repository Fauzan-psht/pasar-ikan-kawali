@extends('layouts.dashboard')

@section('title', $title ?? 'Halaman')
@section('page_title', $title ?? 'Halaman')

@section('content')
<div class="panel text-center py-5">
    <div style="font-size:4rem;color:var(--primary-600);"><i class="bi bi-tools"></i></div>
    <h3 class="mt-3">{{ $title ?? 'Halaman' }}</h3>
    <p class="text-muted">Halaman ini akan diisi pada tahap berikutnya.</p>
</div>
@endsection
