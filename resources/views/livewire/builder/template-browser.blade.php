<div>
    <div class="p-3">
        <h6 class="fw-bold mb-3" style="font-size:0.85rem">قالب‌ها</h6>

        <div class="mb-3">
            <select class="form-select form-select-sm" wire:model.live="category">
                <option value="">همه دسته‌ها</option>
                <option value="restaurant">رستوران</option>
                <option value="cafe">کافه</option>
                <option value="business">شرکتی</option>
                <option value="agency">آژانس</option>
                <option value="portfolio">پورتفولیو</option>
                <option value="wedding">عروسی</option>
                <option value="event">رویداد</option>
                <option value="coming-soon">به‌زودی</option>
            </select>
        </div>

        <div class="row g-2">
            @foreach($templates as $template)
                <div class="col-6">
                    <div class="card card-body p-2 text-center" style="cursor:pointer" wire:click="apply({{ $template['id'] }})"
                         wire:confirm="آیا از اعمال این قالب مطمئنید؟ تمام بلوک‌های فعلی جایگزین می‌شوند.">
                        <div class="fw-medium small">{{ $template['name'] }}</div>
                        <div class="text-muted" style="font-size:0.7rem">{{ $template['category'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
