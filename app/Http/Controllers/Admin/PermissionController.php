<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use App\Support\Notify;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $permissions = Permission::with(['agenda', 'member.user', 'registration'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10);

        return view('admin.permissions.index', compact('permissions', 'status'));
    }

    public function show(Permission $permission)
    {
        $permission->load(['agenda', 'member.user', 'registration']);

        return view('admin.permissions.show', compact('permission'));
    }

    public function approve(Permission $permission)
{
    $permission->update([
        'status'      => 'approved',
        'approved_by' => auth()->id(),
    ]);

    Notify::user(
        $this->requester($permission),
        'Izin disetujui',
        'Izinmu untuk agenda "' . $permission->agenda->title . '" disetujui.',
        route('agendas.index')
    );

    return back()->with('success', 'Izin disetujui.');
}

public function reject(Permission $permission)
{
    $permission->update([
        'status'      => 'rejected',
        'approved_by' => auth()->id(),
    ]);

    Notify::user(
        $this->requester($permission),
        'Izin ditolak',
        'Izinmu untuk agenda "' . $permission->agenda->title . '" ditolak.',
        route('agendas.index')
    );

    return back()->with('success', 'Izin ditolak.');
}

/** Pengaju izin: member atau calon anggota. */
private function requester(Permission $permission): ?User
{
    return $permission->member?->user ?? $permission->registration?->user;
}
}