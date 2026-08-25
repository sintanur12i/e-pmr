<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['agenda_id', 'photo', 'caption', 'uploaded_by'];
    public $timestamps = true;
    const UPDATED_AT = null;

    public function agenda() { return $this->belongsTo(Agenda::class, 'agenda_id'); }
    public function uploader() { return $this->belongsTo(User::class, 'uploaded_by'); } // ini FK ke users
}
