const VISIBLE_CLASS = 'show';

export class ScrollToTop {
    #root;

    constructor(root) {
        this.#root = root;
    }

    start() {
        this.#syncVisibility();
        window.addEventListener('scroll', () => this.#syncVisibility(), { passive: true });
        window.addEventListener('resize', () => this.#syncVisibility());
        this.#root.addEventListener('click', () => this.#scrollToTop());
    }

    #syncVisibility() {
        this.#root.classList.toggle(VISIBLE_CLASS, this.#shouldAppear());
    }

    #shouldAppear() {
        return window.scrollY >= window.innerHeight;
    }

    #scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth',
        });
    }
}
