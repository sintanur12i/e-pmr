<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    protected $fillable = ['user_id', 'name', 'phone_number', 'specialization', 'origin'];
    public $timestamps = true;
    const UPDATED_AT = null;
    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function units() { return $this->hasMany(Unit::class, 'coach_id'); }
    public function agendas() { return $this->hasMany(Agenda::class, 'coach_id'); }
    public function materials() { return $this->hasMany(Material::class, 'uploaded_by'); }
}
