@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Document History</h2>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                @if($documents->isEmpty())
                    <p class="text-muted">No documents found.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Filename</th>
                                    <th>Status</th>
                                    <th>Uploaded At</th>
                                    <th>Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $document)
                                    <tr>
                                        <td>{{ $document->user->name }}</td>
                                        <td>
                                            <a href="{{ url('storage/documents/' . basename($document->filename)) }}" target="_blank">
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
                                        <td>{{ $document->updated_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $documents->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
