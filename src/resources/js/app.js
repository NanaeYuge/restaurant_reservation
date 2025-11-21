import '../css/pages/mypage/index.css';

(function () {
    if (window.__reseHeaderInit) return;
    window.__reseHeaderInit = true;

    const body = document.body;
    const btn = document.querySelector('[data-menu-toggle]');
    const panel = document.querySelector('[data-menu-panel]');
    const backdrop = document.querySelector('[data-menu-backdrop]');
    const closeBtn = document.querySelector('[data-menu-close]');
    const mq = window.matchMedia('(min-width: 1024px)');

    if (!btn || !panel) return;

    let lastFocus = null;

    const isOpen = () => body.classList.contains('menu-open');

    const open = () => {
    lastFocus = document.activeElement;
    body.classList.add('menu-open');
    btn.setAttribute('aria-expanded', 'true');
    panel.setAttribute('aria-hidden', 'false');
    if (backdrop) {
        backdrop.style.opacity = '1';
        backdrop.style.pointerEvents = 'auto';
        backdrop.hidden = false;
    }
    panel.style.opacity = '1';
    panel.style.transform = 'scale(1)';
    panel.style.pointerEvents = 'auto';
    document.addEventListener('keydown', onKeydown);
    };

    const close = () => {
    body.classList.remove('menu-open');
    btn.setAttribute('aria-expanded', 'false');
    panel.setAttribute('aria-hidden', 'true');
    if (backdrop) {
        backdrop.style.opacity = '0';
        backdrop.style.pointerEvents = 'none';
        backdrop.hidden = true;
    }
    panel.style.opacity = '0';
    panel.style.transform = 'scale(.98)';
    panel.style.pointerEvents = 'none';
    document.removeEventListener('keydown', onKeydown);
    if (lastFocus && typeof lastFocus.focus === 'function') lastFocus.focus();
    };

    const toggle = () => (isOpen() ? close() : open());

    const onKeydown = (e) => {
    if (e.key === 'Escape') close();
    };

    btn.addEventListener('click', toggle);
    if (closeBtn) closeBtn.addEventListener('click', close);
    if (backdrop) backdrop.addEventListener('click', close);

    panel.querySelectorAll('a, button').forEach((el) => {
    el.addEventListener('click', () => {
        if (isOpen()) close();
    });
    });

    const onMqChange = (e) => {
    if (e.matches) close();
    };
    if (mq.addEventListener) mq.addEventListener('change', onMqChange);
    else if (mq.addListener) mq.addListener(onMqChange);

    window.addEventListener('pageshow', () => close());
    document.addEventListener('turbo:load', () => close());
    document.addEventListener('livewire:navigated', () => close());
})();
