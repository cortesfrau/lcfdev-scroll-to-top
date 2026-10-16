import '../css/app.css';
import { ScrollToTop } from './scroll-to-top.js';

const ROOT_ID = 'lcfdev-scroll-to-top';

function boot() {
    const root = document.getElementById(ROOT_ID);

    if (!root) {
        return;
    }

    new ScrollToTop(root).start();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}
