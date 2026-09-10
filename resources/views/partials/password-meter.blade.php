{{-- Pengukur kekuatan kata sandi. Param: $input (id input password), $confirm (id konfirmasi, opsional).
     Pakai zxcvbn bila CDN termuat, fallback heuristik bila tidak. --}}
<div class="mt-2" data-pw-meter="{{ $input }}">
    <div class="flex gap-1" aria-hidden="true">
        <span data-seg="0" class="h-1.5 flex-1 rounded-full bg-nf-ink/10"></span>
        <span data-seg="1" class="h-1.5 flex-1 rounded-full bg-nf-ink/10"></span>
        <span data-seg="2" class="h-1.5 flex-1 rounded-full bg-nf-ink/10"></span>
        <span data-seg="3" class="h-1.5 flex-1 rounded-full bg-nf-ink/10"></span>
    </div>
    <p class="mt-1.5 text-xs font-bold"><span data-pw-label class="text-nf-ink/50">Ketik kata sandi…</span><span data-pw-match class="ml-2"></span></p>
    <ul class="mt-1.5 grid grid-cols-2 gap-x-3 gap-y-1 text-xs text-nf-ink/60">
        <li data-req="length">✓ Min. 8 karakter</li>
        <li data-req="case">✓ Huruf besar + kecil</li>
        <li data-req="number">✓ Angka</li>
        <li data-req="symbol">✓ Simbol (!@#…)</li>
    </ul>
</div>
<script>
(function () {
    if (!window.__zxcvbnRequested) {
        window.__zxcvbnRequested = true;
        var s = document.createElement('script');
        s.src = 'https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js';
        s.defer = true;
        s.referrerPolicy = 'no-referrer';
        document.head.appendChild(s);
    }
    if (!window.attachPasswordMeter) {
        window.attachPasswordMeter = function (inputId, confirmId) {
            var input = document.getElementById(inputId);
            if (!input) return;
            var box = document.querySelector('[data-pw-meter="' + inputId + '"]');
            if (!box) return;
            var confirm = confirmId ? document.getElementById(confirmId) : null;
            var colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-400', 'bg-nf-blue', 'bg-nf-green'];
            var labels = ['Sangat lemah', 'Lemah', 'Sedang', 'Kuat', 'Sangat kuat'];
            function scoreOf(v) {
                if (typeof window.zxcvbn === 'function' && v) {
                    try { return window.zxcvbn(v).score; } catch (e) { /* fallback */ }
                }
                var sc = 0;
                if (v.length >= 8) sc++;
                if (/[a-z]/.test(v) && /[A-Z]/.test(v)) sc++;
                if (/\d/.test(v) && /[^A-Za-z0-9]/.test(v)) sc++;
                if (v.length >= 12) sc++;
                return Math.min(sc, 4);
            }
            function paint() {
                var v = input.value || '';
                var sc = v ? scoreOf(v) : -1;
                box.querySelectorAll('[data-seg]').forEach(function (seg, i) {
                    seg.className = 'h-1.5 flex-1 rounded-full ' + (i <= sc ? colors[sc] : 'bg-nf-ink/10');
                });
                var label = box.querySelector('[data-pw-label]');
                label.textContent = v ? labels[sc] : 'Ketik kata sandi…';
                label.className = v ? colors[sc].replace('bg-', 'text-') : 'text-nf-ink/50';
                var reqs = {
                    length: v.length >= 8,
                    case: /[a-z]/.test(v) && /[A-Z]/.test(v),
                    number: /\d/.test(v),
                    symbol: /[^A-Za-z0-9]/.test(v)
                };
                box.querySelectorAll('[data-req]').forEach(function (li) {
                    var ok = !!reqs[li.getAttribute('data-req')];
                    li.className = ok ? 'text-nf-green-dark font-bold' : 'text-nf-ink/60';
                });
                var match = box.querySelector('[data-pw-match]');
                if (match && confirm) {
                    if (!confirm.value) { match.textContent = ''; }
                    else if (confirm.value === v && v) { match.textContent = '✓ cocok'; match.className = 'ml-2 text-nf-green-dark'; }
                    else { match.textContent = '✗ belum cocok'; match.className = 'ml-2 text-red-600'; }
                }
            }
            input.addEventListener('input', paint);
            if (confirm) confirm.addEventListener('input', paint);
            paint();
        };
    }
    window.attachPasswordMeter('{{ $input }}', {{ isset($confirm) ? "'$confirm'" : 'null' }});
})();
</script>
