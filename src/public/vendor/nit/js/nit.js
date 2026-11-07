/* ==========================================================================
   Natore IT — interaction layer
   Vanilla JS on top of Bootstrap 5's bundle. Frontend-only: nothing here
   sends data anywhere.
   ========================================================================== */
(function () {
    'use strict';

    var doc = document;
    var root = doc.documentElement;
    var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

    // Duration helper: collapse waits to 0 when the user prefers reduced motion
    function wait(ms) {
        return reducedMotion.matches ? 0 : ms;
    }

    function $all(selector, scope) {
        return Array.prototype.slice.call((scope || doc).querySelectorAll(selector));
    }

    /* Scroll reveal ------------------------------------------------------ */
    function initReveal() {
        var items = $all('.reveal');

        $all('[data-stagger]').forEach(function (group) {
            var step = parseInt(group.getAttribute('data-stagger'), 10) || 60;
            $all('.reveal', group).forEach(function (el, i) {
                el.style.setProperty('--reveal-delay', Math.min(i, 5) * step + 'ms');
            });
        });

        if (!root.classList.contains('reveal-on')) {
            items.forEach(function (el) { el.classList.add('is-visible'); });
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target); // animate once only
                }
            });
        }, { rootMargin: '0px 0px -8% 0px', threshold: 0 });

        items.forEach(function (el) { observer.observe(el); });
    }

    /* Header elevation + back-to-top (no scroll listeners) --------------- */
    function initScrollState() {
        var sentinel = doc.querySelector('[data-scroll-sentinel]');
        var header = doc.querySelector('[data-site-header]');
        var toTop = doc.querySelector('[data-back-to-top]');
        var heroEnd = doc.querySelector('[data-hero-end]') || doc.querySelector('main > :first-child');

        if (!('IntersectionObserver' in window)) return;

        if (sentinel && header) {
            new IntersectionObserver(function (entries) {
                header.classList.toggle('is-scrolled', !entries[0].isIntersecting);
            }).observe(sentinel);
        }

        if (toTop && heroEnd) {
            new IntersectionObserver(function (entries) {
                var entry = entries[0];
                var past = !entry.isIntersecting && entry.boundingClientRect.top < 0;
                toTop.classList.toggle('is-visible', past);
                toTop.tabIndex = past ? 0 : -1;
            }).observe(heroEnd);

            toTop.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: reducedMotion.matches ? 'auto' : 'smooth' });
                var main = doc.getElementById('main');
                if (main) main.focus({ preventScroll: true });
            });
        }
    }

    /* Mobile navigation: Escape closes the open menu --------------------- */
    function initMobileNav() {
        var collapseEl = doc.getElementById('siteNav');
        var toggler = doc.querySelector('[data-bs-target="#siteNav"]');
        if (!collapseEl || !toggler || !window.bootstrap) return;

        doc.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape' || !collapseEl.classList.contains('show')) return;
            if (doc.querySelector('.site-nav .dropdown-menu.show')) return; // let the dropdown close first
            bootstrap.Collapse.getOrCreateInstance(collapseEl).hide();
            toggler.focus();
        });
    }

    /* Click ripple on buttons (pointer only; skipped for reduced motion) --- */
    function initRipple() {
        doc.addEventListener('pointerdown', function (e) {
            var btn = e.target.closest('.btn');
            if (!btn || btn.disabled || btn.classList.contains('disabled') || reducedMotion.matches || e.button > 0) return;

            var rect = btn.getBoundingClientRect();
            var ripple = doc.createElement('span');
            ripple.className = 'btn-ripple';
            ripple.setAttribute('aria-hidden', 'true');
            ripple.style.setProperty('--x', (e.clientX - rect.left) + 'px');
            ripple.style.setProperty('--y', (e.clientY - rect.top) + 'px');
            ripple.style.setProperty('--d', Math.max(rect.width, rect.height) * 2.4 + 'px');
            btn.appendChild(ripple);
            ripple.addEventListener('animationend', function () { ripple.remove(); });
        });
    }

    /* Filters (portfolio, products) -------------------------------------- */
    function initFilters() {
        $all('[data-filter-group]').forEach(function (bar) {
            var grid = doc.querySelector(bar.getAttribute('data-filter-group'));
            if (!grid) return;

            var items = $all('[data-category]', grid);
            var buttons = $all('[data-filter]', bar);
            var status = doc.querySelector(bar.getAttribute('data-filter-status'));
            var noun = bar.getAttribute('data-filter-noun') || 'item';
            var timer;

            function matches(item, filter) {
                return filter === 'all' || item.getAttribute('data-category').split(' ').indexOf(filter) !== -1;
            }

            buttons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var filter = btn.getAttribute('data-filter');

                    buttons.forEach(function (b) {
                        var on = b === btn;
                        b.classList.toggle('active', on);
                        b.setAttribute('aria-pressed', on ? 'true' : 'false');
                    });

                    // 1) fade out what is leaving, 2) swap visibility, 3) fade in what is new
                    clearTimeout(timer);
                    items.forEach(function (item) {
                        if (!matches(item, filter)) item.classList.add('is-filtered-out');
                    });

                    timer = setTimeout(function () {
                        var count = 0;
                        if (grid.nitPager) {
                            var shown = items.filter(function (item) { return matches(item, filter); });
                            items.forEach(function (item) { item.classList.remove('is-filtered-out'); });
                            grid.nitPager.reset(shown);
                            if (status) status.textContent = 'Showing ' + shown.length + ' ' + noun + (shown.length === 1 ? '' : 's');
                            return;
                        }
                        items.forEach(function (item) {
                            if (matches(item, filter)) {
                                count++;
                                if (item.hidden) {
                                    item.hidden = false;
                                    item.classList.add('is-filtered-out');
                                    void item.offsetWidth; // commit start state before fading in
                                }
                                item.classList.remove('is-filtered-out');
                            } else {
                                item.hidden = true;
                            }
                        });
                        if (status) status.textContent = 'Showing ' + count + ' ' + noun + (count === 1 ? '' : 's');
                    }, wait(200));
                });
            });
        });
    }

    /* Infinite scroll: reveal [data-page-size] items in batches as the sentinel nears */
    function initPagers() {
        $all('[data-page-size]').forEach(function (grid) {
            var size = parseInt(grid.getAttribute('data-page-size'), 10) || 12;
            var section = grid.parentElement;
            var loader = section.querySelector('[data-page-loader]');
            var sentinel = section.querySelector('[data-page-sentinel]');
            var status = section.querySelector('[data-page-status]');
            var pool = [], shown = 0, busy = false, observer;

            function more() {
                if (busy || shown >= pool.length) return;
                busy = true;
                if (loader) loader.hidden = false;
                // Short skeleton beat so new rows don't pop in abruptly
                setTimeout(function () {
                    var next = pool.slice(shown, shown + size);
                    next.forEach(function (item, i) {
                        item.hidden = false;
                        item.classList.add('is-filtered-out');
                        void item.offsetWidth;
                        setTimeout(function () { item.classList.remove('is-filtered-out'); }, i * 25);
                    });
                    shown += next.length;
                    busy = false;
                    if (loader) loader.hidden = shown >= pool.length;
                    if (status) status.textContent = 'Showing ' + shown + ' of ' + pool.length;
                    check();
                }, wait(450));
            }
            // Keep loading while the sentinel is still on screen (tall viewports)
            function check() {
                if (sentinel && sentinel.getBoundingClientRect().top < window.innerHeight + 300) more();
            }

            grid.nitPager = {
                reset: function (items) {
                    $all('[data-category]', grid).forEach(function (item) { item.hidden = true; });
                    pool = items; shown = 0; busy = false;
                    pool.slice(0, size).forEach(function (item) { item.hidden = false; });
                    shown = Math.min(size, pool.length);
                    if (loader) loader.hidden = shown >= pool.length;
                }
            };
            grid.nitPager.reset($all('[data-category]', grid));

            if ('IntersectionObserver' in window && sentinel) {
                observer = new IntersectionObserver(function (entries) {
                    if (entries[0].isIntersecting) more();
                }, { rootMargin: '300px 0px' });
                observer.observe(sentinel);
            } else {
                pool.forEach(function (item) { item.hidden = false; });
                if (loader) loader.hidden = true;
            }
        });
    }

    /* Skeletons: clear the shimmer once the media inside has loaded (or failed) */
    function initSkeletons() {
        $all('[data-skeleton]').forEach(function (media) {
            var holder = media.closest('.skel');
            if (!holder) return;
            function done() { holder.classList.add('is-loaded'); }
            if (media.tagName === 'IMG' && media.complete) { done(); return; }
            media.addEventListener('load', done, { once: true });
            media.addEventListener('error', done, { once: true });
        });
    }

    /* "Show more" toggles for lists collapsed on phones -------------------- */
    function initMoreToggles() {
        $all('[data-more-toggle]').forEach(function (btn) {
            var list = doc.querySelector(btn.getAttribute('data-more-toggle'));
            var label = btn.querySelector('[data-more-label]');
            if (!list) return;
            var closedText = label ? label.textContent : '';
            btn.addEventListener('click', function () {
                var open = list.classList.toggle('is-open');
                btn.setAttribute('aria-expanded', open ? 'true' : 'false');
                if (label) label.textContent = open ? 'Show less' : closedText;
            });
        });
    }

    /* Footer link groups start collapsed on phones ------------------------- */
    function initFooterGroups() {
        var phone = window.matchMedia('(max-width: 767.98px)');
        var groups = $all('[data-footer-group]');
        function apply() {
            groups.forEach(function (group) { group.open = !phone.matches; });
        }
        apply();
        if (phone.addEventListener) phone.addEventListener('change', apply);
        // On desktop the summary is a plain heading, not a toggle
        groups.forEach(function (group) {
            group.querySelector('summary').addEventListener('click', function (e) {
                if (!phone.matches) e.preventDefault();
            });
        });
    }

    /* Contact + newsletter forms: validated here, sent with fetch (plain POST without JS) */
    function initAjaxForms() {
        $all('form[data-ajax-form]').forEach(function (form) {
            var success = form.querySelector('[data-form-success]');
            var error = form.querySelector('[data-form-error]');
            var button = form.querySelector('[type="submit"]');
            var busy = false;

            function show(el, text) {
                if (!el) return;
                var slot = el.querySelector('[data-form-message]') || el;
                slot.textContent = text;
                el.hidden = false;
            }

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                if (busy) return;
                if (success) success.hidden = true;
                if (error) error.hidden = true;

                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    var firstInvalid = form.querySelector(':invalid:not(fieldset)');
                    if (firstInvalid) firstInvalid.focus();
                    return;
                }

                busy = true;
                if (button) button.setAttribute('aria-busy', 'true');
                if (button) button.disabled = true;

                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin'
                })
                    .then(function (res) {
                        return res.json().catch(function () { return {}; }).then(function (body) { return { ok: res.ok, status: res.status, body: body }; });
                    })
                    .then(function (r) {
                        if (r.ok) {
                            form.reset();
                            form.classList.remove('was-validated');
                            show(success, r.body.message || 'Thank you!');
                            if (success) success.focus();
                            return;
                        }
                        var firstError = r.body.errors && Object.values(r.body.errors)[0];
                        show(error, firstError ? firstError[0]
                            : r.status === 429 ? 'Too many tries — please wait a minute and send again.'
                            : r.status === 419 ? 'The page expired — please reload it and send again.'
                            : 'Sorry, it could not be sent. Please call or WhatsApp us.');
                    })
                    .catch(function () {
                        show(error, 'Network problem — please check your connection and try again.');
                    })
                    .finally(function () {
                        busy = false;
                        if (button) { button.disabled = false; button.removeAttribute('aria-busy'); }
                    });
            });
        });
    }

    /* Domain search (live, via the package's /domain/check endpoint) ----- */
    function initDomainSearch() {
        var form = doc.querySelector('form[data-domain-search]');
        if (!form) return;

        var input = form.querySelector('input[name="domain"]');
        var error = doc.querySelector('[data-domain-input-error]');
        var defaultError = error.textContent;
        var button = form.querySelector('[type="submit"]');
        var results = doc.querySelector(form.getAttribute('data-domain-search'));
        var endpoint = form.getAttribute('data-endpoint');
        var alertBox = results.querySelector('[data-domain-alert]');
        var primaryBox = results.querySelector('[data-domain-primary]');
        var groups = {
            spotlight: results.querySelector('[data-domain-group="spotlight"]'),
            suggestions: results.querySelector('[data-domain-group="suggestions"]')
        };
        var namePattern = /^[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?$/;
        var pending;

        function el(tag, className, text) {
            var node = doc.createElement(tag);
            if (className) node.className = className;
            if (text) node.textContent = text;
            return node;
        }

        function safeUrl(url) {
            return typeof url === 'string' && /^(https?:)?\/\//.test(url) ? url : null;
        }

        function actionLink(item, label, className) {
            var url = safeUrl(item.cart_url);
            if (!url) return null;
            var link = el('a', 'btn ' + className, label);
            link.href = url;
            link.setAttribute('aria-label', label + ': ' + item.domain);
            return link;
        }

        function price(item) {
            if (!item.price) return '—';
            return item.price + (item.period > 1 ? '/' + item.period + ' yrs' : '/yr');
        }

        function renderPrimary(item) {
            var state = item.unsupported ? 'is-unsupported' : (item.available ? 'is-available' : 'is-taken');
            var card = el('div', 'domain-primary ' + state);
            var icon = el('span', 'domain-primary-icon');
            icon.setAttribute('aria-hidden', 'true');
            icon.appendChild(el('i', 'bi ' + (item.unsupported ? 'bi-info-circle' : item.available ? 'bi-check-circle-fill' : 'bi-x-circle-fill')));

            var body = el('div', 'domain-primary-body');
            var heading = el('p', 'domain-primary-title');
            heading.appendChild(el('strong', null, item.domain));
            heading.appendChild(doc.createTextNode(item.unsupported ? '' : item.available ? ' is available!' : ' is already registered'));
            body.appendChild(heading);
            if (item.message) {
                body.appendChild(el('p', 'domain-primary-note', item.message));
            } else if (item.available) {
                body.appendChild(el('p', 'domain-primary-note', (item.premium ? 'Premium domain · ' : '') + 'Register for ' + price(item) + (item.renew ? ' · renews at ' + item.renew + '/yr' : '')));
            } else {
                body.appendChild(el('p', 'domain-primary-note', 'Already own it? Transfer it to us, or pick one of the options below.'));
            }

            card.appendChild(icon);
            card.appendChild(body);
            var action = item.unsupported ? actionLink(item, 'Contact us', 'btn-primary')
                : item.available ? actionLink(item, 'Add to cart', 'btn-primary')
                : actionLink(item, 'Transfer', 'btn-outline-primary');
            if (action) card.appendChild(action);
            primaryBox.replaceChildren(card);
        }

        function renderGroup(group, items) {
            var list = group.querySelector('[data-domain-list]');
            list.replaceChildren();
            group.hidden = !items.length;
            items.forEach(function (item, i) {
                var row = el('div', 'domain-result');
                row.style.setProperty('--row-delay', wait(i * 40) + 'ms');
                row.appendChild(el('span', 'domain-name', item.domain));
                row.appendChild(el('span', 'badge rounded-pill ' + (item.available ? 'text-bg-success' : 'text-bg-secondary'), item.available ? 'Available' : 'Taken'));
                row.appendChild(el('span', 'domain-price', item.available ? price(item) : '—'));
                var action = item.available ? actionLink(item, 'Add to cart', 'btn-sm btn-outline-primary') : null;
                if (!action) {
                    action = el('button', 'btn btn-sm btn-outline-secondary', 'Unavailable');
                    action.type = 'button';
                    action.disabled = true;
                }
                row.appendChild(action);
                list.appendChild(row);
            });
        }

        function showAlert(message, url) {
            alertBox.replaceChildren(el('span', null, message + ' '));
            if (safeUrl(url)) {
                var link = el('a', 'alert-link', 'Search on our billing site');
                link.href = url;
                alertBox.appendChild(link);
            }
            alertBox.hidden = false;
        }

        function setLoading(on) {
            button.disabled = on;
            button.classList.toggle('is-loading', on);
            results.setAttribute('aria-busy', on ? 'true' : 'false');
        }

        input.addEventListener('input', function () {
            input.setCustomValidity('');
            error.textContent = defaultError;
        });

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var query = input.value.trim().toLowerCase()
                .replace(/^[a-z]+:\/\//, '').replace(/^www\./, '').split(/[/?#]/)[0];
            input.setCustomValidity(namePattern.test(query.split('.')[0]) ? '' : 'invalid');
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                input.focus();
                return;
            }
            form.classList.remove('was-validated');

            if (pending) pending.abort();
            pending = 'AbortController' in window ? new AbortController() : null;

            setLoading(true);
            results.hidden = false;
            alertBox.hidden = true;
            groups.spotlight.hidden = groups.suggestions.hidden = true;
            primaryBox.replaceChildren(el('div', 'skeleton skeleton-lg'), el('div', 'skeleton'), el('div', 'skeleton'));

            fetch(endpoint + '?domain=' + encodeURIComponent(query), {
                headers: { Accept: 'application/json' },
                signal: pending ? pending.signal : undefined
            })
                .then(function (response) {
                    return response.json().catch(function () { return {}; }).then(function (data) {
                        return { status: response.status, data: data };
                    });
                })
                .then(function (res) {
                    setLoading(false);
                    if (res.status === 200 && res.data.primary) {
                        renderPrimary(res.data.primary);
                        renderGroup(groups.spotlight, res.data.spotlight || []);
                        renderGroup(groups.suggestions, res.data.suggestions || []);
                        return;
                    }
                    primaryBox.replaceChildren();
                    if (res.status === 422) {
                        results.hidden = true;
                        error.textContent = res.data.message || defaultError;
                        input.setCustomValidity('invalid');
                        form.classList.add('was-validated');
                        input.focus();
                    } else if (res.status === 429) {
                        showAlert('Too many searches in a short time. Please wait a minute and try again.');
                    } else {
                        showAlert(res.data.message || 'We could not check availability right now.', res.data.cart_url);
                    }
                })
                .catch(function (err) {
                    if (err && err.name === 'AbortError') return; // superseded by a newer search
                    setLoading(false);
                    primaryBox.replaceChildren();
                    showAlert('Network problem — please check your connection and try again.');
                });
        });
    }

    /* Copy to clipboard (coupon code) -------------------------------------- */
    function initCopy() {
        $all('[data-copy]').forEach(function (button) {
            var field = doc.querySelector(button.getAttribute('data-copy'));
            var label = button.querySelector('[data-copy-label]');
            var icon = button.querySelector('.bi');
            var status = button.closest('.modal-body, body').querySelector('[data-copy-status]');
            var timer;

            function feedback(copied) {
                clearTimeout(timer);
                if (copied) {
                    label.textContent = 'Copied';
                    icon.className = 'bi bi-check2';
                    button.classList.add('is-copied');
                    if (status) status.textContent = 'Coupon code copied to clipboard.';
                    timer = setTimeout(function () {
                        label.textContent = 'Copy';
                        icon.className = 'bi bi-clipboard';
                        button.classList.remove('is-copied');
                    }, 2000);
                } else {
                    field.focus();
                    field.select();
                    if (status) status.textContent = 'Press Ctrl+C (or long-press) to copy the selected code.';
                }
            }

            button.addEventListener('click', function () {
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(field.value).then(function () { feedback(true); }, function () { feedback(false); });
                } else {
                    feedback(false);
                }
            });
        });
    }

    function initScrollProgress() {
        var bar = document.querySelector('[data-scroll-progress]');
        if (!bar) return;
        var update = function () {
            var max = document.documentElement.scrollHeight - window.innerHeight;
            bar.style.transform = 'scaleX(' + (max > 0 ? window.scrollY / max : 0) + ')';
        };
        window.addEventListener('scroll', update, { passive: true });
        update();
    }

    function initThemeToggle() {
        var button = document.querySelector('[data-theme-toggle]');
        if (!button) return;
        var root = document.documentElement;
        var paint = function () {
            button.querySelector('i').className = root.getAttribute('data-bs-theme') === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
        };
        button.addEventListener('click', function () {
            var dark = root.getAttribute('data-bs-theme') === 'dark';
            if (dark) root.removeAttribute('data-bs-theme'); else root.setAttribute('data-bs-theme', 'dark');
            try { localStorage.setItem('nit-theme', dark ? 'light' : 'dark'); } catch (e) {}
            paint();
        });
        paint();
    }

    // "1,000+" counts up from 0 once it is on screen; other texts stay as they are
    function initCountUp() {
        var items = document.querySelectorAll('[data-count]');
        if (!document.body.hasAttribute('data-count-up') || !items.length || !('IntersectionObserver' in window)
            || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                observer.unobserve(entry.target);
                var el = entry.target, text = el.textContent.trim();
                var match = text.match(/^([\d,]+(?:\.\d+)?)(.*)$/);
                if (!match) return;
                var target = parseFloat(match[1].replace(/,/g, '')), decimals = (match[1].split('.')[1] || '').length, start = null;
                var step = function (now) {
                    start = start || now;
                    var t = Math.min((now - start) / 1100, 1), eased = 1 - Math.pow(1 - t, 3);
                    el.textContent = t === 1 ? text : (target * eased).toLocaleString('en-US', { minimumFractionDigits: decimals, maximumFractionDigits: decimals }) + match[2];
                    if (t < 1) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            });
        }, { threshold: 0.4 });
        items.forEach(function (item) { observer.observe(item); });
    }

    // Prices are written in USD; with a rate set in the admin they can be shown in taka
    function initCurrency() {
        var rate = parseFloat(document.body.getAttribute('data-bdt-rate'));
        var button = document.querySelector('[data-currency-toggle]');
        var main = document.getElementById('main');
        if (!rate || !button || !main) return;
        var originals = new WeakMap(), price = /\$\s?(\d[\d,]*(?:\.\d+)?)/g, current = document.body.getAttribute('data-currency-default');
        try { current = localStorage.getItem('nit-currency') || current; } catch (e) {}
        var observer;

        function apply() {
            observer.disconnect();
            var walker = document.createTreeWalker(main, NodeFilter.SHOW_TEXT), node;
            while ((node = walker.nextNode())) {
                var text = originals.has(node) ? originals.get(node) : node.nodeValue;
                if (text.indexOf('$') === -1) continue;
                originals.set(node, text);
                node.nodeValue = current !== 'bdt' ? text : text.replace(price, function (match, amount) {
                    return '৳' + Math.round(parseFloat(amount.replace(/,/g, '')) * rate).toLocaleString('en-US');
                });
            }
            button.textContent = current === 'bdt' ? '৳ BDT' : '$ USD';
            observer.observe(main, { childList: true, subtree: true });
        }

        observer = new MutationObserver(apply);
        button.addEventListener('click', function () {
            current = current === 'bdt' ? 'usd' : 'bdt';
            try { localStorage.setItem('nit-currency', current); } catch (e) {}
            apply();
        });
        apply();
    }

    function init() {
        window.NIT_READY = true;
        initReveal();
        initScrollState();
        initMobileNav();
        initPagers();
        initFilters();
        initSkeletons();
        initMoreToggles();
        initFooterGroups();
        initAjaxForms();
        initDomainSearch();
        initCopy();
        initRipple();
        initScrollProgress();
        initThemeToggle();
        initCountUp();
        initCurrency();
    }

    if (doc.readyState === 'loading') {
        doc.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
