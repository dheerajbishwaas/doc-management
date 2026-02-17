<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filename',
        'original_name',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
