<?php

declare(strict_types=1);

namespace App\Livewire\Builder;

use App\Config\WidgetConfig;
use App\Models\LandingPageWidget;
use Livewire\Component;

class WidgetLibrary extends Component
{
    public string $search = '';
    public string $activeCategory = 'basic';

    public function getWidgetsProperty(): array
    {
        $widgets = LandingPageWidget::active()->ordered()->get();

        if ($this->search) {
            $widgets = $widgets->filter(fn($w) => str_contains($w->name, $this->search) || str_contains($w->component, $this->search));
        }

        return $widgets->groupBy('category')->toArray();
    }

    public function getCategoriesProperty(): array
    {
        return WidgetConfig::getCategories();
    }

    public function setActiveCategory(string $category): void
    {
        $this->activeCategory = $category;
    }

    public function render()
    {
        return view('livewire.builder.widget-library');
    }
}
