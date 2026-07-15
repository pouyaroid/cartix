<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Font;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FontController extends Controller
{
    public function index()
    {
        $fonts = Font::latest()->get();

        return view('admin.fonts.index', compact('fonts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:woff,woff2,ttf|max:5120',
            'file_format' => 'required|in:woff2,woff,ttf',
        ]);

        $file = $request->file('file');
        $path = $file->store('fonts', 'public');

        Font::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'file_path' => $path,
            'file_format' => $validated['file_format'],
            'is_active' => true,
        ]);

        return back()->with('success', 'فونت با موفقیت آپلود شد.');
    }

    public function destroy(Font $font)
    {
        \Storage::disk('public')->delete($font->file_path);
        $font->delete();

        return back()->with('success', 'فونت حذف شد.');
    }

    public function toggle(Font $font)
    {
        $font->update(['is_active' => !$font->is_active]);

        return back()->with('success', 'وضعیت فونت تغییر کرد.');
    }
}
