@props(['active' => false, 'badge' => null])

@php
  $classes = $active ?? false ? 'flex items-center justify-between px-3 py-2.5 mb-1 bg-white text-slate-800 rounded-lg font-medium transition-all' : 'flex items-center justify-between px-3 py-2.5 mb-1 text-slate-300 hover:bg-slate-700 hover:text-white rounded-lg transition-all';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
  <div class="flex items-center space-x-3">
    @isset($icon)
      <div>
        {{ $icon }}
      </div>
    @endisset
    <span class="text-sm">{{ $slot }}</span>
  </div>

  @if ($badge)
    <span class="bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
      {{ $badge }}
    </span>
  @endif
</a>
