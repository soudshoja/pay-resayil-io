<?php

return [

    // Authentication
    'auth' => [
        'subtitle' => 'Payment Gateway Management',
        'welcome' => 'Welcome Back',
        'login_subtitle' => 'Sign in with your phone number',
        'phone' => 'Phone Number',
        'send_otp' => 'Send Verification Code',
        'otp_info' => 'We will send you a verification code via WhatsApp',
        'verify_otp' => 'Verify Code',
        'otp_sent_to' => 'Code sent to :phone',
        'enter_otp' => 'Verification Code',
        'verify' => 'Verify & Login',
        'resend_in' => 'Resend code in',
        'seconds' => 'seconds',
        'resend_otp' => 'Resend Code',
        'back_to_login' => 'Back to login',
        'logout' => 'Logout',
        'logged_out' => 'You have been logged out',
        'welcome_back' => 'Welcome back, :name!',
        'login_success' => 'Login successful',
        'user_not_found' => 'User not found or inactive',
    ],

    // OTP Messages
    'otp' => [
        'sent' => 'Verification code sent via WhatsApp',
        'verified' => 'Phone verified successfully',
        'invalid' => 'Invalid verification code',
        'expired' => 'Verification code has expired',
        'max_attempts' => 'Maximum attempts exceeded. Please request a new code',
        'cooldown' => 'Please wait :seconds seconds before requesting a new code',
        'send_failed' => 'Failed to send verification code. Please try again',
    ],

    // Navigation
    'nav' => [
        'dashboard' => 'Dashboard',
        'payments' => 'Payments',
        'team' => 'Team',
        'agencies' => 'Agencies',
        'settings' => 'Settings',
    ],

    // Dashboard
    'dashboard' => [
        'revenue_today' => 'Revenue Today (KWD)',
        'paid_today' => 'Paid Today',
        'pending' => 'Pending',
        'revenue_month' => 'Monthly Revenue (KWD)',
        'weekly_overview' => 'Weekly Overview',
        'recent_payments' => 'Recent Payments',
        'quick_actions' => 'Quick Actions',
        'view_pending' => 'View Pending',
    ],

    // Payments
    'payments' => [
        'title' => 'Payments',
        'subtitle' => 'Manage payment requests',
        'new' => 'New Payment',
        'create_title' => 'Create Payment',
        'create_subtitle' => 'Generate a new payment link',
        'create_button' => 'Create Payment Link',
        'amount' => 'Amount',
        'customer' => 'Customer',
        'customer_phone' => 'Customer Phone',
        'customer_name' => 'Customer Name',
        'customer_name_placeholder' => 'Optional customer name',
        'description' => 'Description',
        'description_placeholder' => 'Optional payment description',
        'send_whatsapp' => 'Send payment link via WhatsApp',
        'status' => 'Status',
        'date' => 'Date',
        'details' => 'Payment Details',
        'invoice_id' => 'Invoice ID',
        'reference_id' => 'Reference ID',
        'created_at' => 'Created At',
        'paid_at' => 'Paid At',
        'created_by' => 'Created By',
        'payment_link' => 'Payment Link',
        'resend_whatsapp' => 'Resend via WhatsApp',
        'cancel' => 'Cancel Payment',
        'no_payments' => 'No payments found',
        'created' => 'Payment link created successfully',
        'link_resent' => 'Payment link sent via WhatsApp',
        'cancelled' => 'Payment cancelled',
        'cannot_resend' => 'Cannot resend link for non-pending payment',
        'cannot_cancel' => 'Cannot cancel non-pending payment',
        'no_credentials' => 'MyFatoorah credentials not configured',
        'success_title' => 'Payment Successful',
        'success_message' => 'Your payment has been processed successfully',
        'success_note' => 'You will receive a confirmation via WhatsApp shortly',
        'error_title' => 'Payment Failed',
        'error_message' => 'Your payment could not be processed',
        'error_note' => 'Please try again or contact the agency',
        'close_window' => 'You can close this window',
        'contact_support' => 'If the problem persists, contact the agency',
        'processing_error' => 'Payment processing error',
        'invalid_id' => 'Invalid payment ID',
        'not_found' => 'Payment not found',
        'search_placeholder' => 'Search by phone, name, or invoice...',
        'all_status' => 'All Status',
    ],

    // Status
    'status' => [
        'pending' => 'Pending',
        'paid' => 'Paid',
        'failed' => 'Failed',
        'expired' => 'Expired',
        'cancelled' => 'Cancelled',
    ],

    // Team
    'team' => [
        'title' => 'Team Members',
        'subtitle' => 'Manage your agency team',
        'add_member' => 'Add Member',
        'no_members' => 'No team members found',
        'full_name' => 'Full Name',
        'phone' => 'Phone Number',
        'email' => 'Email',
        'role' => 'Role',
        'password' => 'Password',
        'password_confirm' => 'Confirm Password',
        'create_button' => 'Create Member',
        'created' => 'Team member created successfully',
        'updated' => 'Team member updated successfully',
        'deleted' => 'Team member deleted',
        'status_updated' => 'Status updated',
        'cannot_deactivate_self' => 'You cannot deactivate your own account',
        'cannot_delete_self' => 'You cannot delete your own account',
    ],

    // Roles
    'roles' => [
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'accountant' => 'Accountant',
        'agent' => 'Agent',
    ],

    // Agencies
    'agencies' => [
        'title' => 'Agencies',
        'created' => 'Agency created successfully',
        'updated' => 'Agency updated successfully',
        'deleted' => 'Agency deleted',
        'status_updated' => 'Agency status updated',
    ],

    // Settings
    'settings' => [
        'title' => 'Settings',
        'profile' => 'Profile Settings',
        'language' => 'Language',
        'change_password' => 'Change Password',
        'current_password' => 'Current Password',
        'new_password' => 'New Password',
        'confirm_password' => 'Confirm Password',
        'update_password' => 'Update Password',
        'myfatoorah' => 'MyFatoorah Settings',
        'myfatoorah_desc' => 'Configure payment gateway credentials',
        'webhooks' => 'Webhooks',
        'webhooks_desc' => 'Configure n8n and API webhooks',
        'api_key' => 'API Key',
        'country_code' => 'Country',
        'mode' => 'Mode',
        'test_mode' => 'Test Mode',
        'live_mode' => 'Live Mode',
        'test_connection' => 'Test Connection',
        'last_verified' => 'Last verified',
        'myfatoorah_updated' => 'MyFatoorah credentials updated',
        'myfatoorah_invalid' => 'Invalid MyFatoorah credentials',
        'myfatoorah_valid' => 'Credentials verified successfully',
        'no_credentials' => 'No credentials configured',
        'profile_updated' => 'Profile updated',
        'password_changed' => 'Password changed successfully',
        'webhook_created' => 'Webhook created',
        'webhook_deleted' => 'Webhook deleted',
        'webhook_updated' => 'Webhook updated',
        'no_webhooks' => 'No webhooks configured',
        'triggered' => 'Triggered',
        'last_trigger' => 'Last trigger',
    ],

    // Common
    'common' => [
        'view_all' => 'View All',
        'back' => 'Back',
        'save' => 'Save',
        'cancel' => 'Cancel',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'view' => 'View',
        'copy' => 'Copy',
        'filter' => 'Filter',
        'add' => 'Add',
        'actions' => 'Actions',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'activate' => 'Activate',
        'deactivate' => 'Deactivate',
        'confirm_action' => 'Are you sure?',
        'confirm_delete' => 'Are you sure you want to delete this?',
    ],

    // Footer
    'footer' => [
        'rights' => 'All rights reserved',
        'powered_by' => 'Powered by',
    ],

    // Errors
    'errors' => [
        'unauthorized' => 'You are not authorized to perform this action',
        'no_agency' => 'You are not assigned to any agency',
        'agency_inactive' => 'Your agency is currently inactive',
    ],

    // Activity Log
    'activity' => [
        'login' => 'Logged in',
        'logout' => 'Logged out',
        'payment_created' => 'Created payment',
        'payment_paid' => 'Payment completed',
        'user_created' => 'Created user',
        'settings_updated' => 'Updated settings',
    ],
];
