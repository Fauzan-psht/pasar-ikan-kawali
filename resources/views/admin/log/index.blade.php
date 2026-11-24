@extends('layouts.dashboard')

@section('title', 'Log Aktivitas')
@section('page_title', 'Log Aktivitas Sistem')

@section('content')
<div class="panel">
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Aksi</th>
                    <th>Model Terkait</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>
                        @if($log->user)
                            <strong>{{ $log->user->name }}</strong> <br>
                            <small class="text-muted">{{ $log->user->role }}</small>
                        @else
                            <span class="text-muted">Sistem / Guest</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge-soft info">{{ $log->aksi }}</span>
                    </td>
                    <td>
                        @if($log->model)
                            {{ class_basename($log->model) }} (ID: {{ $log->model_id }})
                        @else
                            -
                        @endif
                    </td>
                    <td><code>{{ $log->ip }}</code></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada log aktivitas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $logs->links() }}</div>
</div>
@endsection