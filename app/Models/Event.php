<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($model) => $model->id = (string) Str::uuid());
    }

    protected $fillable = [
        'title', 'description', 'date', 'time', 'place',
        'price', 'public_id', 'thumbnail'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];
}
