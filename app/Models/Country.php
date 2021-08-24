<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'short_code'];

    public function sports()
    {
        return $this->belongsToMany(Sport::class)
        ->withPivot(['medal']);
    }

    public function scopeWithCountSport($query)
    {
        return $query->withCount([
            'sports as gold_count' => function ($query) {
                $query->where('medal', 1);
            },
            'sports as silver_count' => function ($query) {
                $query->where('medal', 2);
            },
            'sports as bronze_count' => function ($query) {
                $query->where('medal', 3);
            },
        ]);
    }
}
