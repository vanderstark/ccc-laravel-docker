<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class War extends Model
{
    protected $fillable = [
        'nama', 'tahun', 'wilayah', 'matra', 'kategori', 'pop', 'lat', 'lon', 'deskripsi',
    ];
}