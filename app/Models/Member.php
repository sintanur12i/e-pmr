<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'user_id', 'student_id', 'class', 'generation',
        'phone_number', 'address', 'membership_status',
    ];
    public $timestamps = true;
    const UPDATED_AT = null;

    public function user() { 
        return $this->belongsTo(User::class, 'user_id');
     }
    public function managements() { return $this->hasMany(Management::class, 'member_id'); }
    public function memberUnits() { return $this->hasMany(MemberUnit::class, 'member_id'); }
    public function trainings() { return $this->hasMany(Training::class, 'member_id'); }
    public function attendances() { return $this->hasMany(Attendance::class, 'member_id'); }
    public function permissions() { return $this->hasMany(Permission::class, 'member_id'); }
}
