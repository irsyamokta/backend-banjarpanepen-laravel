<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Gallery extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($model) => $model->id = (string) Str::uuid());
    }

    protected $fillable = ['title', 'caption', 'public_id', 'image'];
}
