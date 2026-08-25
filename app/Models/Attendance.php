<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'agenda_id', 'member_id', 'registration_id', 'status', 'attendance_time',
    ];
    public $timestamps = true;
    const UPDATED_AT = null;
    public function agenda() { return $this->belongsTo(Agenda::class, 'agenda_id'); }
    public function member() { return $this->belongsTo(Member::class, 'member_id'); }
    public function registration() { return $this->belongsTo(Registration::class, 'registration_id'); }
}