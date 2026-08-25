<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['name', 'description', 'coach_id'];
    public $timestamps = true;
    const UPDATED_AT = null;
    public function coach() { return $this->belongsTo(Coach::class, 'coach_id'); }
    public function memberUnits() { return $this->hasMany(MemberUnit::class, 'unit_id'); }
    public function agendas() { return $this->hasMany(Agenda::class, 'unit_id'); }
}
