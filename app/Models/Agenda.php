<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = [
        'title', 'description', 'date', 'time_label', 'place', 'is_published',
    ];

    protected function casts(): array
    {
        return ['date' => 'date', 'is_published' => 'boolean'];
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->published()->where('date', '>=', today())->orderBy('date');
    }
}
