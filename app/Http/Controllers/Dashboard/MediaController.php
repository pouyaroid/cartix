<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\MediaFolder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $folderId = $request->get('folder');
        $query = Media::where('user_id', auth()->id());

        if ($folderId) {
            $query->where('folder_id', $folderId);
        } else {
            $query->whereNull('folder_id');
        }

        $media = $query->latest()->paginate(24);
        $folders = MediaFolder::where('user_id', auth()->id())
            ->when($folderId, fn($q) => $q->where('parent_id', $folderId), fn($q) => $q->whereNull('parent_id'))
            ->get();

        $currentFolder = $folderId ? MediaFolder::find($folderId) : null;

        return view('dashboard.media.index', compact('media', 'folders', 'currentFolder'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,webp,pdf,mp3,mp4,doc,docx',
        ]);

        $file = $request->file('file');
        $path = $file->store('media/' . auth()->id(), 'public');

        $media = Media::create([
            'user_id' => auth()->id(),
            'folder_id' => $request->folder_id,
            'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'original_name' => $file->getClientOriginalName(),
            'file_name' => basename($path),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'path' => $path,
            'disk' => 'public',
        ]);

        return response()->json(['success' => true, 'media' => $media]);
    }

    public function destroy(Media $media)
    {
        $this->authorize('delete', $media);

        Storage::disk('public')->delete($media->path);
        $media->delete();

        return back()->with('success', 'فایل حذف شد.');
    }

    public function rename(Request $request, Media $media)
    {
        $this->authorize('update', $media);

        $request->validate(['name' => 'required|string|max:255']);

        $media->update(['name' => $request->name]);

        return response()->json(['success' => true]);
    }

    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $folder = MediaFolder::create([
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return response()->json(['success' => true, 'folder' => $folder]);
    }

    public function deleteFolder(MediaFolder $folder)
    {
        $this->authorize('delete', $folder);

        Media::where('folder_id', $folder->id)->update(['folder_id' => null]);
        $folder->delete();

        return back()->with('success', 'پوشه حذف شد.');
    }
}
