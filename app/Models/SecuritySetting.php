<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecuritySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'two_factor_required',
        'ip_blocking_enabled',
        'max_login_attempts',
        'session_timeout_minutes'
    ];
}