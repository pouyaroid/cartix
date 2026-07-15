<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Events\CardViewed;
use App\Http\Controllers\Controller;
use App\Models\Card;

class PublicCardController extends Controller
{
    public function show(string $slug)
    {
        $card = Card::with([
            'sections' => fn($q) => $q->orderBy('sort_order'),
            'socialLinks',
            'galleryItems',
            'products',
            'services',
            'testimonials',
            'faqs',
            'qrCodes' => fn($q) => $q->where('is_active', true),
            'template',
            'user',
        ])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        event(new CardViewed($card));

        return view($this->resolveView($card), ['card' => $card]);
    }

    private function resolveView(Card $card): string
    {
        // Check if card has a template with a blade_view
        if ($card->template && $card->template->blade_view) {
            // Try the template's blade_view directly
            if (view()->exists($card->template->blade_view)) {
                return $card->template->blade_view;
            }
            // Try with templates. prefix
            if (view()->exists("templates.{$card->template->blade_view}")) {
                return "templates.{$card->template->blade_view}";
            }
            // Try with templates. prefix and .show suffix
            if (view()->exists("templates.{$card->template->blade_view}.show")) {
                return "templates.{$card->template->blade_view}.show";
            }
        }

        $typeViews = [
            'business' => 'cards.business.show',
            'wedding' => 'cards.wedding.show',
            'birthday' => 'cards.wedding.show',
            'restaurant' => 'cards.restaurant.show',
            'doctor' => 'cards.doctor.show',
            'lawyer' => 'cards.doctor.show',
            'event' => 'cards.event.show',
            'conference' => 'cards.event.show',
            'corporate' => 'cards.business.show',
            'portfolio' => 'cards.portfolio.show',
            'resume' => 'cards.portfolio.show',
            'real_estate' => 'cards.business.show',
        ];

        return $typeViews[$card->type] ?? 'cards.business.show';
    }
}
