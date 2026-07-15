<?php

namespace App\Console\Commands;

use App\Models\Template;
use Illuminate\Console\Command;

class CheckTemplates extends Command
{
    protected $signature = 'templates:check';
    protected $description = 'Check template blade_view values';

    public function handle()
    {
        $templates = Template::where('category', 'wedding')
            ->select('id', 'name', 'slug', 'blade_view')
            ->get();

        $this->info('Wedding Templates:');
        $this->table(['ID', 'Name', 'Slug', 'Blade View'], $templates->toArray());

        // Check if views exist
        $this->info("\nChecking view existence:");
        foreach ($templates as $template) {
            $exists = view()->exists($template->blade_view);
            $status = $exists ? '✓ EXISTS' : '✗ MISSING';
            $this->line("{$template->blade_view}: {$status}");
        }

        return 0;
    }
}
