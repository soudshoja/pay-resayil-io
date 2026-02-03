<?php

namespace App\Http\Controllers;

use App\Models\MyfatoorahCredential;
use App\Models\WebhookConfig;
use App\Models\ActivityLog;
use App\Services\MyFatoorahService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    /**
     * Show settings page
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $agency = $user->agency;

        $myfatoorahCredential = $agency?->myfatoorahCredential;
        $webhooks = $agency?->webhookConfigs ?? collect();

        return view('settings.index', compact('agency', 'myfatoorahCredential', 'webhooks'));
    }

    /**
     * Show MyFatoorah settings
     */
    public function myfatoorah(Request $request)
    {
        $user = $request->user();
        $agency = $user->agency;
        $credential = $agency?->myfatoorahCredential;

        return view('settings.myfatoorah', compact('agency', 'credential'));
    }

    /**
     * Update MyFatoorah credentials
     */
    public function updateMyfatoorah(Request $request)
    {
        $request->validate([
            'api_key' => 'required|string|min:20',
            'country_code' => 'required|string|size:3',
            'is_test_mode' => 'boolean',
        ]);

        $user = $request->user();
        $agency = $user->agency;

        // Try to verify credentials
        try {
            $myfatoorah = new MyFatoorahService();
            // We'd test by making an InitiatePayment call
            // For now, just save

            $credential = MyfatoorahCredential::updateOrCreate(
                ['agency_id' => $agency->id],
                [
                    'api_key' => $request->api_key,
                    'country_code' => strtoupper($request->country_code),
                    'is_test_mode' => $request->boolean('is_test_mode', true),
                    'is_active' => true,
                    'last_verified_at' => now(),
                ]
            );

            ActivityLog::log('myfatoorah_updated', 'Updated MyFatoorah credentials', [
                'test_mode' => $credential->is_test_mode,
            ], $credential);

            return redirect()->route('settings.myfatoorah')
                ->with('success', __('messages.settings.myfatoorah_updated'));

        } catch (\Exception $e) {
            return back()->withErrors([
                'api_key' => __('messages.settings.myfatoorah_invalid') . ': ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Test MyFatoorah credentials
     */
    public function testMyfatoorah(Request $request)
    {
        $user = $request->user();
        $agency = $user->agency;

        if (!$agency->hasValidCredentials()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.settings.no_credentials')
            ]);
        }

        try {
            $myfatoorah = new MyFatoorahService($agency->id);
            $response = $myfatoorah->initiatePayment(1, 'KWD');

            $agency->myfatoorahCredential->markAsVerified();

            return response()->json([
                'success' => true,
                'message' => __('messages.settings.myfatoorah_valid'),
                'methods' => count($response['Data']['PaymentMethods'] ?? [])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Show webhooks settings
     */
    public function webhooks(Request $request)
    {
        $user = $request->user();
        $agency = $user->agency;
        $webhooks = $agency?->webhookConfigs ?? collect();

        return view('settings.webhooks', compact('agency', 'webhooks'));
    }

    /**
     * Store webhook config
     */
    public function storeWebhook(Request $request)
    {
        $request->validate([
            'webhook_type' => 'required|in:incoming_whatsapp,payment_callback,n8n_trigger,custom',
            'endpoint_url' => 'required|url|max:500',
        ]);

        $user = $request->user();
        $agency = $user->agency;

        $webhook = WebhookConfig::create([
            'agency_id' => $agency->id,
            'webhook_type' => $request->webhook_type,
            'endpoint_url' => $request->endpoint_url,
            'secret_key' => Str::random(32),
            'is_active' => true,
        ]);

        ActivityLog::log('webhook_created', 'Created webhook configuration', [
            'type' => $webhook->webhook_type,
        ], $webhook);

        return redirect()->route('settings.webhooks')
            ->with('success', __('messages.settings.webhook_created'));
    }

    /**
     * Delete webhook config
     */
    public function destroyWebhook(Request $request, WebhookConfig $webhook)
    {
        $user = $request->user();

        if (!$user->isSuperAdmin() && $webhook->agency_id !== $user->agency_id) {
            abort(403);
        }

        $webhook->delete();

        ActivityLog::log('webhook_deleted', 'Deleted webhook configuration');

        return redirect()->route('settings.webhooks')
            ->with('success', __('messages.settings.webhook_deleted'));
    }

    /**
     * Toggle webhook status
     */
    public function toggleWebhook(Request $request, WebhookConfig $webhook)
    {
        $user = $request->user();

        if (!$user->isSuperAdmin() && $webhook->agency_id !== $user->agency_id) {
            abort(403);
        }

        $webhook->update([
            'is_active' => !$webhook->is_active
        ]);

        return back()->with('success', __('messages.settings.webhook_updated'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $request->user()->id,
            'preferred_locale' => 'required|in:en,ar',
        ]);

        $user = $request->user();
        $user->update([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'preferred_locale' => $request->preferred_locale,
        ]);

        ActivityLog::log('profile_updated', 'Updated profile');

        return back()->with('success', __('messages.settings.profile_updated'));
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $request->user()->update([
            'password' => bcrypt($request->password)
        ]);

        ActivityLog::log('password_changed', 'Changed password');

        return back()->with('success', __('messages.settings.password_changed'));
    }
}
