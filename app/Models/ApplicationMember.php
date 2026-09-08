<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationMember extends Model
{
    use HasFactory;

    protected $table = 'application_members';

    protected $fillable = [
        'application_id',
        'name',
        'nim',
        'school',
        'major',
        'phone',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}