@php
$showSearch = $showSearch ?? request()->routeIs('shops.index');
if ($showSearch) {
    $areas  = $areas  ?? \App\Models\Area::query()->orderBy('id')->get();
    $genres = $genres ?? \App\Models\Genre::query()->orderBy('id')->get();
}
$role = auth()->user()->role ?? null;

$isBackoffice = request()->routeIs('admin.*')
    || request()->routeIs('owner.*')
    || request()->routeIs('staff.*');
@endphp

<header class="site-header">
    <div class="site-header__inner">
        @unless($isBackoffice)
            <button type="button" class="hamburger" data-menu-toggle aria-label="メニューを開く">
                <img class="hamburger-img" src="{{ asset('images/kkrn_icon_menu_6.png') }}" alt="">
            </button>
        @endunless

        <a href="{{ route('shops.index') }}" class="brand">Rese</a>

        @if($showSearch && ! $isBackoffice)
            <form class="header-search" action="{{ route('shops.index') }}" method="get" role="search" aria-label="店舗検索">
                <div class="header-search__item">
                    <select name="area" class="hsel" aria-label="エリア">
                        <option value="">All area</option>
                        @foreach($areas as $a)
                            <option value="{{ $a->id }}" @selected(request('area') == $a->id)>{{ $a->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="header-search__item">
                    <select name="genre" class="hsel" aria-label="ジャンル">
                        <option value="">All genre</option>
                        @foreach($genres as $g)
                            <option value="{{ $g->id }}" @selected(request('genre') == $g->id)>{{ $g->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="header-search__item header-search__item--input">
                    <button class="header-search__icon" type="submit" aria-label="検索する">
                        <img src="{{ asset('images/search.png') }}" alt="">
                    </button>
                    <input
                        type="text"
                        name="keyword"
                        class="hinput"
                        value="{{ request('keyword') }}"
                        placeholder="Search …"
                        aria-label="店名で検索"
                    >
                </div>
            </form>
        @endif
    </div>

    @unless($isBackoffice)
        <div class="menu-overlay" id="menuOverlay" data-open="false" aria-hidden="true">
            <button class="menu-overlay__close" type="button" data-menu-close aria-label="閉じる">×</button>
            <nav class="menu-overlay__body" aria-label="メニュー">
                <a class="menu-link" href="{{ route('shops.index') }}">Home</a>

                @auth
                    @if(in_array($role, ['owner','admin'], true))
                        <a class="menu-link" href="{{ route('staff.dashboard') }}">スタッフダッシュボード</a>
                        @if($role === 'admin')
                            <a class="menu-link" href="{{ route('admin.dashboard') }}">管理者ダッシュボード</a>
                        @endif
                    @endif

                    <a class="menu-link" href="{{ route('mypage.index') }}">Mypage</a>

                    @if(Route::has('online.index'))
                        <a class="menu-link" href="{{ route('online.index') }}">Online</a>
                    @endif

                    @if(in_array($role, ['owner','admin'], true))
                        <form method="post" action="{{ route('staff.logout') }}">
                            @csrf
                            <button type="submit" class="menu-link menu-link--button">Logout</button>
                        </form>
                    @else
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="menu-link menu-link--button">Logout</button>
                        </form>
                    @endif
                @else
                    <a class="menu-link" href="{{ route('register') }}">Registration</a>
                    <a class="menu-link" href="{{ route('login') }}">Login</a>
                @endauth
            </nav>
        </div>

        <script>
            (() => {
                const overlay = document.getElementById('menuOverlay');
                if (!overlay) return;

                const openBtn = document.querySelector('[data-menu-toggle]');
                const closeBtn = document.querySelector('[data-menu-close]');
                const isOpen = () => overlay.getAttribute('data-open') === 'true';
                const open = () => {
                    overlay.setAttribute('data-open', 'true');
                    overlay.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                };
                const close = () => {
                    overlay.setAttribute('data-open', 'false');
                    overlay.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                };
                if (openBtn) openBtn.addEventListener('click', () => isOpen() ? close() : open(), { passive: true });
                if (closeBtn) closeBtn.addEventListener('click', close, { passive: true });
                overlay.addEventListener('click', e => { if (e.target === overlay) close(); }, { passive: true });
                window.addEventListener('keydown', e => { if (e.key === 'Escape' && isOpen()) close(); });
                overlay.querySelectorAll('a,button').forEach(el => el.addEventListener('click', () => {
                    if (isOpen()) close();
                }, { passive: true }));
                window.addEventListener('pageshow', close);
                document.addEventListener('livewire:navigated', close);
            })();
        </script>
    @endunless
</header>
