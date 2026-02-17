@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h4 class="mb-0">Dashboard</h4>
            </div>
            <div class="card-body">
                <p class="lead">Welcome back, {{ Auth::user()->name }}!</p>
                
                @if(Auth::user()->is_admin)
                    <div class="alert alert-info">
                        You have administrator access. <a href="{{ route('admin.dashboard') }}" class="fw-bold">Go to Admin Panel</a>
                    </div>
                @endif
            </div>
        </div>

        @if(!Auth::user()->is_admin)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                Upload New Document
            </div>
            <div class="card-body">
                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="document" class="form-label">Select Document (PDF, DOC, DOCX)</label>
                        <input class="form-control @error('document') is-invalid @enderror" type="file" id="document" name="document" accept=".pdf,.doc,.docx" required>
                        @error('document')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <x-button type="submit" variant="primary">Upload</x-button>
                </form>
            </div>
        </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5>Your Documents</h5>
            </div>
            <div class="card-body">
                @if($documents->isEmpty())
                    <p class="text-muted">No documents uploaded yet.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Filename</th>
                                    <th>Status</th>
                                    <th>Uploaded At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $document)
                                    <tr>
                                        <td>
                                            <a href="{{ url('storage/documents/' . basename($document->filename)) }}" target="_blank" class="text-decoration-none">
                                                {{ $document->original_name }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($document->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($document->status == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $document->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <form action="{{ route('documents.destroy', $document) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <x-button type="submit" variant="danger" size="sm">Delete</x-button>
                                            </form>
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
<script src="{{ asset('js/user-dashboard.js') }}"></script>
@endpush
