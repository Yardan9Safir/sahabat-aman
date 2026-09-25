@php
    $tones = [
        'success' => [
            'wrap' => 'bg-primary-fixed/70 text-on-primary-fixed border-primary/30',
            'icon' => 'check_circle',
        ],
        'failed' => [
            'wrap' => 'bg-error-container/70 text-on-error-container border-error/30',
            'icon' => 'error',
        ],
        'pending' => [
            'wrap' => 'bg-secondary-fixed/70 text-on-secondary-fixed border-secondary/30',
            'icon' => 'schedule',
        ],
        'info' => [
            'wrap' => 'bg-surface-container text-on-surface-variant border-outline-variant/60',
            'icon' => 'info',
        ],
    ];

    $config = $tones[$tone ?? 'pending'] ?? $tones['pending'];
    $dismissible = $dismissible ?? true;
    $autohide = $autohide ?? 0;
    $class = $class ?? '';
@endphp

<div data-alert @if($autohide) data-alert-autohide="{{ $autohide }}" @endif role="alert"
    class="flex items-start gap-3 rounded-2xl border p-3.5 transition-opacity duration-300 {{ $config['wrap'] }} {{ $class }}">
    <span class="material-symbols-outlined text-xl leading-none mt-0.5 shrink-0">{{ $config['icon'] }}</span>

    <div class="min-w-0 flex-1">
        @if(!empty($title))
        <p class="text-label-lg font-bold leading-snug">{{ $title }}</p>
        <p class="text-label-md leading-snug opacity-90 mt-0.5">{{ $message }}</p>
        @else
        <p class="text-label-md font-semibold leading-snug">{{ $message }}</p>
        @endif
    </div>

    @if($dismissible)
    <button type="button" data-alert-dismiss
        class="-m-1.5 p-1.5 rounded-lg shrink-0 opacity-70 transition"
        aria-label="Tutup pesan">
        <span class="material-symbols-outlined text-lg leading-none">close</span>
    </button>
    @endif
</div>
