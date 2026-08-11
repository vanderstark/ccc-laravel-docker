<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = ['code', 'nama', 'jenis', 'deskripsi'];

    public function simulations()
    {
        return $this->hasMany(Simulation::class);
    }
}
