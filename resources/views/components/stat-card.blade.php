@props([
    'label',
    'value',
    'icon',
    'iconBg' => 'purple', // purple, green, orange, blue, yellow
    'trend' => null,
    'trendType' => 'positive', // positive, negative
    'subtext' => null,
    'badge' => null
])

<div {{ $attributes->merge(['class' => 'stat-card']) }}>
    <div class="stat-card-top">
        <div class="stat-label">{{ $label }}</div>
        <div class="stat-icon-wrap stat-icon-{{ $iconBg }}">
            <i class="{{ $icon }}"></i>
        </div>
    </div>
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="stat-value">{{ $value }}</span>
            @if($badge)
                <span class="badge" style="background-color: #FEF08A; color: #854D0E; font-size: 0.7rem; font-weight: 700;">{{ $badge }}</span>
            @endif
        </div>
        <div class="d-flex align-items-center gap-2">
            @if($trend)
                <span class="stat-trend {{ $trendType }}">
                    <i class="bi {{ $trendType === 'positive' ? 'bi-arrow-up-right' : 'bi-arrow-down-right' }}"></i>
                    {{ $trend }}
                </span>
            @endif
            @if($subtext)
                <span class="stat-subtext">{{ $subtext }}</span>
            @endif
        </div>
    </div>
</div>
