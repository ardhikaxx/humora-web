@props([
    'icon' => 'folder-open',
    'title' => 'Belum Ada Data',
    'subtitle' => 'Data tidak ditemukan atau belum pernah ditambahkan.',
    'actionUrl' => null,
    'actionText' => null,
    'actionIcon' => 'plus',
    'actionTarget' => null,
    'resetUrl' => null,
    'resetText' => 'Reset Filter',
    'colspan' => null,
    'compact' => false,
])

@if ($colspan)
<tr>
    <td colspan="{{ $colspan }}" class="p-0 border-0">
@endif

<div class="humora-empty-state {{ $compact ? 'compact' : '' }}">
    <div class="humora-empty-icon-box">
        <i data-lucide="{{ $icon }}"></i>
    </div>
    <div class="humora-empty-title">{{ $title }}</div>
    <div class="humora-empty-subtitle">{{ $subtitle }}</div>

    @if ($actionUrl || $actionTarget || $resetUrl)
    <div class="humora-empty-actions">
        @if ($actionUrl && $actionText)
        <a href="{{ $actionUrl }}" class="btn btn-sm btn-humora-primary">
            @if ($actionIcon)
                <i data-lucide="{{ $actionIcon }}" style="width: 14px; height: 14px;"></i>
            @endif
            <span>{{ $actionText }}</span>
        </a>
        @elseif ($actionTarget && $actionText)
        <button type="button" class="btn btn-sm btn-humora-primary" data-bs-toggle="modal" data-bs-target="{{ $actionTarget }}">
            @if ($actionIcon)
                <i data-lucide="{{ $actionIcon }}" style="width: 14px; height: 14px;"></i>
            @endif
            <span>{{ $actionText }}</span>
        </button>
        @endif

        @if ($resetUrl)
        <a href="{{ $resetUrl }}" class="btn btn-sm btn-outline-secondary">
            <i data-lucide="rotate-ccw" style="width: 14px; height: 14px;"></i>
            <span>{{ $resetText }}</span>
        </a>
        @endif
    </div>
    @endif
</div>

@if ($colspan)
    </td>
</tr>
@endif
