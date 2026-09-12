<?php

namespace App\Models\Concerns;

use App\Models\PpdbFormField;

trait HasVisibilityCondition
{
    public function hasCondition(): bool
    {
        return ! empty($this->visible_if_field) && ! empty($this->visible_if_operator);
    }

    /**
     * Evaluasi apakah item tampil untuk jawaban yang diberikan.
     * $answers: array key => value (string|array|null).
     * Mirror JS: ppdbIsVisible di partial ppdb-logic.
     */
    public function isVisibleFor(array $answers): bool
    {
        if (! $this->hasCondition()) {
            return true;
        }

        $actual = $answers[$this->visible_if_field] ?? null;
        $expected = $this->visible_if_value;

        return match ($this->visible_if_operator) {
            'equals' => $this->conditionEquals($actual, $expected),
            'not_equals' => ! $this->conditionEquals($actual, $expected),
            'in' => count(array_intersect((array) $actual, (array) $expected)) > 0,
            'not_in' => count(array_intersect((array) $actual, (array) $expected)) === 0,
            'filled' => filled($actual) && ! (is_array($actual) && empty(array_filter($actual, fn ($v) => filled($v)))),
            'empty' => blank($actual) || (is_array($actual) && empty(array_filter($actual, fn ($v) => filled($v)))),
            default => true,
        };
    }

    protected function conditionEquals(mixed $actual, mixed $expected): bool
    {
        if (is_array($actual)) {
            return in_array($expected, $actual, true)
                || (is_array($expected) && count(array_intersect($actual, $expected)) > 0);
        }

        return ((string) $actual) === ((string) (is_array($expected) ? ($expected[0] ?? '') : $expected));
    }

    /**
     * Kandidat pemicu: field form sejenjang tanpa kondisi (maks 1 level, anti siklus).
     */
    public static function conditionTriggers(string $jenjang, ?int $exceptId = null)
    {
        return PpdbFormField::forJenjang($jenjang)->ordered()->get()->filter(
            fn (PpdbFormField $f) => ! $f->hasCondition() && $f->type !== 'file'
        )->values();
    }
}
