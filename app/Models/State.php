<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'is_active'];

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
