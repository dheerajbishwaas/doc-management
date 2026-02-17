<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\DocumentLog;
use Illuminate\Support\Facades\Auth;

class AdminDocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with('user')->where('status', 'pending')->latest()->get();
        return view('admin.dashboard', compact('documents'));
    }

    public function approve(Document $document)
    {
        $document->update(['status' => 'approved']);

        DocumentLog::create([
            'document_id' => $document->id,
            'action' => 'approved',
            'performed_by' => Auth::id(),
        ]);

        return response()->json(['message' => "Document '{$document->original_name}' approved successfully"]);
    }

    public function reject(Document $document)
    {
        $document->update(['status' => 'rejected']);

        DocumentLog::create([
            'document_id' => $document->id,
            'action' => 'rejected',
            'performed_by' => Auth::id(),
        ]);

        return response()->json(['message' => "Document '{$document->original_name}' rejected successfully"]);
    }
    public function history()
    {
        $documents = Document::with('user')->latest()->paginate(10);
        return view('admin.history', compact('documents'));
    }
}
