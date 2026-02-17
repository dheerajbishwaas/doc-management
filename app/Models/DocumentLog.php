<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentLog extends Model
{
    protected $fillable = [
        'document_id',
        'action',
        'performed_by',
    ];
}
