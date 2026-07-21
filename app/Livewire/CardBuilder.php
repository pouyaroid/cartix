<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Card;
use App\Models\Font;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Spatie\Browsershot\Browsershot;

class CardBuilder extends Component
{
    public ?int $cardId = null;
    public string $title = '';
    public string $description = '';
    public int $canvasWidth = 900;
    public int $canvasHeight = 600;
    public string $backgroundColor = '#ffffff';
    public string $gradientType = 'none';
    public string $gradientColor1 = '#ffffff';
    public string $gradientColor2 = '#000000';
    public int $gradientAngle = 0;
    public string $text = '';
    public string $textColor = '#000000';
    public string $textFont = 'Arial';
    public int $textSize = 24;
    public bool $textBold = false;
    public bool $textItalic = false;
    public bool $textUnderline = false;
    public bool $saving = false;
    public bool $generating = false;
    public string $activeTab = 'text';
    public array $fonts = [];
    public ?string $loadedDesignData = null;
    public string $designDataPayload = '';
    public string $canvasDataUrl = '';

    protected array $rules = [
        'title' => 'required|string|max:255',
        'canvasWidth' => 'required|integer|min:300|max:2000',
        'canvasHeight' => 'required|integer|min:200|max:2000',
        'backgroundColor' => 'required|string|max:7',
    ];

    protected array $messages = [
        'title.required' => 'عنوان کارت الزامی است.',
    ];

    public function mount(?int $cardId = null): void
    {
        $this->fonts = Font::where('is_active', true)->pluck('name', 'name')->toArray();
        $this->fonts = array_unique(array_merge(['Arial', 'Helvetica', 'Times New Roman', 'Georgia', 'Courier New', 'Verdana'], $this->fonts));
        sort($this->fonts);

        if ($cardId) {
            $this->loadCard($cardId);
        }
    }

    public function loadCard(int $cardId): void
    {
        $card = Card::where('id', $cardId)->where('user_id', auth()->id())->first();

        if (!$card) {
            session()->flash('error', 'کارت یافت نشد.');
            return;
        }

        $this->cardId = $card->id;
        $this->title = $card->title;
        $this->description = $card->description ?? '';
        $this->canvasWidth = $card->canvas_width;
        $this->canvasHeight = $card->canvas_height;
        $this->backgroundColor = $card->settings['background_color'] ?? '#ffffff';
        $this->gradientType = $card->settings['gradient_type'] ?? 'none';
        $this->gradientColor1 = $card->settings['gradient_color1'] ?? '#ffffff';
        $this->gradientColor2 = $card->settings['gradient_color2'] ?? '#000000';
        $this->gradientAngle = $card->settings['gradient_angle'] ?? 0;
        $this->loadedDesignData = json_encode($card->design_data);
    }

    public function save(): void
    {
        $this->validate();
        $this->saving = true;

        try {
            $designData = json_decode($this->designDataPayload, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException('طراحی نامعتبر است.');
            }

            $settings = [
                'background_color' => $this->backgroundColor,
                'gradient_type' => $this->gradientType,
                'gradient_color1' => $this->gradientColor1,
                'gradient_color2' => $this->gradientColor2,
                'gradient_angle' => $this->gradientAngle,
            ];

            $card = Card::updateOrCreate(
                ['id' => $this->cardId, 'user_id' => auth()->id()],
                [
                    'title' => $this->title,
                    'description' => $this->description,
                    'canvas_width' => $this->canvasWidth,
                    'canvas_height' => $this->canvasHeight,
                    'design_data' => $designData,
                    'settings' => $settings,
                    'status' => 'active',
                ]
            );

            $this->cardId = $card->id;
            $this->dispatch('show-toast', type: 'success', message: 'کارت با موفقیت ذخیره شد.');
        } catch (\Throwable $e) {
            Log::error('Card save failed: ' . $e->getMessage());
            $this->dispatch('show-toast', type: 'error', message: 'خطا در ذخیره کارت.');
        } finally {
            $this->saving = false;
        }
    }

    public function generateImage(): void
    {
        if (!$this->cardId) {
            $this->save();
        }

        if (!$this->cardId) {
            $this->dispatch('show-toast', type: 'error', message: 'ابتدا کارت را ذخیره کنید.');
            return;
        }

        $this->generating = true;

        try {
            $card = Card::find($this->cardId);
            if (!$card) {
                throw new \RuntimeException('کارت یافت نشد.');
            }

            $dataUrl = $this->canvasDataUrl;
            if (empty($dataUrl)) {
                throw new \RuntimeException('داده canvas دریافت نشد.');
            }

            $width = $card->canvas_width;
            $height = $card->canvas_height;

            $html = <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8">
<style>* { margin:0; padding:0; } body { width:{$width}px; height:{$height}px; overflow:hidden; }
img { width:{$width}px; height:{$height}px; display:block; }</style>
</head>
<body><img src="{$dataUrl}" /></body>
</html>
HTML;

            $tempFile = tempnam(sys_get_temp_dir(), 'card_') . '.png';

            Browsershot::html($html)
                ->windowSize($width, $height)
                ->noSandbox()
                ->disableGpu()
                ->disableExtensions()
                ->timeout(30000)
                ->save($tempFile);

            $card->clearMediaCollection('final-image');
            $card->addMedia($tempFile)
                ->usingName($card->title . '-final')
                ->usingFileName('card-' . $card->id . '-' . time() . '.png')
                ->toMediaCollection('final-image');

            @unlink($tempFile);
            $this->dispatch('show-toast', type: 'success', message: 'تصویر نهایی با موفقیت تولید شد.');
        } catch (\Throwable $e) {
            Log::error('Card image generation failed: ' . $e->getMessage());
            $this->dispatch('show-toast', type: 'error', message: 'خطا در تولید تصویر نهایی.');
        } finally {
            $this->generating = false;
        }
    }

    public function render()
    {
        return view('livewire.card-builder');
    }
}
