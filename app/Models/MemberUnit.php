<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberUnit extends Model
{
    protected $fillable = [
        'member_id', 'unit_id', 'period_id', 'status',
        'application_date', 'decision_date',
    ];
    public $timestamps = true;
    const UPDATED_AT = null;
    public function member() { return $this->belongsTo(Member::class, 'member_id'); }
    public function unit() { return $this->belongsTo(Unit::class, 'unit_id'); }
    public function period() { return $this->belongsTo(Period::class, 'period_id'); }
}
