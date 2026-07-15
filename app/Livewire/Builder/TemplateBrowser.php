<?php

declare(strict_types=1);

namespace App\Livewire\Builder;

use App\Models\LandingPage;
use App\Models\LandingPageTemplate;
use App\Services\LandingPageService;
use Livewire\Component;

class TemplateBrowser extends Component
{
    public LandingPage $page;
    public string $category = '';
    public $templates = [];

    public function mount(LandingPage $page): void
    {
        $this->page = $page;
        $this->loadTemplates();
    }

    public function loadTemplates(): void
    {
        $query = LandingPageTemplate::active();

        if ($this->category) {
            $query->byCategory($this->category);
        }

        $this->templates = $query->ordered()->get()->toArray();
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
        $this->loadTemplates();
    }

    public function apply(int $templateId): void
    {
        $template = LandingPageTemplate::findOrFail($templateId);
        $service = app(LandingPageService::class);
        $service->applyTemplate($this->page, $template);

        $this->dispatch('templateApplied');
    }

    public function render()
    {
        return view('livewire.builder.template-browser');
    }
}
