<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KurikulumLevel extends Model
{
    protected $fillable = ['nama', 'tingkat', 'deskripsi', 'durasi_hari'];

    public function mappings(): HasMany
    {
        return $this->hasMany(KurikulumMapping::class, 'kurikulum_level_id');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(KurikulumProgress::class, 'kurikulum_level_id');
    }
}