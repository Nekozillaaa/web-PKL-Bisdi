<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LabDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LabDocumentController extends Controller
{
    public function index()
    {
        $documents = LabDocument::latest()->get();

        return view('admin.documents.index', compact('documents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:20480',
        ]);

        $file = $request->file('file');
        $filename = time().'_'.$file->getClientOriginalName();
        $path = $file->storeAs('documents', $filename, 'public');

        LabDocument::create([
            'title' => $request->title,
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'is_active' => true,
        ]);

        return back()->with('success', 'Dokumen berhasil diunggah.');
    }

    public function destroy(LabDocument $document)
    {
        Storage::disk('public')->delete($document->path);
        $document->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    public function download(LabDocument $document)
    {
        return Storage::disk('public')->download($document->path, $document->filename);
    }

    public function view(LabDocument $document)
    {
        if (! Storage::disk('public')->exists($document->path)) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        return Storage::disk('public')->response($document->path, $document->filename, [
            'Content-Type' => $document->mime_type ?? 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$document->filename.'"',
        ]);
    }
}
