<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = ['title', 'description', 'file', 'category', 'uploaded_by', 'date'];
    public $timestamps = true;
    const UPDATED_AT = null;
    
    public function uploader() { return $this->belongsTo(Coach::class, 'uploaded_by'); }
}
