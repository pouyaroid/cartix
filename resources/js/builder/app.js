// Builder Application - Landing Page Editor
// Vanilla JS + Livewire architecture

(function() {
    'use strict';

    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content;
    const API_HEADERS = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN,
        'Accept': 'application/json'
    };

    // --- API Helper ---
    window.builderApi = async function(url, method = 'GET', data = null) {
        const opts = { method, headers: API_HEADERS };
        if (data) opts.body = JSON.stringify(data);
        const res = await fetch(url, opts);
        return res.json();
    };

    // --- Drag & Drop ---
    window.initDragDrop = function(containerId, onDrop) {
        const container = document.getElementById(containerId);
        if (!container) return;

        // Widget items draggable
        document.querySelectorAll('.widget-item[draggable]').forEach(item => {
            item.addEventListener('dragstart', (e) => {
                e.dataTransfer.setData('component', item.dataset.component);
                e.dataTransfer.setData('type', item.dataset.type);
                e.dataTransfer.effectAllowed = 'copy';
            });
        });

        // Container drop zone
        container.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'copy';
            container.classList.add('drag-over');
        });

        container.addEventListener('dragleave', () => {
            container.classList.remove('drag-over');
        });

        container.addEventListener('drop', (e) => {
            e.preventDefault();
            container.classList.remove('drag-over');
            const component = e.dataTransfer.getData('component');
            const type = e.dataTransfer.getData('type');
            if (component && onDrop) onDrop(component, type);
        });
    };

    // --- Sortable Init ---
    window.initSortable = function(containerId, onReorder) {
        const container = document.getElementById(containerId);
        if (!container || typeof Sortable === 'undefined') return;

        return new Sortable(container, {
            handle: '.drag-handle',
            animation: 200,
            ghostClass: 'opacity-50',
            chosenClass: 'border-primary',
            onEnd: () => {
                if (onReorder) onReorder();
            }
        });
    };

    // --- Block Selection ---
    window.initBlockSelection = function(onSelect) {
        document.querySelectorAll('.block-wrapper').forEach(el => {
            el.addEventListener('click', (e) => {
                e.stopPropagation();
                document.querySelectorAll('.block-wrapper').forEach(b => b.classList.remove('selected'));
                el.classList.add('selected');
                if (onSelect) onSelect(el.dataset.blockId);
            });
        });
    };

    // --- Keyboard Shortcuts ---
    window.initKeyboardShortcuts = function(handlers) {
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.key === 'z') {
                e.preventDefault();
                if (handlers.undo) handlers.undo();
            }
            if (e.ctrlKey && e.key === 'y') {
                e.preventDefault();
                if (handlers.redo) handlers.redo();
            }
            if (e.key === 'Delete' && handlers.delete) {
                handlers.delete();
            }
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                if (handlers.save) handlers.save();
            }
            if (e.ctrlKey && e.key === 'd') {
                e.preventDefault();
                if (handlers.duplicate) handlers.duplicate();
            }
        });
    };

    // --- Autosave ---
    window.initAutosave = function(saveCallback, intervalMs = 30000) {
        let timer = null;
        let dirty = false;

        window.markDirty = function() { dirty = true; };

        timer = setInterval(async () => {
            if (!dirty) return;
            dirty = false;
            if (saveCallback) await saveCallback();
        }, intervalMs);

        return () => clearInterval(timer);
    };

    // --- Inline Text Editing ---
    window.initInlineEdit = function(element, onUpdate) {
        if (!element) return;
        element.contentEditable = true;
        element.style.cursor = 'text';

        element.addEventListener('blur', () => {
            if (onUpdate) onUpdate(element.textContent);
        });

        element.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                element.blur();
            }
        });
    };

    // --- Responsive Preview ---
    window.initResponsivePreview = function(frameId) {
        document.querySelectorAll('.responsive-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.responsive-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const frame = document.getElementById(frameId);
                if (frame) {
                    frame.className = 'builder-canvas-frame' +
                        (btn.dataset.mode !== 'desktop' ? ' ' + btn.dataset.mode : '');
                }
            });
        });
    };

    // --- Toast Notification ---
    window.builderToast = function(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top:20px;left:50%;transform:translateX(-50%);z-index:9999;min-width:300px';
        toast.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    };

})();
