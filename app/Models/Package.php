<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Package extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'days', 'price', 'active',
    ];

    public function scopeSearch(Builder $query, Request $request)
    {
        return
            $query->when($name = $request->get('name', false), function ($query) use ($name) {
                $query->where('name', 'like', "%$name%");
            });
    }

    public function getActiveStatusAttribute()
    {
        return $this->active ? 'فعالة':'غير فعالة';
    }
}
