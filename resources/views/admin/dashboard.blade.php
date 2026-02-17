@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-primary">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Admin Dashboard</h4>
                <div>
                    <a href="{{ route('admin.history') }}" class="btn btn-light btn-sm me-2">View History</a>
                    <span class="badge bg-light text-primary">{{ $documents->count() }} Pending</span>
                </div>
            </div>
            <div class="card-body">
                <p class="lead">Welcome, Administrator {{ Auth::user()->name }}!</p>
                
                <h5 class="mt-4">Pending Documents</h5>
                @if($documents->isEmpty())
                    <p class="text-muted">No pending documents to review.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Filename</th>
                                    <th>Uploaded At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $document)
                                    <tr id="doc-row-{{ $document->id }}">
                                        <td>{{ $document->user->name }}</td>
                                        <td>
                                            <a href="{{ url('storage/documents/' . basename($document->filename)) }}" target="_blank">
                                                {{ $document->original_name }}
                                            </a>
                                        </td>
                                        <td>{{ $document->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <button class="btn btn-success btn-sm me-2 action-btn" data-id="{{ $document->id }}" data-action="approve">Approve</button>
                                            <button class="btn btn-danger btn-sm action-btn" data-id="{{ $document->id }}" data-action="reject">Reject</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-dashboard.js') }}"></script>
@endpush
