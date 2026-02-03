<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{
    /**
     * List team members
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $agencyId = $user->agency_id;

        $members = User::with('agency')
            ->when(!$user->isSuperAdmin(), function ($query) use ($agencyId) {
                $query->where('agency_id', $agencyId);
            })
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->where('role', $request->role);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('full_name', 'like', '%' . $request->search . '%')
                      ->orWhere('username', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate(20);

        return view('team.index', compact('members'));
    }

    /**
     * Show create member form
     */
    public function create()
    {
        return view('team.create');
    }

    /**
     * Store new team member
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|min:8|max:20|unique:users,username',
            'email' => 'nullable|email|max:255|unique:users,email',
            'role' => ['required', Rule::in(['admin', 'accountant', 'agent'])],
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Normalize phone number
        $phone = $this->normalizePhoneNumber($request->phone);

        // Determine agency
        $agencyId = $user->isSuperAdmin() ? $request->agency_id : $user->agency_id;

        // Only super admin can create other admins
        if ($request->role === 'admin' && !$user->isSuperAdmin() && !$user->isAdmin()) {
            abort(403);
        }

        $member = User::create([
            'agency_id' => $agencyId,
            'username' => $phone,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
        ]);

        ActivityLog::log('user_created', 'Created new team member', [
            'member_name' => $member->full_name,
            'role' => $member->role,
        ], $member);

        return redirect()->route('team.index')
            ->with('success', __('messages.team.created'));
    }

    /**
     * Show edit member form
     */
    public function edit(Request $request, User $member)
    {
        $user = $request->user();

        // Check access
        if (!$user->isSuperAdmin() && $member->agency_id !== $user->agency_id) {
            abort(403);
        }

        // Can't edit super admin
        if ($member->isSuperAdmin() && !$user->isSuperAdmin()) {
            abort(403);
        }

        return view('team.edit', compact('member'));
    }

    /**
     * Update team member
     */
    public function update(Request $request, User $member)
    {
        $user = $request->user();

        if (!$user->isSuperAdmin() && $member->agency_id !== $user->agency_id) {
            abort(403);
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $member->id,
            'role' => ['required', Rule::in(['admin', 'accountant', 'agent'])],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $member->update($data);

        ActivityLog::log('user_updated', 'Updated team member', [
            'member_name' => $member->full_name,
        ], $member);

        return redirect()->route('team.index')
            ->with('success', __('messages.team.updated'));
    }

    /**
     * Toggle member status
     */
    public function toggleStatus(Request $request, User $member)
    {
        $user = $request->user();

        if (!$user->isSuperAdmin() && $member->agency_id !== $user->agency_id) {
            abort(403);
        }

        // Can't deactivate yourself
        if ($member->id === $user->id) {
            return back()->withErrors([
                'error' => __('messages.team.cannot_deactivate_self')
            ]);
        }

        $member->update([
            'is_active' => !$member->is_active
        ]);

        ActivityLog::log(
            $member->is_active ? 'user_activated' : 'user_deactivated',
            'Changed team member status',
            ['member_name' => $member->full_name],
            $member
        );

        return back()->with('success', __('messages.team.status_updated'));
    }

    /**
     * Delete team member
     */
    public function destroy(Request $request, User $member)
    {
        $user = $request->user();

        if (!$user->isSuperAdmin() && $member->agency_id !== $user->agency_id) {
            abort(403);
        }

        // Can't delete yourself
        if ($member->id === $user->id) {
            return back()->withErrors([
                'error' => __('messages.team.cannot_delete_self')
            ]);
        }

        $memberName = $member->full_name;
        $member->delete();

        ActivityLog::log('user_deleted', 'Deleted team member', [
            'member_name' => $memberName,
        ]);

        return redirect()->route('team.index')
            ->with('success', __('messages.team.deleted'));
    }

    /**
     * Normalize phone number
     */
    private function normalizePhoneNumber(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($phone) === 8) {
            $phone = '965' . $phone;
        }

        $phone = ltrim($phone, '0');

        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        return $phone;
    }
}
