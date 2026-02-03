<?php

return [

    // Authentication
    'auth' => [
        'subtitle' => 'Payment Collection Platform',
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
        'sent' => 'Verification code sent',
        'verified' => 'Verified successfully',
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
        'agents' => 'Agents',
        'sales_persons' => 'Sales Persons',
        'accountants' => 'Accountants',
        'transactions' => 'Transactions',
        'keywords' => 'Keywords',
        'clients' => 'Clients',
        'users' => 'Users',
        'logs' => 'Activity Logs',
    ],

    // Roles
    'roles' => [
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'accountant' => 'Accountant',
        'agent' => 'Agent',
        'platform_owner' => 'Platform Owner',
        'client_admin' => 'Client Admin',
        'sales_person' => 'Sales Person',
    ],

    // Stats
    'stats' => [
        'total_agents' => 'Total Agents',
        'total_transactions' => 'Total Transactions',
        'total_revenue' => 'Total Revenue',
        'pending_payments' => 'Pending Payments',
        'total_clients' => 'Total Clients',
        'total_users' => 'Total Users',
        'today_transactions' => 'Today\'s Transactions',
        'today_revenue' => 'Today\'s Revenue',
    ],

    // Common Words
    'active' => 'Active',
    'inactive' => 'Inactive',
    'view_all' => 'View All',
    'recent_transactions' => 'Recent Transactions',
    'recent_agents' => 'Recent Agents',
    'no_transactions' => 'No transactions found',
    'no_agents' => 'No agents found',
    'resayil_iframe_note' => 'Manage your WhatsApp conversations directly from this dashboard',

    // Agent CRUD
    'agent_created' => 'Agent created successfully',
    'agent_updated' => 'Agent updated successfully',
    'agent_deleted' => 'Agent deleted successfully',
    'phone_added' => 'Phone number added successfully',
    'phone_removed' => 'Phone number removed successfully',
    'phone_not_authorized' => 'This phone number is not authorized',
    'agent_inactive' => 'This agent account is inactive',

    // Sales Person CRUD
    'sales_person_created' => 'Sales person created successfully',
    'sales_person_updated' => 'Sales person updated successfully',
    'sales_person_deleted' => 'Sales person deleted successfully',

    // Accountant CRUD
    'accountant_created' => 'Accountant created successfully',
    'accountant_updated' => 'Accountant updated successfully',
    'accountant_deleted' => 'Accountant deleted successfully',

    // Keywords
    'keyword_created' => 'Keyword created successfully',
    'keyword_updated' => 'Keyword updated successfully',
    'keyword_deleted' => 'Keyword deleted successfully',

    // Notes
    'note_added' => 'Note added successfully',

    // Settings
    'settings_updated' => 'Settings updated successfully',

    // Payment Page
    'payment' => [
        'title' => 'Payment',
        'amount' => 'Amount',
        'service_fee' => 'Service Fee',
        'total' => 'Total',
        'invoice_id' => 'Invoice ID',
        'created' => 'Created',
        'pay_now_knet' => 'Pay Now with KNET',
        'secure_note' => 'Your payment is secured with bank-grade encryption',
        'powered_by' => 'Powered by',
        'success_title' => 'Payment Successful',
        'success_heading' => 'Payment Successful!',
        'success_message' => 'Your payment has been processed successfully.',
        'reference' => 'Reference',
        'date' => 'Date',
        'agent' => 'Agent',
        'return_whatsapp' => 'Return to WhatsApp',
        'receipt_sent' => 'A receipt has been sent to your WhatsApp',
        'failed_title' => 'Payment Failed',
        'failed_heading' => 'Payment Failed',
        'failed_message' => 'We were unable to process your payment. Please try again.',
        'try_again' => 'Try Again',
        'need_help' => 'Need help?',
        'contact_support' => 'Contact Support',
    ],

    // Email OTP
    'email' => [
        'otp_subject' => 'Your Verification Code - Collect Resayil',
        'otp_greeting' => 'Hello,',
        'otp_line1' => 'Your verification code is:',
        'otp_line2' => 'This code will expire in :minutes minutes.',
        'otp_line3' => 'If you did not request this code, please ignore this email.',
        'otp_thanks' => 'Thank you,',
        'otp_team' => 'Collect Resayil Team',
    ],

    // Footer
    'footer' => [
        'rights' => 'All rights reserved',
        'powered_by' => 'Powered by',
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

    // Payments (legacy)
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
        'status' => 'Status',
        'date' => 'Date',
        'no_payments' => 'No payments found',
        'created' => 'Payment link created successfully',
    ],

    // Status
    'status' => [
        'pending' => 'Pending',
        'paid' => 'Paid',
        'failed' => 'Failed',
        'expired' => 'Expired',
        'cancelled' => 'Cancelled',
        'confirmed' => 'Confirmed',
    ],

    // Team (legacy)
    'team' => [
        'title' => 'Team Members',
        'subtitle' => 'Manage your team',
        'add_member' => 'Add Member',
        'no_members' => 'No team members found',
    ],

    // Settings (legacy)
    'settings' => [
        'title' => 'Settings',
        'profile' => 'Profile Settings',
        'language' => 'Language',
        'change_password' => 'Change Password',
        'myfatoorah' => 'MyFatoorah Settings',
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
        'confirm_action' => 'Are you sure?',
        'confirm_delete' => 'Are you sure you want to delete this?',
        'search' => 'Search...',
        'export' => 'Export',
        'export_csv' => 'Export CSV',
    ],

    // Errors
    'errors' => [
        'unauthorized' => 'You are not authorized to perform this action',
        'not_found' => 'Resource not found',
    ],
];
