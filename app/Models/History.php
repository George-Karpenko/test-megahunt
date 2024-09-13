<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class History extends Model
{
    use HasFactory,  HasUuids, SoftDeletes;

    protected $fillable = [
        'before',
        'after',
        'action',
    ];

    public function historytable(): MorphTo
    {
        return $this->morphTo();
    }


    public function histories(): MorphMany
    {
        return $this->morphMany(History::class, 'model', 'model_name', 'model_id');
    }
}
