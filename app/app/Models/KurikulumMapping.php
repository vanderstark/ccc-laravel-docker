<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KurikulumMapping extends Model
{
    protected $fillable = ['kurikulum_level_id', 'tipe_skenario', 'kode_skenario', 'nama_skenario', 'jam_pelatihan', 'objektif'];

    public function level(): BelongsTo
    {
        return $this->belongsTo(KurikulumLevel::class, 'kurikulum_level_id');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(KurikulumProgress::class, 'kurikulum_mapping_id');
    }
}