{{-- Partial render field PPDB. Dipakai form publik (live) dan preview studio admin.
     Param: $j (slug jenjang), $fields (collection), $preview (bool, default false).
     Scope Alpine induk wajib menyediakan: isVisible(j, key), sync(key, el). --}}
@php
$preview = $preview ?? false;
$grouped = $fields->groupBy(fn ($f) => $f->section ?: '');
$oldVal = fn ($k) => $preview ? null : old('answers.'.$k);
@endphp
@foreach($grouped as $sectionName => $group)
@if($sectionName !== '')<h4 class="mt-5 mb-1 font-heading font-bold text-sm uppercase tracking-widest text-nf-blue-dark/70">{{ $sectionName }}</h4>@endif
<div class="mt-3 grid sm:grid-cols-2 gap-4">
    @foreach($group as $f)
    @php $cond = $f->hasCondition(); @endphp
    <div @if($cond) x-show="isVisible('{{ $j }}', '{{ $f->key }}')" x-transition.opacity.duration.200ms @endif>
    <label class="grid gap-1.5 text-sm font-bold {{ in_array($f->type, ['textarea']) ? 'sm:col-span-2' : '' }}">{{ $f->label }} @if($f->is_required)<span class="text-red-600">*</span>@endif
        @if($f->type === 'textarea')
        <textarea name="answers[{{ $f->key }}]" rows="3" x-on:input="sync('{{ $f->key }}', $event.target)" @if(!$preview && $f->is_required && !$cond) required @elseif(!$preview && $f->is_required) :required="isVisible('{{ $j }}', '{{ $f->key }}')" @endif @if($cond) :disabled="!isVisible('{{ $j }}', '{{ $f->key }}')" @endif class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 disabled:opacity-50">{{ $oldVal($f->key) }}</textarea>
        @elseif($f->type === 'select')
        <select name="answers[{{ $f->key }}]" x-on:change="sync('{{ $f->key }}', $event.target)" @if(!$preview && $f->is_required && !$cond) required @elseif(!$preview && $f->is_required) :required="isVisible('{{ $j }}', '{{ $f->key }}')" @endif @if($cond) :disabled="!isVisible('{{ $j }}', '{{ $f->key }}')" @endif class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 disabled:opacity-50">
            <option value="">Pilih</option>
            @foreach($f->options ?? [] as $o)<option @if(!$preview) @selected($oldVal($f->key) === $o) @endif>{{ $o }}</option>@endforeach
        </select>
        @elseif($f->type === 'radio')
        <span class="flex flex-wrap gap-3 font-normal">
            @foreach($f->options ?? [] as $o)<label class="flex items-center gap-1.5"><input type="radio" name="answers[{{ $f->key }}]" value="{{ $o }}" @if(!$preview) @checked($oldVal($f->key) === $o) @endif x-on:change="sync('{{ $f->key }}', $event.target)" @if($cond) :disabled="!isVisible('{{ $j }}', '{{ $f->key }}')" @endif class="text-nf-blue disabled:opacity-50"> {{ $o }}</label>@endforeach
        </span>
        @elseif($f->type === 'checkbox')
        <span class="flex flex-wrap gap-3 font-normal">
            @foreach($f->options ?? [] as $o)<label class="flex items-center gap-1.5"><input type="checkbox" name="answers[{{ $f->key }}][]" value="{{ $o }}" @if(!$preview) @checked(in_array($o, (array) $oldVal($f->key))) @endif x-on:change="sync('{{ $f->key }}', $event.target)" @if($cond) :disabled="!isVisible('{{ $j }}', '{{ $f->key }}')" @endif class="rounded text-nf-blue disabled:opacity-50"> {{ $o }}</label>@endforeach
        </span>
        @elseif($f->type === 'file')
        <input type="file" name="answers[{{ $f->key }}]" @if(!$preview && $f->is_required && !$cond) required @elseif(!$preview && $f->is_required) :required="isVisible('{{ $j }}', '{{ $f->key }}')" @endif @if($cond) :disabled="!isVisible('{{ $j }}', '{{ $f->key }}')" @endif accept=".pdf,.jpg,.jpeg,.png,.webp" class="font-normal text-sm file:mr-3 file:rounded-full file:border-0 file:bg-nf-blue-soft file:text-nf-blue-dark file:font-bold file:px-4 file:py-2 disabled:opacity-50">
        @elseif($f->type === 'number')
        <input type="number" name="answers[{{ $f->key }}]" value="{{ $oldVal($f->key) }}" x-on:input="sync('{{ $f->key }}', $event.target)" @if(!$preview && $f->is_required && !$cond) required @elseif(!$preview && $f->is_required) :required="isVisible('{{ $j }}', '{{ $f->key }}')" @endif @if($cond) :disabled="!isVisible('{{ $j }}', '{{ $f->key }}')" @endif class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 disabled:opacity-50">
        @elseif($f->type === 'date')
        <input type="date" name="answers[{{ $f->key }}]" value="{{ $oldVal($f->key) }}" x-on:change="sync('{{ $f->key }}', $event.target)" @if(!$preview && $f->is_required && !$cond) required @elseif(!$preview && $f->is_required) :required="isVisible('{{ $j }}', '{{ $f->key }}')" @endif @if($cond) :disabled="!isVisible('{{ $j }}', '{{ $f->key }}')" @endif class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 disabled:opacity-50">
        @else
        <input type="text" name="answers[{{ $f->key }}]" value="{{ $oldVal($f->key) }}" x-on:input="sync('{{ $f->key }}', $event.target)" @if(!$preview && $f->is_required && !$cond) required @elseif(!$preview && $f->is_required) :required="isVisible('{{ $j }}', '{{ $f->key }}')" @endif @if($cond) :disabled="!isVisible('{{ $j }}', '{{ $f->key }}')" @endif class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 disabled:opacity-50">
        @endif
    </label>
    </div>
    @endforeach
</div>
@endforeach
