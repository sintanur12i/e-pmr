<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username', 'password', 'full_name', 'email',
        'profile_photo', 'role', 'is_active',
    ];
    protected $hidden = ['password', 'remember_token'];

    public $timestamps = true;
    const UPDATED_AT = null;

    public function member() { return $this->hasOne(Member::class, 'user_id'); }
    public function coach() { return $this->hasOne(Coach::class, 'user_id'); }
    public function registration() { return $this->hasOne(Registration::class, 'user_id'); }
    public function createdAgendas() { return $this->hasMany(Agenda::class, 'created_by'); }
    public function uploadedGalleries() { return $this->hasMany(Gallery::class, 'uploaded_by'); }
    public function approvedPermissions() { return $this->hasMany(Permission::class, 'approved_by'); }
}