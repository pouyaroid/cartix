<?php

namespace App\Console\Commands;

use App\Models\Card;
use Illuminate\Console\Command;

class DebugCardView extends Command
{
    protected $signature = 'debug:card-view {cardId?}';
    protected $description = 'Debug card view resolution';

    public function handle(int $cardId = null)
    {
        if ($cardId) {
            $cards = Card::where('id', $cardId)->with('template')->get();
        } else {
            $cards = Card::where('type', 'wedding')->with('template')->get();
        }

        foreach ($cards as $card) {
            $this->line("Card #{$card->id}: {$card->title}");
            $this->line("  Type: {$card->type}");

            if ($card->template) {
                $this->line("  Template: {$card->template->name}");
                $this->line("  Blade View: {$card->template->blade_view}");

                $bladeView = $card->template->blade_view;
                $exists1 = view()->exists($bladeView);
                $exists2 = view()->exists("templates.{$bladeView}");
                $exists3 = view()->exists("templates.{$bladeView}.show");

                $this->line("  View exists ({$bladeView}): " . ($exists1 ? 'YES' : 'NO'));
                $this->line("  View exists (templates.{$bladeView}): " . ($exists2 ? 'YES' : 'NO'));
                $this->line("  View exists (templates.{$bladeView}.show): " . ($exists3 ? 'YES' : 'NO'));
            } else {
                $this->line("  Template: NONE");
            }

            $this->line("");
        }

        return 0;
    }
}
