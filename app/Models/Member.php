<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Member extends Model
{
    protected $fillable = [
        'user_id', 'student_id', 'class', 'generation',
        'phone_number', 'address', 'membership_status',
    ];
    public $timestamps = true;
    const UPDATED_AT = null;

    public function user() { 
        return $this->belongsTo(User::class, 'user_id');
     }
    public function managements() { return $this->hasMany(Management::class, 'member_id'); }
    public function memberUnits() { return $this->hasMany(MemberUnit::class, 'member_id'); }
    public function attendances() { return $this->hasMany(Attendance::class, 'member_id'); }
    public function permissions() { return $this->hasMany(Permission::class, 'member_id'); }
    public function certificates() { return $this->hasMany(Certificate::class, 'member_id'); }

    /**
     * Menonaktifkan anggota sekaligus semua turunannya:
     * - status keanggotaan jadi 'inactive'
     * - jabatan kepengurusan yang masih aktif jadi tidak aktif
     * - keanggotaan unit yang masih berjalan jadi 'left', pengajuan unit yang masih pending jadi 'rejected'
     */
    public function deactivate(): void
    {
        DB::transaction(function () {
            $this->update(['membership_status' => 'inactive']);

            Management::where('member_id', $this->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $today = now('Asia/Jakarta')->toDateString();

            MemberUnit::where('member_id', $this->id)
                ->whereIn('status', ['approved', 'exit_requested'])
                ->update(['status' => 'left', 'decision_date' => $today]);

            MemberUnit::where('member_id', $this->id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected', 'decision_date' => $today]);
        });
    }
}