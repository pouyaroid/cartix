<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Enums\CardTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\CardSection;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CardController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $cards = Card::where('user_id', auth()->id())
            ->with('template')
            ->latest()
            ->paginate(12);

        return view('dashboard.cards.index', compact('cards'));
    }

    public function create()
    {
        $types = CardTypeEnum::cases();
        $templates = Template::active()->get();

        return view('dashboard.cards.create', compact('types', 'templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => ['required', 'string', Rule::in(array_map(fn($e) => $e->value, CardTypeEnum::cases()))],
            'template_id' => 'nullable|exists:templates,id',
            'description' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);

        $originalSlug = $validated['slug'];
        $count = 1;
        while (Card::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count;
            $count++;
        }

        $card = Card::create($validated);

        $defaultSections = [
            ['type' => 'contact', 'title' => 'اطلاعات تماس', 'sort_order' => 0],
            ['type' => 'social', 'title' => 'شبکه‌های اجتماعی', 'sort_order' => 1],
        ];

        foreach ($defaultSections as $section) {
            CardSection::create(array_merge($section, ['card_id' => $card->id]));
        }

        return redirect()->route('dashboard.cards.builder', $card)
            ->with('success', 'کارت با موفقیت ایجاد شد. حالا می‌توانید آن را سفارشی کنید.');
    }

    public function edit(Card $card)
    {
        $this->authorize('update', $card);

        $card->load(['sections', 'socialLinks', 'galleryItems', 'products', 'services', 'testimonials', 'faqs']);
        $templates = Template::active()->get();

        return view('dashboard.cards.edit', compact('card', 'templates'));
    }

    public function update(Request $request, Card $card)
    {
        $this->authorize('update', $card);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'template_id' => 'nullable|exists:templates,id',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|max:255',
            'website' => 'nullable|max:500',
            'address' => 'nullable|string',
            'theme_color' => 'nullable|string|max:7',
            'font_family' => 'nullable|string|max:100',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'settings' => 'nullable|array',
        ]);

        $card->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'کارت با موفقیت بروزرسانی شد.');
    }

    public function destroy(Card $card)
    {
        $this->authorize('delete', $card);
        $card->delete();

        return redirect()->route('dashboard.cards.index')
            ->with('success', 'کارت با موفقیت حذف شد.');
    }

    public function publish(Card $card)
    {
        $this->authorize('publish', $card);

        $card->update([
            'is_published' => !$card->is_published,
            'published_at' => $card->is_published ? null : now(),
        ]);

        $status = $card->is_published ? 'منتشر شد' : 'پیش‌نویس شد';

        return back()->with('success', "کارت {$status}.");
    }

    public function builder(Card $card)
    {
        $this->authorize('update', $card);

        $card->load(['sections' => function ($q) {
            $q->orderBy('sort_order');
        }, 'socialLinks', 'galleryItems', 'products', 'services', 'testimonials', 'faqs']);

        return view('dashboard.cards.builder.index', compact('card'));
    }

    public function addSection(Request $request, Card $card)
    {
        $this->authorize('update', $card);

        $validated = $request->validate([
            'type' => 'required|string|in:gallery,video,audio,document,social,contact,products,services,testimonials,faq,map,custom,timeline,countdown,rsvp',
            'title' => 'nullable|string|max:255',
        ]);

        $maxOrder = $card->sections()->max('sort_order') ?? -1;

        $section = CardSection::create([
            'card_id' => $card->id,
            'type' => $validated['type'],
            'title' => $validated['title'] ?? '',
            'sort_order' => $maxOrder + 1,
        ]);

        return response()->json(['success' => true, 'section' => $section]);
    }

    public function updateSection(Request $request, Card $card, CardSection $section)
    {
        $this->authorize('update', $card);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'is_visible' => 'nullable|boolean',
            'settings' => 'nullable|array',
        ]);

        $section->update($validated);

        return response()->json(['success' => true, 'section' => $section->fresh()]);
    }

    public function deleteSection(Card $card, CardSection $section)
    {
        $this->authorize('update', $card);
        $section->delete();

        return response()->json(['success' => true]);
    }

    public function reorderSections(Request $request, Card $card)
    {
        $this->authorize('update', $card);

        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:card_sections,id',
        ]);

        foreach ($validated['order'] as $index => $sectionId) {
            CardSection::where('id', $sectionId)->where('card_id', $card->id)
                ->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    public function listItems(Card $card, CardSection $section)
    {
        $this->authorize('update', $card);

        $type = $section->type;
        $modelMap = [
            'services' => \App\Models\CardService::class,
            'products' => \App\Models\CardProduct::class,
            'testimonials' => \App\Models\CardTestimonial::class,
            'faq' => \App\Models\CardFaq::class,
            'gallery' => \App\Models\CardGalleryItem::class,
            'timeline' => \App\Models\CardService::class,
        ];

        $model = $modelMap[$type] ?? null;
        if (!$model) {
            return response()->json(['items' => []]);
        }

        $items = $model::where('card_id', $card->id)->orderBy('sort_order')->get();

        return response()->json(['items' => $items]);
    }

    public function addItem(Request $request, Card $card, CardSection $section)
    {
        $this->authorize('update', $card);

        $type = $request->input('type');
        $modelMap = [
            'services' => \App\Models\CardService::class,
            'products' => \App\Models\CardProduct::class,
            'testimonials' => \App\Models\CardTestimonial::class,
            'faq' => \App\Models\CardFaq::class,
            'gallery' => \App\Models\CardGalleryItem::class,
            'timeline' => \App\Models\CardService::class,
        ];

        $model = $modelMap[$type] ?? null;
        if (!$model) {
            return response()->json(['success' => false, 'error' => 'نوع نامعتبر'], 400);
        }

        $maxOrder = $section->items()->max('sort_order') ?? -1;

        $data = $request->except(['type', '_token']);
        $data['card_id'] = $card->id;
        $data['sort_order'] = $maxOrder + 1;

        if ($type === 'timeline') {
            $data['name'] = $data['name'] ?? $data['title'] ?? '';
            $data['description'] = $data['description'] ?? $data['content'] ?? '';
        }

        $model::create($data);

        return response()->json(['success' => true]);
    }

    public function deleteItem(Card $card, CardSection $section, int $item)
    {
        $this->authorize('update', $card);

        $type = $section->type;
        $modelMap = [
            'services' => \App\Models\CardService::class,
            'products' => \App\Models\CardProduct::class,
            'testimonials' => \App\Models\CardTestimonial::class,
            'faq' => \App\Models\CardFaq::class,
            'gallery' => \App\Models\CardGalleryItem::class,
            'timeline' => \App\Models\CardService::class,
        ];

        $model = $modelMap[$type] ?? null;
        if ($model) {
            $model::where('id', $item)->where('card_id', $card->id)->delete();
        }

        return response()->json(['success' => true]);
    }

    public function uploadImage(Request $request, Card $card, string $field)
    {
        $this->authorize('update', $card);

        $request->validate(['file' => 'required|file|max:5120|mimes:jpg,jpeg,png,gif,webp']);

        $file = $request->file('file');
        $allowedFields = ['logo', 'profile_image', 'cover_image'];
        $fieldName = in_array($field, ['logo', 'profile', 'cover']) ? ($field === 'profile' ? 'profile_image' : ($field === 'cover' ? 'cover_image' : $field)) : null;

        if (!$fieldName || !in_array($fieldName, $allowedFields)) {
            return response()->json(['success' => false], 400);
        }

        $path = $file->store("cards/{$card->id}", 'public');
        $card->update([$fieldName => $path]);

        return response()->json(['success' => true, 'path' => asset('storage/' . $path)]);
    }
}
