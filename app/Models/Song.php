<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = ['title'];

    public function albums()
    {
        return $this->belongsToMany(Album::class)->withPivot('track_number');
    }
}

