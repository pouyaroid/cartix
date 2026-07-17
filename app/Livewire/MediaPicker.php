<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Media;
use Livewire\Component;
use Livewire\WithFileUploads;

class MediaPicker extends Component
{
    use WithFileUploads;

    public bool $isOpen = false;
    public ?string $selectedUrl = null;
    public ?int $selectedMediaId = null;
    public ?string $returnTo = null;
    public ?int $returnBlockId = null;
    public ?string $returnKey = null;
    public ?string $returnIdx = null;
    public ?string $returnFieldKey = null;
    public string $filter = 'all';
    public $newFile;

    protected $listeners = [
        'openMediaPicker' => 'handleOpen',
    ];

    public function handleOpen(): void
    {
        $args = func_get_args();
        $data = $args[0] ?? [];

        if (is_array($data)) {
            $this->returnTo = $data[0] ?? null;
            $this->returnBlockId = isset($data[1]) ? (int) $data[1] : null;
            $this->returnKey = $data[2] ?? null;
            $this->returnIdx = $data[3] ?? null;
            $this->returnFieldKey = $data[4] ?? null;
        }

        $this->isOpen = true;
        $this->selectedUrl = null;
        $this->selectedMediaId = null;
    }

    public function close(): void
    {
        $this->isOpen = false;
        $this->selectedUrl = null;
        $this->selectedMediaId = null;
    }

    public function selectMedia(int $mediaId): void
    {
        $media = Media::find($mediaId);
        if (!$media) return;

        $this->selectedUrl = $media->url;
        $this->selectedMediaId = $media->id;
    }

    public function confirmSelection(): void
    {
        if (!$this->selectedUrl || !$this->returnBlockId) return;

        $this->dispatch('mediaSelected', [
            'url' => $this->selectedUrl,
            'blockId' => (int) $this->returnBlockId,
            'returnTo' => $this->returnTo,
            'key' => $this->returnKey,
            'idx' => $this->returnIdx !== null ? (int) $this->returnIdx : null,
            'fieldKey' => $this->returnFieldKey,
        ]);

        $this->close();
    }

    public function saveUploadedFile(): void
    {
        $this->validate([
            'newFile' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,webp,svg',
        ]);

        try {
            $file = $this->newFile;
            $userId = auth()->id();
            $path = $file->store("media/{$userId}", 'public');

            $media = Media::create([
                'user_id' => $userId,
                'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'original_name' => $file->getClientOriginalName(),
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'path' => $path,
                'disk' => 'public',
            ]);

            $this->selectedUrl = $media->url;
            $this->selectedMediaId = $media->id;
            $this->newFile = null;

            $this->dispatch('show-toast', type: 'success', message: 'فایل با موفقیت آپلود شد');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('show-toast', type: 'error', message: 'فرمت فایل پشتیبانی نمی‌شود');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'خطا در آپلود فایل');
        }
    }

    public function getMedia()
    {
        $query = Media::where('user_id', auth()->id())
            ->orderByDesc('created_at');

        if ($this->filter !== 'all') {
            $query->where('mime_type', 'like', $this->filter . '%');
        }

        return $query->paginate(24);
    }

    public function render()
    {
        return view('livewire.media-picker');
    }
}
