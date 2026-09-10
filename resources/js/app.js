

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Evaluator logika kondisional form PPDB. Dipakai form publik dan pratinjau studio.
// conditions: {jenjang: {fieldKey: {trigger, op, value}}}
// Mirror 1:1 dengan PpdbFormField::isVisibleFor() di backend.
window.ppdbForm = function (conditions, initialAnswers) {
    const asArr = v => (Array.isArray(v) ? v.map(String) : (v === undefined || v === null || v === '' ? [] : [String(v)]));
    const first = v => (Array.isArray(v) ? (v[0] ?? '') : (v ?? ''));
    return {
        jenjang: 'sdit',
        periodId: '',
        answers: initialAnswers || {},
        init() {
            const sel = this.$el.querySelector && this.$el.querySelector('select[name="period_id"]');
            if (sel && sel.selectedOptions[0]) {
                this.periodId = sel.value;
                this.jenjang = sel.selectedOptions[0].dataset.jenjang || 'sdit';
            }
        },
        isVisible(j, key) {
            const c = (conditions[j] || {})[key];
            if (!c) return true;
            const actual = this.answers[c.trigger];
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
        },
        sync(key, el) {
            const scope = (el.closest && el.closest('[data-jenjang-section]')) || document;
            if (el.type === 'checkbox') {
                this.answers[key] = [...scope.querySelectorAll('input[name="answers[' + key + '][]"]:checked')].map(b => b.value);
            } else if (el.type === 'radio') {
                const sel = scope.querySelector('input[name="answers[' + key + ']"]:checked');
                this.answers[key] = sel ? sel.value : '';
            } else {
                this.answers[key] = el.value;
            }
        }
    };
};
