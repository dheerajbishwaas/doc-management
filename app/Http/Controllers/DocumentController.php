<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Auth::user()->documents()->latest()->get();
        return view('dashboard', compact('documents'));
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
        ]);

        $file = $request->file('document');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('documents', $filename, 'private');

        Document::create([
            'user_id' => Auth::id(),
            'filename' => $path,
            'original_name' => $file->getClientOriginalName(),
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Document uploaded successfully!');
    }

    public function destroy(Document $document)
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        Storage::disk('private')->delete($document->filename);

        $document->delete();

        return redirect()->route('dashboard')->with('success', 'Document deleted successfully!');
    }

    public function serveFile($filename)
    {
        // Find document by filename
        $document = Document::where('filename', 'documents/' . $filename)->first();

        if (!$document) {
            abort(404, 'Document not found');
        }

        // Check authorization: Admin can view all, users can only view their own
        $isAdmin = Auth::user()->is_admin;
        $isOwner = $document->user_id === Auth::id();
        
        \Log::info('File Access Attempt', [
            'user_id' => Auth::id(),
            'is_admin' => $isAdmin,
            'document_id' => $document->id,
            'document_owner' => $document->user_id,
            'is_owner' => $isOwner,
        ]);
        
        if (!$isAdmin && !$isOwner) {
            abort(403, 'You are not authorized to access this document');
        }

        $filePath = storage_path('app/' . $document->filename);

        if (!file_exists($filePath)) {
            abort(404, 'File not found on server');
        }

        return response()->file($filePath);
    }

    public function download(Document $document)
    {
        // Check if user is admin or document owner
        if (!Auth::user()->is_admin && $document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to document');
        }

        $filePath = storage_path('app/' . $document->filename);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath, $document->original_name);
    }
}
