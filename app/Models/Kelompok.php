<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompok';

    protected $fillable = ['user_id', 'nama'];

    public function ketua(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function anggota(): HasMany
    {
        return $this->hasMany(KelompokAnggota::class);
    }
}
