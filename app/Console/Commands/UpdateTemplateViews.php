<?php

namespace App\Console\Commands;

use App\Models\Template;
use Illuminate\Console\Command;

class UpdateTemplateViews extends Command
{
    protected $signature = 'templates:update-views';
    protected $description = 'Update template blade_view paths';

    public function handle()
    {
        $templates = [
            'wedding-luxury' => 'templates.wedding.luxury',
            'wedding-minimal' => 'templates.wedding.minimal',
            'wedding-elegant' => 'templates.wedding.elegant',
            'wedding-floral' => 'templates.wedding.floral',
            'wedding-royal' => 'templates.wedding.royal',
            'wedding-vintage' => 'templates.wedding.vintage',
            'wedding-romantic' => 'templates.wedding.romantic',
            'wedding-modern' => 'templates.wedding.modern',
            'wedding-persian' => 'templates.wedding.persian',
            'wedding-garden' => 'templates.wedding.garden',
            'wedding-boho' => 'templates.wedding.boho',
            'wedding-dark-luxury' => 'templates.wedding.dark-luxury',
            'wedding-gold-theme' => 'templates.wedding.gold-theme',
            'wedding-watercolor' => 'templates.wedding.watercolor',
            'wedding-glassmorphism' => 'templates.wedding.glassmorphism',
            'wedding-premium-animated' => 'templates.wedding.premium-animated',
            'wedding-silk' => 'templates.wedding.silk',
            'wedding-starry-night' => 'templates.wedding.starry-night',
            'wedding-rose-gold' => 'templates.wedding.rose-gold',
            'wedding-art-deco' => 'templates.wedding.art-deco',
        ];

        $count = 0;
        foreach ($templates as $slug => $bladeView) {
            $updated = Template::where('slug', $slug)->update(['blade_view' => $bladeView]);
            if ($updated) {
                $count++;
                $this->info("Updated: {$slug} -> {$bladeView}");
            }
        }

        $this->info("Updated {$count} templates successfully.");
        return 0;
    }
}
