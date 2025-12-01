<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TempUploadController extends Controller
{
    /**
     * Upload a temporary image to storage/app/temp and return stored filename.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|image|max:5120', // max 5MB
        ]);

        $file = $request->file('file');
        if (! $file) {
            return response()->json(['error' => 'No file uploaded'], 422);
        }

        $ext = $file->getClientOriginalExtension();
        $timestamp = now()->format('YmdHis');
        $name = $timestamp . '_' . Str::random(8) . '.' . $ext;

        $path = $file->storeAs('temp', $name);

        return response()->json([
            'storedName' => $name,
            'originalName' => $file->getClientOriginalName(),
            'path' => $path,
        ]);
    }

    /**
     * Delete a temporary uploaded file (by filename) from storage/app/temp.
     */
    public function delete(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
        ]);

        $filename = $request->input('filename');
        $path = 'temp/' . $filename;

        if (Storage::exists($path)) {
            Storage::delete($path);
            return response()->json(['deleted' => true]);
        }

        return response()->json(['deleted' => false], 404);
    }
}
