@php
    $id = $id ?? 'confirm-'.\Illuminate\Support\Str::random(8);
    $title = $title ?? 'Yakin ingin melanjutkan?';
    $message = $message ?? 'Tindakan ini tidak dapat dibatalkan.';
    $confirmText = $confirmText ?? 'Ya, lanjutkan';
    $cancelText = $cancelText ?? 'Batal';
    $danger = $danger ?? false;
    $action = $action ?? null;
    $method = strtoupper($method ?? 'POST');
    $confirmClass = $danger
        ? 'bg-error text-on-error hover:bg-error/90'
        : 'bg-primary text-on-primary hover:bg-primary-container';
    $iconClass = $danger ? 'bg-error-container text-on-error-container' : 'bg-primary-container text-on-primary-container';
    $icon = $icon ?? ($danger ? 'warning' : 'help');
@endphp

<div id="{{ $id }}" data-alert-modal class="fixed inset-0 z-[60] hidden items-center justify-center p-4 sm:p-6"
    role="dialog" aria-modal="true" aria-labelledby="{{ $id }}-title" aria-describedby="{{ $id }}-desc">
    <div data-alert-modal-overlay class="absolute inset-0"></div>

    <div data-alert-modal-panel
        class="relative w-full max-w-md bg-surface-container-lowest rounded-3xl border border-surface-variant/50 shadow-xl p-6 sm:p-7">
        <div class="flex items-start gap-4">
            <div class="w-11 h-11 shrink-0 rounded-2xl flex items-center justify-center {{ $iconClass }}">
                <span class="material-symbols-outlined text-2xl leading-none">{{ $icon }}</span>
            </div>

            <div class="min-w-0 flex-1">
                <h2 id="{{ $id }}-title" class="text-headline-sm font-bold text-on-surface leading-snug">{{ $title }}</h2>
                <p id="{{ $id }}-desc" class="mt-1.5 text-label-md text-on-surface-variant leading-relaxed">{{ $message }}</p>
            </div>
        </div>

        <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-2.5">
            <button type="button" data-alert-modal-close
                class="w-full sm:w-auto rounded-2xl bg-surface-container px-5 py-2.5 text-label-lg font-semibold text-on-surface-variant transition-colors hover:bg-surface-container-high">
                {{ $cancelText }}
            </button>

            @if($action)
            <form method="POST" action="{{ $action }}" class="w-full sm:w-auto">
                @csrf
                @if($method !== 'POST')
                @method($method)
                @endif
                <button type="submit" data-alert-modal-confirm
                    class="w-full rounded-2xl px-5 py-2.5 text-label-lg font-bold text-white transition-colors {{ $confirmClass }}">
                    {{ $confirmText }}
                </button>
            </form>
            @else
            <button type="button" data-alert-modal-confirm
                class="w-full sm:w-auto rounded-2xl px-5 py-2.5 text-label-lg font-bold text-white transition-colors {{ $confirmClass }}">
                {{ $confirmText }}
            </button>
            @endif
        </div>
    </div>
</div>
