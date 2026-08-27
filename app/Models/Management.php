<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Management extends Model
{
    protected $table = 'managements';
    protected $fillable = ['member_id', 'period_id', 'position', 'is_active'];
    public $timestamps = true;
    const UPDATED_AT = null;
    public function member() { return $this->belongsTo(Member::class, 'member_id'); }
    public function period() { return $this->belongsTo(Period::class, 'period_id'); }
}
