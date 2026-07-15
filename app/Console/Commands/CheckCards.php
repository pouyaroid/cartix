<?php

namespace App\Console\Commands;

use App\Models\Card;
use Illuminate\Console\Command;

class CheckCards extends Command
{
    protected $signature = 'cards:check';
    protected $description = 'Check cards and their template assignments';

    public function handle()
    {
        $cards = Card::where('type', 'wedding')
            ->select('id', 'title', 'template_id', 'type', 'slug')
            ->get();

        $this->info('Wedding Cards:');
        $this->table(['ID', 'Title', 'Template ID', 'Type', 'Slug'], $cards->toArray());

        // Check template assignments
        $this->info("\nTemplate Assignments:");
        foreach ($cards as $card) {
            $templateInfo = $card->template ? "{$card->template->name} ({$card->template->blade_view})" : 'NO TEMPLATE';
            $this->line("Card #{$card->id} ({$card->title}): {$templateInfo}");
        }

        return 0;
    }
}
