@php
  $full = floor($score);
  $half = ($score - $full) >= 0.5 ? 1 : 0;
  $empty = 5 - $full - $half;
@endphp
<div class="stars" style="display:inline-flex;gap:2px;vertical-align:middle">
  @for($i=0;$i<$full;$i++)
    <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path d="M12 17.3l-6.18 3.25 1.18-6.88-5-4.87 6.91-1 3.09-6.28 3.09 6.28 6.91 1-5 4.87 1.18 6.88z"/></svg>
  @endfor
  @if($half)
    <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path d="M12 2l3.09 6.28 6.91 1-5 4.87 1.18 6.88L12 17.3V2z"/></svg>
  @endif
  @for($i=0;$i<$empty;$i++)
    <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path d="M22 9.28l-7.19-1.04L12 1.5 9.19 8.24 2 9.28l5.2 5.07L5.82 22 12 18.77 18.18 22l-1.38-7.65L22 9.28zm-10 7.03l-4.24 2.23.81-4.52-3.36-3.28 4.64-.67L12 6l2.14 4.57 4.64.67-3.36 3.28.81 4.52L12 16.31z"/></svg>
  @endfor
  @if(isset($count))
    <span style="margin-left:6px">{{ number_format($score,1) }} @if($count>0) ({{ $count }}) @endif</span>
  @endif
</div>
