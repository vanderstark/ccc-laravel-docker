<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderBoard extends Model
{
    protected $fillable = [
        'session_id', 'nomor', 'jenis', 'tujuan_satker',
        'isi', 'status', 'dibuat_oleh', 'dikirim_pada',
    ];

    protected $casts = [
        'dikirim_pada' => 'datetime',
    ];

    public const STATUS = ['draft', 'dikirim', 'dibaca', 'dilaksanakan', 'selesai'];

    public function session(): BelongsTo
    {
        return $this->belongsTo(ExerciseSession::class, 'session_id');
    }

    public function maker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}