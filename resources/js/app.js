import './bootstrap';

/* =====================================================================
   Riad Saveurs — interactions du thème (vanilla JS, sans dépendance)
   ===================================================================== */

/* ---- Thème clair / sombre (persistance localStorage) ---- */
const THEME_KEY = 'riad-theme';

function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    try {
        localStorage.setItem(THEME_KEY, theme);
    } catch (e) {
        /* localStorage indisponible : on ignore */
    }
}

function initTheme() {
    let saved;
    try {
        saved = localStorage.getItem(THEME_KEY);
    } catch (e) {
        saved = null;
    }
    const theme = saved || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    document.documentElement.setAttribute('data-theme', theme);
}

// Évite le flash : applique le thème dès le chargement du module.
initTheme();

function bind() {
    /* ---- Toggle thème ---- */
    document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const next = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            document.documentElement.classList.add('tw-no-transition');
            applyTheme(next);
            window.setTimeout(() => document.documentElement.classList.remove('tw-no-transition'), 60);
        });
    });

    /* ---- Toasts auto-affichés (flash) ---- */
    document.querySelectorAll('.toast').forEach((toast) => {
        requestAnimationFrame(() => toast.classList.add('is-on'));
        window.setTimeout(() => toast.classList.remove('is-on'), 4200);
    });

    /* ---- Steppers de quantité (panier / formulaire) ----
       Structure : <div class="qty" data-qty>
                      <button data-step="-1">−</button>
                      <input type="hidden" name="quantite" value="1">  (optionnel)
                      <span class="val">1</span>
                      <button data-step="1">+</button>
                    </div>
       Si l'attribut data-autosubmit est présent, le formulaire parent est
       soumis à chaque changement. */
    document.querySelectorAll('[data-qty]').forEach((wrap) => {
        const valEl = wrap.querySelector('.val');
        const input = wrap.querySelector('input');
        const min = parseInt(wrap.dataset.min || '1', 10);
        const max = parseInt(wrap.dataset.max || '99', 10);

        wrap.querySelectorAll('[data-step]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const current = parseInt((input ? input.value : valEl.textContent) || '1', 10);
                let next = current + parseInt(btn.dataset.step, 10);
                next = Math.max(min, Math.min(max, next));
                if (valEl) valEl.textContent = next;
                if (input) input.value = next;
                if (wrap.hasAttribute('data-autosubmit')) {
                    wrap.closest('form')?.submit();
                }
            });
        });
    });

    /* ---- Confirmation avant suppression ---- */
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (e) => {
            if (!window.confirm(form.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });

    /* ---- Soumission auto au changement (filtres select) ---- */
    document.querySelectorAll('[data-autosubmit-change]').forEach((el) => {
        el.addEventListener('change', () => el.closest('form')?.submit());
    });

    /* ---- Épinglage de la barre latérale (persisté) ---- */
    const PIN_KEY = 'riad-sidenav-pinned';
    const sidenav = document.getElementById('sidenav');
    if (sidenav) {
        let pinned;
        try {
            pinned = localStorage.getItem(PIN_KEY) === '1';
        } catch (e) {
            pinned = false;
        }
        sidenav.classList.toggle('is-pinned', pinned);

        document.querySelectorAll('[data-pin-sidenav]').forEach((btn) => {
            const sync = () => btn.classList.toggle('is-on', sidenav.classList.contains('is-pinned'));
            sync();
            btn.addEventListener('click', () => {
                const isPinned = sidenav.classList.toggle('is-pinned');
                try {
                    localStorage.setItem(PIN_KEY, isPinned ? '1' : '0');
                } catch (e) {
                    /* ignore */
                }
                sync();
            });
        });
    }

    /* ---- Toggle "disponible" stylé : reflète le checkbox masqué ---- */
    document.querySelectorAll('[data-toggle]').forEach((toggle) => {
        const target = document.getElementById(toggle.dataset.toggle);
        if (!target) return;
        const sync = () => toggle.classList.toggle('is-on', target.checked);
        sync();
        toggle.addEventListener('click', () => {
            target.checked = !target.checked;
            sync();
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bind);
} else {
    bind();
}
