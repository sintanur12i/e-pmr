<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id', 'type', 'period_id', 'unit_id', 'title', 'file', 'issued_by', 'issued_at',
    ];

    public $timestamps = true;
    const UPDATED_AT = null;

    public function member() { return $this->belongsTo(Member::class, 'member_id'); }
    public function period() { return $this->belongsTo(Period::class, 'period_id'); }
    public function unit() { return $this->belongsTo(Unit::class, 'unit_id'); }
    public function issuer() { return $this->belongsTo(User::class, 'issued_by'); }
}