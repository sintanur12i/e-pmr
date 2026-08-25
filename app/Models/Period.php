<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $fillable = ['name', 'start_date', 'end_date', 'status'];
    public $timestamps = true;
    const UPDATED_AT = null;

    public function registrations() { return $this->hasMany(Registration::class, 'period_id'); }
    public function managements() { return $this->hasMany(Management::class, 'period_id'); }
    public function agendas() { return $this->hasMany(Agenda::class, 'period_id'); }
    public function memberUnits() { return $this->hasMany(MemberUnit::class, 'period_id'); }
}
