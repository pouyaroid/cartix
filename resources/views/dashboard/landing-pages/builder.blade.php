<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ویرایشگر: {{ $page->title }} | CardX</title>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Vazirmatn',system-ui,sans-serif;overflow:hidden;height:100vh;background:#f8f9fa}

        /* === Sidebar Form Controls === */
        .style-field{margin-bottom:10px}
        .style-label{display:block;font-size:11px;font-weight:500;color:#6b7280;margin-bottom:3px}
        .style-input{width:100%;padding:7px 10px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;color:#111827;font-size:12px;font-family:inherit;transition:all .15s;outline:none}
        .style-input:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.1)}
        .style-select{width:100%;padding:7px 10px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;color:#111827;font-size:12px;font-family:inherit;cursor:pointer;outline:none}
        .style-select:focus{border-color:#6366f1}
        .color-picker{width:34px;height:34px;padding:2px;background:#fff;border:1px solid #e5e7eb;border-radius:6px;cursor:pointer;flex-shrink:0}

        /* === Toolbar === */
        .toolbar-btn:hover{background:#f3f4f6!important;color:#111827!important;border-color:#d1d5db!important}
        .toolbar-btn.active{background:#eef2ff!important;color:#4f46e5!important;border-color:#c7d2fe!important}
        .sidebar-tab:hover{background:#f9fafb!important}

        /* === Widget Items === */
        .widget-item:hover{border-color:#6366f1!important;background:#eef2ff!important}
        .widget-item:active{transform:scale(0.97)}

        /* === Block Selection === */
        .block-wrapper{position:relative;transition:border-color .15s,box-shadow .15s;cursor:pointer}
        .block-wrapper:hover{border-color:rgba(99,102,241,.35)!important}
        .block-wrapper.selected{border-color:#6366f1!important;box-shadow:0 0 0 3px rgba(99,102,241,.1)!important}
        .block-toolbar{display:none;position:absolute;top:-36px;left:50%;transform:translateX(-50%);gap:2px;z-index:50;background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:4px;box-shadow:0 2px 8px rgba(0,0,0,.08)}
        .block-wrapper.selected .block-toolbar{display:flex}
        .block-badge{position:absolute;top:-8px;right:8px;font-size:9px;padding:1px 6px;border-radius:3px;background:#6366f1;color:#fff;font-weight:500;opacity:0;transition:opacity .15s;pointer-events:none}
        .block-wrapper.selected .block-badge{opacity:1}
        .block-action-btn{width:28px;height:28px;display:flex;align-items:center;justify-content:center;background:transparent;border:none;border-radius:4px;color:#6b7280;cursor:pointer;font-size:12px;transition:all .15s}
        .block-action-btn:hover{background:#f3f4f6;color:#111827}
        .block-action-btn.danger:hover{background:#fef2f2;color:#dc2626}

        /* === SortableJS === */
        .sortable-ghost{opacity:.4;background:#eef2ff;border:2px dashed #6366f1!important;border-radius:4px}
        .sortable-chosen{box-shadow:0 4px 12px rgba(0,0,0,.15)}

        /* === Scrollbar === */
        ::-webkit-scrollbar{width:6px;height:6px}
        ::-webkit-scrollbar-track{background:transparent}
        ::-webkit-scrollbar-thumb{background:#d1d5db;border-radius:3px}
        ::-webkit-scrollbar-thumb:hover{background:#9ca3af}

        /* === Livewire Loading === */
        [wire\:loading]{opacity:.5;pointer-events:none}
    </style>
    @livewireStyles
</head>
<body>
    @livewire('page-builder', ['page' => $page])
    @livewireScripts

    <script>
    // SortableJS initialization
    function initSortable() {
        const container = document.getElementById('blocksContainer');
        if (!container || typeof Sortable === 'undefined') return;

        // Destroy existing instance if any
        if (container._sortable) {
            container._sortable.destroy();
        }

        container._sortable = Sortable.create(container, {
            handle: '.sortable-handle',
            animation: 200,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function(evt) {
                const items = Array.from(container.children).map(el => el.dataset.blockId);
                Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).call('handleReorder', JSON.stringify(items));
            }
        });
    }

    document.addEventListener('DOMContentLoaded', initSortable);
    Livewire.on('blockUpdated', () => setTimeout(initSortable, 100));
    Livewire.on('blockReordered', () => setTimeout(initSortable, 100));

    // Autosave every 30 seconds if dirty
    setInterval(() => {
        const el = document.querySelector('[wire\\:id]');
        if (el && el.__livewire && el.__livewire.serverMemo.data.isDirty) {
            Livewire.find(el.getAttribute('wire:id')).call('save');
        }
    }, 30000);

    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).call('save');
        }
        if ((e.ctrlKey || e.metaKey) && e.key === 'z' && !e.shiftKey) {
            e.preventDefault();
            Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).call('undo');
        }
        if ((e.ctrlKey || e.metaKey) && (e.key === 'y' || (e.key === 'z' && e.shiftKey))) {
            e.preventDefault();
            Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).call('redo');
        }
        if (e.key === 'Delete' || e.key === 'Backspace') {
            const active = document.activeElement;
            if (active && (active.tagName === 'INPUT' || active.tagName === 'TEXTAREA' || active.tagName === 'SELECT')) return;
            const selected = document.querySelector('.block-wrapper.selected');
            if (selected) {
                Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).call('deleteBlock', parseInt(selected.dataset.blockId));
            }
        }
    });
    </script>
</body>
</html>
