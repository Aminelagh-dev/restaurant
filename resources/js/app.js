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

/* =====================================================================
   Panier — ajout en AJAX (sans rechargement de page)
   ===================================================================== */

const ICON_PATHS = {
    check: '<path d="M5 13l4 4L19 7"/>',
    x: '<path d="M6 6l12 12M18 6 6 18"/>',
};

function iconSvg(name) {
    return `<svg class="icon" width="13" height="13" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2.4" stroke-linecap="round"
        stroke-linejoin="round" aria-hidden="true">${ICON_PATHS[name] || ''}</svg>`;
}

/* Affiche un toast (même rendu que les toasts flash serveur) puis le retire. */
function showToast(message, type = 'ok') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.setAttribute('role', type === 'ok' ? 'status' : 'alert');

    const ico = document.createElement('span');
    ico.className = 'toast-ico';
    ico.innerHTML = iconSvg(type === 'ok' ? 'check' : 'x');
    toast.appendChild(ico);
    toast.appendChild(document.createTextNode(' ' + message));

    document.body.appendChild(toast);
    requestAnimationFrame(() => toast.classList.add('is-on'));
    window.setTimeout(() => {
        toast.classList.remove('is-on');
        window.setTimeout(() => toast.remove(), 400);
    }, 4200);
}

/* Met à jour (ou crée/retire) la pastille de compteur du panier dans la barre. */
function updateCartCount(count) {
    const cartBtn = document.querySelector('.cart-btn');
    if (!cartBtn) return;

    let badge = cartBtn.querySelector('.cart-count');
    if (count > 0) {
        if (!badge) {
            badge = document.createElement('span');
            badge.className = 'cart-count';
            cartBtn.appendChild(badge);
        }
        badge.textContent = count;
        badge.classList.remove('is-bump');
        void badge.offsetWidth; // reflow : relance l'animation
        badge.classList.add('is-bump');
    } else if (badge) {
        badge.remove();
    }
}

/* Met à jour la quantité d'une ligne du panier en AJAX (page /panier),
   avec une légère temporisation pour grouper les clics rapides. */
const cartQtyTimers = new WeakMap();

function scheduleCartQty(wrap) {
    clearTimeout(cartQtyTimers.get(wrap));
    cartQtyTimers.set(wrap, window.setTimeout(() => sendCartQty(wrap), 350));
}

async function sendCartQty(wrap) {
    const form = wrap.closest('form');
    if (!form) return;

    wrap.classList.add('is-loading');

    try {
        // axios.post + _method=PATCH (champ caché du formulaire) → route PATCH.
        const { data } = await window.axios.post(form.action, new FormData(form));

        // Sous-total de la ligne concernée.
        const sub = wrap.closest('.cart-line')?.querySelector('[data-line-subtotal]');
        if (sub && data.sousTotal) sub.textContent = data.sousTotal;

        // Totaux du récapitulatif (sous-total + total).
        if (data.total) {
            document.querySelectorAll('[data-cart-total]').forEach((el) => {
                el.textContent = data.total;
            });
        }

        // Libellé « Sous-total (N article(s)) » reconstruit depuis son gabarit localisé.
        const articles = document.querySelector('[data-articles-tpl]');
        if (articles && typeof data.count === 'number') {
            articles.textContent = articles.dataset.articlesTpl.replace(':count', data.count);
        }

        if (typeof data.count === 'number') updateCartCount(data.count);
    } catch (e) {
        // Échec : repli sur la soumission classique (rechargement de la page).
        form.submit();
        return;
    } finally {
        wrap.classList.remove('is-loading');
    }
}

/* Soumet le formulaire d'ajout au panier en AJAX (avec repli classique). */
async function submitCartForm(form) {
    if (form.dataset.busy === '1') return;
    form.dataset.busy = '1';

    const button = form.querySelector('[type="submit"]');
    button?.classList.add('is-loading');
    button?.setAttribute('disabled', 'disabled');

    try {
        const { data } = await window.axios.post(form.action, new FormData(form));

        if (typeof data.count === 'number') updateCartCount(data.count);
        showToast(data.message || '✓', 'ok');
    } catch (error) {
        if (error.response) {
            // Réponse d'erreur applicative (ex. plat épuisé → 422) : on affiche le message.
            const data = error.response.data || {};
            if (typeof data.count === 'number') updateCartCount(data.count);
            showToast(data.message || 'Erreur', 'err');
        } else {
            // Pas de réponse (réseau) : repli sur la soumission classique (rechargement).
            form.submit();
            return;
        }
    } finally {
        button?.classList.remove('is-loading');
        button?.removeAttribute('disabled');
        form.dataset.busy = '0';
    }
}

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

    /* ---- Ajout au panier en AJAX (menu) ---- */
    document.querySelectorAll('form[data-cart-form]').forEach((form) => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            submitCartForm(form);
        });
    });

    /* ---- Steppers de quantité (panier / formulaire) ----
       Structure : <div class="qty" data-qty>
                      <button data-step="-1">−</button>
                      <input type="hidden" name="quantite" value="1">  (optionnel)
                      <span class="val">1</span>
                      <button data-step="1">+</button>
                    </div>
       Si l'attribut data-autosubmit est présent, le formulaire parent est
       soumis à chaque changement. Si data-cart-qty est présent, la nouvelle
       quantité est envoyée en AJAX (mise à jour du panier sans rechargement). */
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
                if (next === current) return;
                if (valEl) valEl.textContent = next;
                if (input) input.value = next;
                if (wrap.hasAttribute('data-cart-qty')) {
                    scheduleCartQty(wrap);
                } else if (wrap.hasAttribute('data-autosubmit')) {
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
