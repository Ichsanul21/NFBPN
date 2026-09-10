{{-- Satu-satunya sumber factory JS form PPDB. Di-load inline agar HTML dan JS
     tidak mungkin beda versi (kebal stale bundle). Dipakai form publik dan
     pratinjau studio. Mirror 1:1 dengan PpdbFormField::isVisibleFor(). --}}
<script>
(function () {
    const asArr = v => (Array.isArray(v) ? v.map(String) : (v === undefined || v === null || v === '' ? [] : [String(v)]));
    const first = v => (Array.isArray(v) ? (v[0] ?? '') : (v ?? ''));

    function isVisible(conditions, answers, j, key) {
        const c = (conditions[j] || {})[key];
        if (!c) return true;
        const actual = answers[c.trigger];
        const exp = c.value;
        switch (c.op) {
            case 'equals':
                if (Array.isArray(actual)) return actual.map(String).includes(String(first(exp)));
                return String(actual ?? '') === String(first(exp));
            case 'not_equals':
                if (Array.isArray(actual)) return !actual.map(String).includes(String(first(exp)));
                return String(actual ?? '') !== String(first(exp));
            case 'in':
                return asArr(actual).some(v => asArr(exp).includes(v));
            case 'not_in':
                return !asArr(actual).some(v => asArr(exp).includes(v));
            case 'filled':
                return actual !== undefined && actual !== null && String(actual).trim() !== '' && !(Array.isArray(actual) && actual.length === 0);
            case 'empty': {
                const filled = actual !== undefined && actual !== null && String(actual).trim() !== '' && !(Array.isArray(actual) && actual.length === 0);
                return !filled;
            }
            default:
                return true;
        }
    }

    function sync(comp, key, el) {
        const scope = (el.closest && el.closest('[data-jenjang-section]')) || document;
        if (el.type === 'checkbox') {
            comp.answers[key] = [...scope.querySelectorAll('input[name="answers[' + key + '][]"]:checked')].map(b => b.value);
        } else if (el.type === 'radio') {
            const sel = scope.querySelector('input[name="answers[' + key + ']"]:checked');
            comp.answers[key] = sel ? sel.value : '';
        } else {
            comp.answers[key] = el.value;
        }
    }

    window.ppdbForm = function (conditions, initialAnswers) {
        return {
            answers: initialAnswers || {},
            isVisible(j, key) { return isVisible(conditions, this.answers, j, key); },
            sync(key, el) { sync(this, key, el); }
        };
    };

    // Wizard 3 langkah halaman PPDB publik: 1 akun, 2 jenjang, 3 formulir dinamis.
    window.ppdbWizard = function (conditions, initialAnswers, periodsByJenjang, initialPeriodId) {
        return {
            step: 1,
            jenjang: '',
            periodId: initialPeriodId ? String(initialPeriodId) : '',
            periodsByJenjang: periodsByJenjang || {},
            answers: initialAnswers || {},
            init() {
                if (this.periodId) {
                    for (const [j, list] of Object.entries(this.periodsByJenjang)) {
                        if ((list || []).some(p => String(p.id) === String(this.periodId))) {
                            this.jenjang = j;
                            break;
                        }
                    }
                }
            },
            periodsFor(j) { return this.periodsByJenjang[j] || []; },
            chooseJenjang(j) {
                this.jenjang = j;
                const list = this.periodsFor(j);
                this.periodId = list.length === 1 ? String(list[0].id) : '';
            },
            nextStep() {
                // Langkah 2 = pilih jenjang (butuh jenjang + gelombang).
                // Langkah lain = validasi generik semua input pada step aktif.
                if (this.step === 2) {
                    if (!this.jenjang) return;
                    if (!this.periodId) {
                        const box = this.$el.querySelector('[data-step="2"]');
                        const sel = box && box.querySelector('select');
                        if (sel) { sel.reportValidity(); }
                        return;
                    }
                } else {
                    const box = this.$el.querySelector('[data-step="' + this.step + '"]');
                    if (box) {
                        const inputs = box.querySelectorAll('input,select,textarea');
                        for (const el of inputs) {
                            if (!el.checkValidity()) { el.reportValidity(); return; }
                        }
                    }
                }
                this.step++;
                const top = this.$el.closest && this.$el.closest('section');
                if (top && top.scrollIntoView) top.scrollIntoView({ behavior: 'smooth', block: 'start' });
            },
            isVisible(j, key) { return isVisible(conditions, this.answers, j, key); },
            sync(key, el) { sync(this, key, el); }
        };
    };

    window.__ppdbLogicReady = true;
})();
</script>
