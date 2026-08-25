<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permission'; // WAJIB, karena tabelnya singular bukan "permissions"
    protected $fillable = [
        'agenda_id', 'member_id', 'registration_id', 'reason',
        'proof', 'status', 'approved_by',
    ];
    public $timestamps = true;
    const UPDATED_AT = null;
    public function agenda() { return $this->belongsTo(Agenda::class, 'agenda_id'); }
    public function member() { return $this->belongsTo(Member::class, 'member_id'); }
    public function registration() { return $this->belongsTo(Registration::class, 'registration_id'); }
    public function approver() { return $this->belongsTo(User::class, 'approved_by'); }
}
