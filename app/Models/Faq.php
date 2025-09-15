<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'question','answer','category','intent'
    ];

    public function scopeSearch($query, ?string $term)
    {
        if (!$term) return $query;
        $like = '%'.strtolower($term).'%';
        return $query->whereRaw('LOWER(question) LIKE ?', [$like])
                     ->orWhereRaw('LOWER(answer) LIKE ?', [$like])
                     ->orWhereRaw('LOWER(category) LIKE ?', [$like])
                     ->orWhereRaw('LOWER(intent) LIKE ?', [$like]);
    }
}
