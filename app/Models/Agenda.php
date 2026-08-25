<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = [
        'period_id', 'unit_id', 'type', 'coach_id',
        'title', 'description', 'date', 'time', 'location', 'created_by',
    ];
    public $timestamps = true;
    const UPDATED_AT = null;
    public function period() { return $this->belongsTo(Period::class, 'period_id'); }
    public function unit() { return $this->belongsTo(Unit::class, 'unit_id'); }
    public function coach() { return $this->belongsTo(Coach::class, 'coach_id'); } 
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function attendances() { return $this->hasMany(Attendance::class, 'agenda_id'); }
    public function permissions() { return $this->hasMany(Permission::class, 'agenda_id'); }
    public function galleries() { return $this->hasMany(Gallery::class, 'agenda_id'); }

}