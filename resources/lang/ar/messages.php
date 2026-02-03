<?php

return [

    // Authentication
    'auth' => [
        'subtitle' => 'منصة تحصيل المدفوعات',
        'welcome' => 'مرحباً بعودتك',
        'login_subtitle' => 'تسجيل الدخول برقم الهاتف',
        'phone' => 'رقم الهاتف',
        'send_otp' => 'إرسال رمز التحقق',
        'otp_info' => 'سنرسل لك رمز التحقق عبر واتساب',
        'verify_otp' => 'تحقق من الرمز',
        'otp_sent_to' => 'تم إرسال الرمز إلى :phone',
        'enter_otp' => 'رمز التحقق',
        'verify' => 'تحقق وسجل الدخول',
        'resend_in' => 'إعادة إرسال الرمز خلال',
        'seconds' => 'ثانية',
        'resend_otp' => 'إعادة إرسال الرمز',
        'back_to_login' => 'العودة لتسجيل الدخول',
        'logout' => 'تسجيل الخروج',
        'logged_out' => 'تم تسجيل الخروج',
        'welcome_back' => 'مرحباً بعودتك، :name!',
        'login_success' => 'تم تسجيل الدخول بنجاح',
        'user_not_found' => 'المستخدم غير موجود أو غير نشط',
    ],

    // OTP Messages
    'otp' => [
        'sent' => 'تم إرسال رمز التحقق',
        'verified' => 'تم التحقق بنجاح',
        'invalid' => 'رمز التحقق غير صحيح',
        'expired' => 'انتهت صلاحية رمز التحقق',
        'max_attempts' => 'تجاوزت الحد الأقصى للمحاولات. يرجى طلب رمز جديد',
        'cooldown' => 'يرجى الانتظار :seconds ثانية قبل طلب رمز جديد',
        'send_failed' => 'فشل إرسال رمز التحقق. يرجى المحاولة مرة أخرى',
    ],

    // Navigation
    'nav' => [
        'dashboard' => 'لوحة التحكم',
        'payments' => 'المدفوعات',
        'team' => 'الفريق',
        'agencies' => 'الوكالات',
        'settings' => 'الإعدادات',
        'agents' => 'الوكلاء',
        'sales_persons' => 'مندوبي المبيعات',
        'accountants' => 'المحاسبين',
        'transactions' => 'المعاملات',
        'keywords' => 'الكلمات المفتاحية',
        'clients' => 'العملاء',
        'users' => 'المستخدمين',
        'logs' => 'سجل النشاط',
    ],

    // Roles
    'roles' => [
        'super_admin' => 'المسؤول الأعلى',
        'admin' => 'المسؤول',
        'accountant' => 'محاسب',
        'agent' => 'وكيل',
        'platform_owner' => 'مالك المنصة',
        'client_admin' => 'مسؤول العميل',
        'sales_person' => 'مندوب مبيعات',
    ],

    // Stats
    'stats' => [
        'total_agents' => 'إجمالي الوكلاء',
        'total_transactions' => 'إجمالي المعاملات',
        'total_revenue' => 'إجمالي الإيرادات',
        'pending_payments' => 'المدفوعات المعلقة',
        'total_clients' => 'إجمالي العملاء',
        'total_users' => 'إجمالي المستخدمين',
        'today_transactions' => 'معاملات اليوم',
        'today_revenue' => 'إيرادات اليوم',
    ],

    // Common Words
    'active' => 'نشط',
    'inactive' => 'غير نشط',
    'view_all' => 'عرض الكل',
    'recent_transactions' => 'المعاملات الأخيرة',
    'recent_agents' => 'الوكلاء الجدد',
    'no_transactions' => 'لا توجد معاملات',
    'no_agents' => 'لا يوجد وكلاء',
    'resayil_iframe_note' => 'إدارة محادثات واتساب مباشرة من لوحة التحكم',

    // Agent CRUD
    'agent_created' => 'تم إنشاء الوكيل بنجاح',
    'agent_updated' => 'تم تحديث الوكيل بنجاح',
    'agent_deleted' => 'تم حذف الوكيل بنجاح',
    'phone_added' => 'تمت إضافة رقم الهاتف بنجاح',
    'phone_removed' => 'تم حذف رقم الهاتف بنجاح',
    'phone_not_authorized' => 'رقم الهاتف هذا غير مصرح به',
    'agent_inactive' => 'حساب الوكيل هذا غير نشط',

    // Sales Person CRUD
    'sales_person_created' => 'تم إنشاء مندوب المبيعات بنجاح',
    'sales_person_updated' => 'تم تحديث مندوب المبيعات بنجاح',
    'sales_person_deleted' => 'تم حذف مندوب المبيعات بنجاح',

    // Accountant CRUD
    'accountant_created' => 'تم إنشاء المحاسب بنجاح',
    'accountant_updated' => 'تم تحديث المحاسب بنجاح',
    'accountant_deleted' => 'تم حذف المحاسب بنجاح',

    // Keywords
    'keyword_created' => 'تم إنشاء الكلمة المفتاحية بنجاح',
    'keyword_updated' => 'تم تحديث الكلمة المفتاحية بنجاح',
    'keyword_deleted' => 'تم حذف الكلمة المفتاحية بنجاح',

    // Notes
    'note_added' => 'تمت إضافة الملاحظة بنجاح',

    // Settings
    'settings_updated' => 'تم تحديث الإعدادات بنجاح',

    // Payment Page
    'payment' => [
        'title' => 'الدفع',
        'amount' => 'المبلغ',
        'service_fee' => 'رسوم الخدمة',
        'total' => 'الإجمالي',
        'invoice_id' => 'رقم الفاتورة',
        'created' => 'تاريخ الإنشاء',
        'pay_now_knet' => 'ادفع الآن عبر كي نت',
        'secure_note' => 'دفعتك مؤمنة بتشفير مصرفي',
        'powered_by' => 'مدعوم من',
        'success_title' => 'تم الدفع بنجاح',
        'success_heading' => 'تم الدفع بنجاح!',
        'success_message' => 'تمت معالجة دفعتك بنجاح.',
        'reference' => 'المرجع',
        'date' => 'التاريخ',
        'agent' => 'الوكيل',
        'return_whatsapp' => 'العودة إلى واتساب',
        'receipt_sent' => 'تم إرسال الإيصال إلى واتساب الخاص بك',
        'failed_title' => 'فشل الدفع',
        'failed_heading' => 'فشل الدفع',
        'failed_message' => 'لم نتمكن من معالجة دفعتك. يرجى المحاولة مرة أخرى.',
        'try_again' => 'حاول مرة أخرى',
        'need_help' => 'تحتاج مساعدة؟',
        'contact_support' => 'اتصل بالدعم',
    ],

    // Email OTP
    'email' => [
        'otp_subject' => 'رمز التحقق الخاص بك - كوليكت رسايل',
        'otp_greeting' => 'مرحباً،',
        'otp_line1' => 'رمز التحقق الخاص بك هو:',
        'otp_line2' => 'ينتهي هذا الرمز خلال :minutes دقائق.',
        'otp_line3' => 'إذا لم تطلب هذا الرمز، يرجى تجاهل هذا البريد.',
        'otp_thanks' => 'شكراً لك،',
        'otp_team' => 'فريق كوليكت رسايل',
    ],

    // Footer
    'footer' => [
        'rights' => 'جميع الحقوق محفوظة',
        'powered_by' => 'مدعوم من',
    ],

    // Dashboard
    'dashboard' => [
        'revenue_today' => 'إيرادات اليوم (د.ك)',
        'paid_today' => 'المدفوع اليوم',
        'pending' => 'معلق',
        'revenue_month' => 'الإيرادات الشهرية (د.ك)',
        'weekly_overview' => 'نظرة أسبوعية',
        'recent_payments' => 'المدفوعات الأخيرة',
        'quick_actions' => 'إجراءات سريعة',
        'view_pending' => 'عرض المعلق',
    ],

    // Payments (legacy)
    'payments' => [
        'title' => 'المدفوعات',
        'subtitle' => 'إدارة طلبات الدفع',
        'new' => 'دفعة جديدة',
        'create_title' => 'إنشاء دفعة',
        'create_subtitle' => 'إنشاء رابط دفع جديد',
        'create_button' => 'إنشاء رابط الدفع',
        'amount' => 'المبلغ',
        'customer' => 'العميل',
        'customer_phone' => 'رقم هاتف العميل',
        'customer_name' => 'اسم العميل',
        'status' => 'الحالة',
        'date' => 'التاريخ',
        'no_payments' => 'لا توجد مدفوعات',
        'created' => 'تم إنشاء رابط الدفع بنجاح',
    ],

    // Status
    'status' => [
        'pending' => 'معلق',
        'paid' => 'مدفوع',
        'failed' => 'فشل',
        'expired' => 'منتهي',
        'cancelled' => 'ملغي',
        'confirmed' => 'مؤكد',
    ],

    // Team (legacy)
    'team' => [
        'title' => 'أعضاء الفريق',
        'subtitle' => 'إدارة فريقك',
        'add_member' => 'إضافة عضو',
        'no_members' => 'لا يوجد أعضاء في الفريق',
    ],

    // Settings (legacy)
    'settings' => [
        'title' => 'الإعدادات',
        'profile' => 'إعدادات الملف الشخصي',
        'language' => 'اللغة',
        'change_password' => 'تغيير كلمة المرور',
        'myfatoorah' => 'إعدادات ماي فاتورة',
    ],

    // Common
    'common' => [
        'view_all' => 'عرض الكل',
        'back' => 'رجوع',
        'save' => 'حفظ',
        'cancel' => 'إلغاء',
        'edit' => 'تعديل',
        'delete' => 'حذف',
        'view' => 'عرض',
        'copy' => 'نسخ',
        'filter' => 'تصفية',
        'add' => 'إضافة',
        'actions' => 'إجراءات',
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'confirm_action' => 'هل أنت متأكد؟',
        'confirm_delete' => 'هل أنت متأكد أنك تريد حذف هذا؟',
        'search' => 'بحث...',
        'export' => 'تصدير',
        'export_csv' => 'تصدير CSV',
    ],

    // Agencies
    'agencies' => [
        'title' => 'الوكالات',
        'created' => 'تم إنشاء الوكالة بنجاح',
        'updated' => 'تم تحديث الوكالة بنجاح',
        'deleted' => 'تم حذف الوكالة',
        'status_updated' => 'تم تحديث حالة الوكالة',
    ],

    // Activity Log
    'activity' => [
        'login' => 'تسجيل دخول',
        'logout' => 'تسجيل خروج',
        'payment_created' => 'إنشاء دفعة',
        'payment_paid' => 'اكتمال الدفع',
        'user_created' => 'إنشاء مستخدم',
        'settings_updated' => 'تحديث الإعدادات',
    ],

    // Errors
    'errors' => [
        'unauthorized' => 'غير مصرح لك بتنفيذ هذا الإجراء',
        'not_found' => 'العنصر غير موجود',
        'no_agency' => 'لم يتم تعيينك لأي وكالة',
        'agency_inactive' => 'وكالتك غير نشطة حالياً',
    ],
];
