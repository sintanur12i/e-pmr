<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'user_id', 'full_name', 'class', 'join_reason',
        'period_id', 'status', 'registration_date',
    ];
    public $timestamps = true;
    const UPDATED_AT = null;

    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function period() { return $this->belongsTo(Period::class, 'period_id'); }
    public function attendances() { return $this->hasMany(Attendance::class, 'registration_id'); }
    public function permissions() { return $this->hasMany(Permission::class, 'registration_id'); }
}
