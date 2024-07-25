<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;
    public function film()
    {
        return $this->belongToMany(Film::class,'genre_film','id_genre','id_film');
    }
}
