<?php

namespace App\Exports;

use App\Models\PpdbFormField;
use App\Models\PpdbRegistration;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RegistrationsExport implements FromCollection, WithHeadings
{
    protected array $filters;
    protected Collection $fields;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
        $this->fields = PpdbFormField::when(
            ! empty($filters['jenjang']),
            fn ($q) => $q->where('jenjang', $filters['jenjang'])
        )->orderBy('jenjang')->ordered()->get();
    }

    public function collection(): \Illuminate\Support\Enumerable
    {
        $regs = PpdbRegistration::with('period')
            ->when($this->filters['period_id'] ?? null, fn ($q, $v) => $q->where('period_id', $v))
            ->when($this->filters['jenjang'] ?? null, fn ($q, $v) => $q->where('jenjang', $v))
            ->when($this->filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->orderBy('id')
            ->get();

        return $regs->map(function (PpdbRegistration $r) {
            // Cegah formula injection saat dibuka di Excel.
            $safe = fn ($v) => is_string($v) && preg_match('/^[=+\-@]/', $v) ? "'".$v : $v;
            $row = [
                $r->registration_no,
                $r->period?->name.' ('.strtoupper($r->jenjang).')',
                $r->child_name,
                $r->child_birthdate?->format('Y-m-d'),
                $r->gender,
                $r->parent_name,
                $r->whatsapp,
                $r->statusLabel(),
                $r->created_at->format('Y-m-d H:i'),
            ];
            foreach ($this->fields as $f) {
                $val = $r->answers[$f->key] ?? null;
                $row[] = $safe(is_array($val) ? implode('; ', $val) : $val);
            }

            return array_map($safe, $row);
        });
    }

    public function headings(): array
    {
        $head = ['No Registrasi', 'Periode', 'Nama Anak', 'Tgl Lahir', 'JK', 'Orang Tua', 'WhatsApp', 'Status', 'Mendaftar'];
        foreach ($this->fields as $f) {
            $head[] = $f->label.' ['.$f->jenjang.']';
        }

        return $head;
    }
}
