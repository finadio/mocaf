import type { Directive } from 'vue';

let sharedObserver: IntersectionObserver | null = null;

function getObserver(): IntersectionObserver | null {
    if (typeof window === 'undefined' || !('IntersectionObserver' in window)) {
        return null;
    }

    if (!sharedObserver) {
        sharedObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        sharedObserver?.unobserve(entry.target);
                    }
                });
            },
            {
                threshold: 0.1,
                rootMargin: '0px 0px -30px 0px',
            }
        );
    }

    return sharedObserver;
}

/**
 * Custom directive v-reveal untuk animasi muncul halus saat elemen masuk viewport.
 * Contoh penggunaan: v-reveal atau v-reveal="120" (delay dalam milidetik).
 */
export const vReveal: Directive<HTMLElement, number | undefined> = {
    mounted(el, binding) {
        el.classList.add('reveal-item');

        if (typeof binding.value === 'number' && binding.value > 0) {
            el.style.transitionDelay = `${binding.value}ms`;
        }

        const observer = getObserver();
        if (!observer) {
            el.classList.add('is-visible');
            return;
        }

        observer.observe(el);
    },
    unmounted(el) {
        sharedObserver?.unobserve(el);
    },
};

