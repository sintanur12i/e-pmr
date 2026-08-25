<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $fillable = [
        'member_id', 'training_name', 'organizer', 'date', 'certificate', 'notes',
    ];
    public $timestamps = true;
    const UPDATED_AT = null;

    public function member() { return $this->belongsTo(Member::class, 'member_id'); }
}
