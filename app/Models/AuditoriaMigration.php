<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditoriaMigration extends Model
{
    use HasFactory;

    protected $fillable = [
        'migration'
    ];
}
