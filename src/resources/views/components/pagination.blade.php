{{-- resources/views/components/pagination.blade.php --}}
@php
    use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
@endphp

@if (($paginator ?? null) instanceof PaginatorContract && $paginator->hasPages())
<nav class="pagination" role="navigation" aria-label="Pagination">

    {{-- Prev --}}
    @if ($paginator->onFirstPage())
        <span class="disabled" aria-disabled="true">‹</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev">‹</a>
    @endif

    {{-- Page Numbers（LengthAware のときは自前描画 / それ以外はlinksに委譲） --}}
    @if ($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator)
        @php
            $current = $paginator->currentPage();
            $last    = $paginator->lastPage();
            $window  = 1; // 現在ページの両側に表示する数（必要なら調整）
            // 表示したいページ番号の集合（1, 最終, そして現在の前後）
            $pages = [1];
            for ($i = max(2, $current - $window); $i <= min($last - 1, $current + $window); $i++) { $pages[] = $i; }
            if ($last > 1) { $pages[] = $last; }
            $pages = array_values(array_unique($pages)); sort($pages);
            $prevShown = null;
        @endphp

        @foreach ($pages as $page)
            @if (!is_null($prevShown) && $page - $prevShown > 1)
                <span class="disabled">…</span>
            @endif

            @if ($page == $current)
                <span class="current" aria-current="page">{{ $page }}</span>
            @else
                <a href="{{ $paginator->url($page) }}">{{ $page }}</a>
            @endif

            @php $prevShown = $page; @endphp
        @endforeach
    @else
        {{-- SimplePaginator 等のフォールバック（Laravel標準に委譲） --}}
        {{ $paginator->onEachSide(1)->links() }}
    @endif

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next">›</a>
    @else
        <span class="disabled" aria-disabled="true">›</span>
    @endif
</nav>
@endif
