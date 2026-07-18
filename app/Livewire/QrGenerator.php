<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\QrRenderer;

class QrGenerator extends Component
{
    use WithFileUploads;

    // Basic
    public $title = '';
    public $type = 'static';
    public $content = 'https://';

    // Colors
    public $foregroundColor = '#000000';
    public $backgroundColor = '#FFFFFF';
    public $gradientFrom = '';
    public $gradientTo = '';
    public $eyeColor = '#000000';

    // Style
    public $style = 'square';
    public $shape = 'square';
    public $pattern = 'default';
    public $size = 300;
    public $errorCorrection = 'M';

    // Eye
    public $eyeStyle = 'square';

    // Frame
    public $frameStyle = 'none';
    public $frameColor = '#000000';

    // Text
    public $text = '';
    public $textPosition = 'bottom';
    public $textFont = 'Vazirmatn';
    public $textSize = 14;
    public $textColor = '#000000';

    // Logo
    public $logo;
    public $logoPreview = null;
    public $logoSize = 50;
    public $logoPadding = 5;

    // Settings
    public $margin = 10;
    public $resolution = 300;

    // Templates
    public $selectedTemplate = '';

    // Preview
    public $previewImage = null;
    public $generating = false;

    public function mount(): void
    {
        $this->updatePreview();
    }

    // ------------------------------------------------------------------
    // Livewire lifecycle
    // ------------------------------------------------------------------

    public function updated(string $path, mixed $value): void
    {
        if ($path === 'logo') {
            return;
        }
        $this->updatePreview();
    }

    // ------------------------------------------------------------------
    // Setter methods for wire:click
    // ------------------------------------------------------------------

    public function setStyle(string $value): void
    {
        $this->style = $value;
        $this->updatePreview();
    }

    public function setShape(string $value): void
    {
        $this->shape = $value;
        $this->updatePreview();
    }

    public function setEyeStyle(string $value): void
    {
        $this->eyeStyle = $value;
        $this->updatePreview();
    }

    public function setFrameStyle(string $value): void
    {
        $this->frameStyle = $value;
        $this->updatePreview();
    }

    // ------------------------------------------------------------------
    // File upload
    // ------------------------------------------------------------------

    public function updatedLogo(): void
    {
        try {
            $this->validate([
                'logo' => 'image|mimes:jpg,jpeg,png,svg|max:2048',
            ]);
            $this->logoPreview = $this->logo->temporaryUrl();
        } catch (\Illuminate\Validation\ValidationException) {
            $this->logo = null;
            $this->logoPreview = null;
        }

        $this->updatePreview();
    }

    public function removeLogo(): void
    {
        $this->logo = null;
        $this->logoPreview = null;
        $this->updatePreview();
    }

    // ------------------------------------------------------------------
    // Template selection
    // ------------------------------------------------------------------

    public function selectTemplate(string $template): void
    {
        $this->selectedTemplate = $template;
        $presets = $this->getPresetTemplates();

        if (isset($presets[$template])) {
            foreach ($presets[$template] as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }

        $this->updatePreview();
    }

    // ------------------------------------------------------------------
    // Build QrRenderer from current component state
    // ------------------------------------------------------------------

    private function makeRenderer(): QrRenderer
    {
        $renderer = QrRenderer::fromLivewire([
            'foregroundColor' => $this->foregroundColor,
            'backgroundColor' => $this->backgroundColor,
            'gradientFrom'    => $this->gradientFrom,
            'gradientTo'      => $this->gradientTo,
            'eyeColor'        => $this->eyeColor,
            'style'           => $this->style,
            'shape'           => $this->shape,
            'pattern'         => $this->pattern,
            'size'            => $this->size,
            'errorCorrection' => $this->errorCorrection,
            'eyeStyle'        => $this->eyeStyle,
            'frameStyle'      => $this->frameStyle,
            'frameColor'      => $this->frameColor,
            'text'            => $this->text,
            'textPosition'    => $this->textPosition,
            'textFont'        => $this->textFont,
            'textSize'        => $this->textSize,
            'textColor'       => $this->textColor,
            'logoSize'        => $this->logoSize,
            'logoPadding'     => $this->logoPadding,
            'margin'          => $this->margin,
            'resolution'      => $this->resolution,
            'content'         => $this->content,
        ]);

        // Logo: use Livewire temp file path
        if ($this->logo) {
            $renderer->setLogoPath($this->logo->getRealPath());
        }

        return $renderer;
    }

    // ------------------------------------------------------------------
    // Preview — delegates entirely to QrRenderer
    // ------------------------------------------------------------------

    public function updatePreview(): void
    {
        if (empty($this->content)) {
            $this->previewImage = null;
            return;
        }

        try {
            $this->generating = true;
            $this->previewImage = $this->makeRenderer()->toSvgDataUri();
        } catch (\Throwable $e) {
            \Log::error('QR preview failed: ' . $e->getMessage());
            $this->previewImage = null;
        } finally {
            $this->generating = false;
        }
    }

    // ------------------------------------------------------------------
    // Downloads — delegates to QrRenderer, saves to disk, redirects
    // ------------------------------------------------------------------

    public function downloadPng(): void
    {
        if (empty($this->content)) {
            return;
        }

        if (!\App\Services\QrRenderer::isGdAvailable()) {
            $this->dispatch('show-toast', type: 'error', message: 'دانلود PNG نیاز به افزونه GD دارد. لطفاً فایل SVG را دانلود کنید.');
            return;
        }

        try {
            $png = $this->makeRenderer()->toPng();
            if (!$png) {
                $this->dispatch('show-toast', type: 'error', message: 'خطا در ساخت تصویر PNG');
                return;
            }

            $safe = preg_replace('/[^a-zA-Z0-9_-]/', '-', $this->title ?: 'qr-code');
            $filename = $safe . '-' . time() . '.png';
            $dir = storage_path('app/qr-downloads');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            file_put_contents($dir . '/' . $filename, $png);

            $this->redirect(route('dashboard.qr.tempDownload', $filename), navigate: false);
        } catch (\Throwable $e) {
            \Log::error('QR PNG download failed: ' . $e->getMessage());
            $this->dispatch('show-toast', type: 'error', message: 'خطا در دانلود PNG');
        }
    }

    public function downloadSvg(): void
    {
        if (empty($this->content)) {
            return;
        }

        try {
            $svg = $this->makeRenderer()->toSvg();

            $safe = preg_replace('/[^a-zA-Z0-9_-]/', '-', $this->title ?: 'qr-code');
            $filename = $safe . '-' . time() . '.svg';
            $dir = storage_path('app/qr-downloads');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            file_put_contents($dir . '/' . $filename, $svg);

            $this->redirect(route('dashboard.qr.tempDownload', $filename), navigate: false);
        } catch (\Throwable $e) {
            \Log::error('QR SVG download failed: ' . $e->getMessage());
            $this->dispatch('show-toast', type: 'error', message: 'خطا در دانلود SVG');
        }
    }

    // ------------------------------------------------------------------
    // Save
    // ------------------------------------------------------------------

    public function save(): void
    {
        $this->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string|max:2048',
        ]);

        $data = [
            'user_id'           => auth()->id(),
            'type'              => $this->type,
            'title'             => $this->title,
            'content'           => $this->content,
            'foreground_color'  => $this->foregroundColor,
            'background_color'  => $this->backgroundColor,
            'gradient_from'     => $this->gradientFrom ?: null,
            'gradient_to'       => $this->gradientTo ?: null,
            'style'             => $this->style,
            'shape'             => $this->shape,
            'pattern'           => $this->pattern,
            'size'              => $this->size,
            'error_correction'  => $this->errorCorrection,
            'eye_style'         => $this->eyeStyle,
            'eye_color'         => $this->eyeColor,
            'frame_style'       => $this->frameStyle,
            'frame_color'       => $this->frameColor,
            'text'              => $this->text ?: null,
            'text_position'     => $this->textPosition,
            'text_font'         => $this->textFont,
            'text_size'         => $this->textSize,
            'text_color'        => $this->textColor,
            'logo_size'         => $this->logoSize,
            'logo_padding'      => $this->logoPadding,
            'margin'            => $this->margin,
            'resolution'        => $this->resolution,
        ];

        if ($this->logo) {
            try {
                $data['logo_path'] = $this->logo->store('qr-logos', 'public');
            } catch (\Throwable) {
            }
        }

        $qrCode = \App\Models\QrCode::create($data);

        if ($qrCode->type === 'dynamic') {
            $qrCode->update(['content' => url("/qr/{$qrCode->unique_code}")]);
        }

        session()->flash('success', 'کد QR با موفقیت ایجاد شد.');
        $this->redirect(route('dashboard.qr.show', $qrCode), navigate: true);
    }

    // ------------------------------------------------------------------
    // Templates
    // ------------------------------------------------------------------

    private function getPresetTemplates(): array
    {
        return [
            'business' => [
                'foregroundColor' => '#1a365d', 'backgroundColor' => '#FFFFFF',
                'style' => 'rounded', 'shape' => 'square', 'eyeStyle' => 'rounded',
                'eyeColor' => '#1a365d', 'margin' => 15,
            ],
            'restaurant' => [
                'foregroundColor' => '#c0392b', 'backgroundColor' => '#FFF5F5',
                'style' => 'dots', 'shape' => 'circle', 'eyeStyle' => 'circle',
                'eyeColor' => '#c0392b', 'margin' => 10,
            ],
            'cafe' => [
                'foregroundColor' => '#6F4E37', 'backgroundColor' => '#F5F0E8',
                'style' => 'rounded', 'shape' => 'square', 'eyeStyle' => 'dots',
                'eyeColor' => '#6F4E37', 'margin' => 12,
            ],
            'event' => [
                'foregroundColor' => '#7c3aed', 'backgroundColor' => '#F5F3FF',
                'style' => 'dots', 'shape' => 'circle', 'eyeStyle' => 'circle',
                'eyeColor' => '#7c3aed', 'margin' => 10,
            ],
            'product' => [
                'foregroundColor' => '#059669', 'backgroundColor' => '#F0FDF4',
                'style' => 'rounded', 'shape' => 'square', 'eyeStyle' => 'rounded',
                'eyeColor' => '#059669', 'margin' => 12,
            ],
            'wedding' => [
                'foregroundColor' => '#b8860b', 'backgroundColor' => '#FFFDF5',
                'style' => 'dots', 'shape' => 'circle', 'eyeStyle' => 'dots',
                'eyeColor' => '#b8860b', 'text' => 'ازدواج مبارک',
                'textColor' => '#b8860b', 'margin' => 15,
            ],
            'tech' => [
                'foregroundColor' => '#6366f1', 'backgroundColor' => '#FFFFFF',
                'style' => 'square', 'shape' => 'square', 'eyeStyle' => 'square',
                'eyeColor' => '#6366f1', 'margin' => 10,
            ],
            'minimal' => [
                'foregroundColor' => '#000000', 'backgroundColor' => '#FFFFFF',
                'style' => 'square', 'shape' => 'square', 'eyeStyle' => 'square',
                'eyeColor' => '#000000', 'margin' => 5,
            ],
            'colorful' => [
                'foregroundColor' => '#e91e63', 'backgroundColor' => '#E3F2FD',
                'gradientFrom' => '#e91e63', 'gradientTo' => '#9c27b0',
                'style' => 'dots', 'shape' => 'circle', 'eyeStyle' => 'circle',
                'eyeColor' => '#e91e63', 'margin' => 10,
            ],
            'dark' => [
                'foregroundColor' => '#FFFFFF', 'backgroundColor' => '#1a1a2e',
                'style' => 'rounded', 'shape' => 'square', 'eyeStyle' => 'rounded',
                'eyeColor' => '#FFFFFF', 'margin' => 15,
            ],
        ];
    }

    public function render()
    {
        return view('livewire.qr-generator');
    }
}
