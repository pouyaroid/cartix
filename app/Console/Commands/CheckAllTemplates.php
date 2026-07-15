<?php

namespace App\Console\Commands;

use App\Models\Template;
use Illuminate\Console\Command;

class CheckAllTemplates extends Command
{
    protected $signature = 'templates:check-all';
    protected $description = 'Check all templates';

    public function handle()
    {
        $templates = Template::select('id', 'name', 'slug', 'blade_view', 'category', 'is_active')
            ->orderBy('id')
            ->get();

        $this->info('All Templates:');
        $this->table(['ID', 'Name', 'Slug', 'Blade View', 'Category', 'Active'], $templates->toArray());

        return 0;
    }
}
