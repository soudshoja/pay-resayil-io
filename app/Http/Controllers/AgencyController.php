<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\MyfatoorahCredential;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    /**
     * List all agencies (super admin only)
     */
    public function index(Request $request)
    {
        $agencies = Agency::withCount(['users', 'paymentRequests'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('agency_name', 'like', '%' . $request->search . '%')
                      ->orWhere('iata_number', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', $request->status === 'active');
            })
            ->latest()
            ->paginate(20);

        return view('agencies.index', compact('agencies'));
    }

    /**
     * Show create agency form
     */
    public function create()
    {
        return view('agencies.create');
    }

    /**
     * Store new agency
     */
    public function store(Request $request)
    {
        $request->validate([
            'agency_name' => 'required|string|max:255',
            'iata_number' => 'required|string|max:50|unique:agencies',
            'company_email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
            'timezone' => 'nullable|string|max:50',
        ]);

        $agency = Agency::create([
            'agency_name' => $request->agency_name,
            'iata_number' => strtoupper($request->iata_number),
            'company_email' => $request->company_email,
            'phone' => $request->phone,
            'address' => $request->address,
            'timezone' => $request->timezone ?? 'Asia/Kuwait',
            'is_active' => true,
        ]);

        ActivityLog::log('agency_created', 'Created new agency', [
            'agency_name' => $agency->agency_name,
            'iata' => $agency->iata_number,
        ], $agency);

        return redirect()->route('agencies.show', $agency)
            ->with('success', __('messages.agencies.created'));
    }

    /**
     * Show agency details
     */
    public function show(Request $request, Agency $agency)
    {
        $user = $request->user();

        // Non-super admin can only view their own agency
        if (!$user->isSuperAdmin() && $agency->id !== $user->agency_id) {
            abort(403);
        }

        $agency->load(['users', 'myfatoorahCredential']);
        $agency->loadCount('paymentRequests');

        // Get recent payments
        $recentPayments = $agency->paymentRequests()
            ->with('agent')
            ->latest()
            ->take(5)
            ->get();

        return view('agencies.show', compact('agency', 'recentPayments'));
    }

    /**
     * Show edit agency form
     */
    public function edit(Request $request, Agency $agency)
    {
        $user = $request->user();

        if (!$user->isSuperAdmin() && $agency->id !== $user->agency_id) {
            abort(403);
        }

        return view('agencies.edit', compact('agency'));
    }

    /**
     * Update agency
     */
    public function update(Request $request, Agency $agency)
    {
        $user = $request->user();

        if (!$user->isSuperAdmin() && $agency->id !== $user->agency_id) {
            abort(403);
        }

        $request->validate([
            'agency_name' => 'required|string|max:255',
            'iata_number' => 'required|string|max:50|unique:agencies,iata_number,' . $agency->id,
            'company_email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
            'timezone' => 'nullable|string|max:50',
        ]);

        $agency->update([
            'agency_name' => $request->agency_name,
            'iata_number' => strtoupper($request->iata_number),
            'company_email' => $request->company_email,
            'phone' => $request->phone,
            'address' => $request->address,
            'timezone' => $request->timezone ?? 'Asia/Kuwait',
        ]);

        ActivityLog::log('agency_updated', 'Updated agency details', [
            'agency_name' => $agency->agency_name,
        ], $agency);

        return redirect()->route('agencies.show', $agency)
            ->with('success', __('messages.agencies.updated'));
    }

    /**
     * Toggle agency status
     */
    public function toggleStatus(Agency $agency)
    {
        $agency->update([
            'is_active' => !$agency->is_active
        ]);

        ActivityLog::log(
            $agency->is_active ? 'agency_activated' : 'agency_deactivated',
            'Changed agency status',
            ['agency_name' => $agency->agency_name],
            $agency
        );

        return back()->with('success', __('messages.agencies.status_updated'));
    }

    /**
     * Delete agency
     */
    public function destroy(Agency $agency)
    {
        $agencyName = $agency->agency_name;
        $agency->delete();

        ActivityLog::log('agency_deleted', 'Deleted agency', [
            'agency_name' => $agencyName,
        ]);

        return redirect()->route('agencies.index')
            ->with('success', __('messages.agencies.deleted'));
    }
}
