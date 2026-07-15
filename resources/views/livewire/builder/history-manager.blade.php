<div>
    <div class="p-3">
        <h6 class="fw-bold mb-3" style="font-size:0.85rem">تاریخچه</h6>

        @if(count($versions) > 0)
            <div class="list-group list-group-flush">
                @foreach($versions as $version)
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                        <div>
                            <div class="small fw-medium">نسخه {{ $version['version'] }}</div>
                            <div class="text-muted" style="font-size:0.7rem">
                                {{ $version['label'] ?? 'دستی' }}
                                @if($version['creator'] ?? null)
                                    - {{ $version['creator']['name'] }}
                                @endif
                            </div>
                            <div class="text-muted" style="font-size:0.65rem">
                                {{ \Carbon\Carbon::parse($version['created_at'])->diffForHumans() }}
                            </div>
                        </div>
                        <button class="btn btn-sm btn-outline-primary" wire:click="restore({{ $version['id'] }})"
                                wire:confirm="آیا از بازگردانی به این نسخه مطمئنید؟">
                            <i class="bi bi-arrow-counterclockwise" style="font-size:0.7rem"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-muted py-3">
                <p class="small">تاریخچه‌ای وجود ندارد</p>
            </div>
        @endif
    </div>
</div>
