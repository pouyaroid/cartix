<?php

namespace App\Console\Commands;

use App\Models\Card;
use App\Models\Template;
use Illuminate\Console\Command;

class FixWeddingCards extends Command
{
    protected $signature = 'cards:fix-wedding';
    protected $description = 'Fix wedding cards to use new wedding templates';

    public function handle()
    {
        // Get the new wedding templates
        $weddingTemplates = Template::where('category', 'wedding')
            ->where('blade_view', 'like', 'templates.wedding.%')
            ->pluck('id')
            ->toArray();

        if (empty($weddingTemplates)) {
            $this->error('No wedding templates found!');
            return 1;
        }

        $this->info('Available wedding template IDs: ' . implode(', ', $weddingTemplates));

        // Get wedding cards that don't have a proper wedding template
        $cards = Card::where('type', 'wedding')
            ->where(function ($query) use ($weddingTemplates) {
                $query->whereNull('template_id')
                    ->orWhereNotIn('template_id', $weddingTemplates);
            })
            ->get();

        $this->info('Cards to fix: ' . $cards->count());

        foreach ($cards as $card) {
            // Assign a random wedding template
            $templateId = $weddingTemplates[array_rand($weddingTemplates)];
            $card->template_id = $templateId;
            $card->save();

            $template = Template::find($templateId);
            $this->line("Card #{$card->id} ({$card->title}): assigned to {$template->name} ({$template->blade_view})");
        }

        $this->info('Done! Fixed ' . $cards->count() . ' cards.');
        return 0;
    }
}
