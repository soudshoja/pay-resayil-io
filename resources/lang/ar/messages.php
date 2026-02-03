<?php

return [

    // Authentication
    'auth' => [
        'subtitle' => 'نظام إدارة بوابة الدفع',
        'welcome' => 'مرحباً بعودتك',
        'login_subtitle' => 'سجل الدخول برقم هاتفك',
        'phone' => 'رقم الهاتف',
        'send_otp' => 'إرسال رمز التحقق',
        'otp_info' => 'سنرسل لك رمز التحقق عبر واتساب',
        'verify_otp' => 'التحقق من الرمز',
        'otp_sent_to' => 'تم إرسال الرمز إلى :phone',
        'enter_otp' => 'رمز التحقق',
        'verify' => 'تحقق وسجل الدخول',
        'resend_in' => 'إعادة إرسال الرمز خلال',
        'seconds' => 'ثانية',
        'resend_otp' => 'إعادة إرسال الرمز',
        'back_to_login' => 'العودة لتسجيل الدخول',
        'logout' => 'تسجيل الخروج',
        'logged_out' => 'تم تسجيل خروجك',
        'welcome_back' => 'مرحباً بعودتك، :name!',
        'login_success' => 'تم تسجيل الدخول بنجاح',
        'user_not_found' => 'المستخدم غير موجود أو غير نشط',
    ],

    // OTP Messages
    'otp' => [
        'sent' => 'تم إرسال رمز التحقق عبر واتساب',
        'verified' => 'تم التحقق من الهاتف بنجاح',
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
    ],

    // Dashboard
    'dashboard' => [
        'revenue_today' => 'إيرادات اليوم (د.ك)',
        'paid_today' => 'مدفوعات اليوم',
        'pending' => 'قيد الانتظار',
        'revenue_month' => 'إيرادات الشهر (د.ك)',
        'weekly_overview' => 'نظرة أسبوعية',
        'recent_payments' => 'آخر المدفوعات',
        'quick_actions' => 'إجراءات سريعة',
        'view_pending' => 'عرض المعلقة',
    ],

    // Payments
    'payments' => [
        'title' => 'المدفوعات',
        'subtitle' => 'إدارة طلبات الدفع',
        'new' => 'دفعة جديدة',
        'create_title' => 'إنشاء دفعة',
        'create_subtitle' => 'إنشاء رابط دفع جديد',
        'create_button' => 'إنشاء رابط الدفع',
        'amount' => 'المبلغ',
        'customer' => 'العميل',
        'customer_phone' => 'هاتف العميل',
        'customer_name' => 'اسم العميل',
        'customer_name_placeholder' => 'اسم العميل (اختياري)',
        'description' => 'الوصف',
        'description_placeholder' => 'وصف الدفعة (اختياري)',
        'send_whatsapp' => 'إرسال رابط الدفع عبر واتساب',
        'status' => 'الحالة',
        'date' => 'التاريخ',
        'details' => 'تفاصيل الدفعة',
        'invoice_id' => 'رقم الفاتورة',
        'reference_id' => 'رقم المرجع',
        'created_at' => 'تاريخ الإنشاء',
        'paid_at' => 'تاريخ الدفع',
        'created_by' => 'أنشئ بواسطة',
        'payment_link' => 'رابط الدفع',
        'resend_whatsapp' => 'إعادة الإرسال عبر واتساب',
        'cancel' => 'إلغاء الدفعة',
        'no_payments' => 'لا توجد مدفوعات',
        'created' => 'تم إنشاء رابط الدفع بنجاح',
        'link_resent' => 'تم إرسال رابط الدفع عبر واتساب',
        'cancelled' => 'تم إلغاء الدفعة',
        'cannot_resend' => 'لا يمكن إعادة إرسال الرابط لدفعة غير معلقة',
        'cannot_cancel' => 'لا يمكن إلغاء دفعة غير معلقة',
        'no_credentials' => 'لم يتم تكوين بيانات MyFatoorah',
        'success_title' => 'تمت عملية الدفع بنجاح',
        'success_message' => 'تمت معالجة الدفع بنجاح',
        'success_note' => 'ستتلقى تأكيداً عبر واتساب قريباً',
        'error_title' => 'فشلت عملية الدفع',
        'error_message' => 'تعذرت معالجة الدفع',
        'error_note' => 'يرجى المحاولة مرة أخرى أو التواصل مع الوكالة',
        'close_window' => 'يمكنك إغلاق هذه النافذة',
        'contact_support' => 'إذا استمرت المشكلة، تواصل مع الوكالة',
        'processing_error' => 'خطأ في معالجة الدفع',
        'invalid_id' => 'معرف الدفع غير صالح',
        'not_found' => 'الدفعة غير موجودة',
        'search_placeholder' => 'بحث بالهاتف أو الاسم أو الفاتورة...',
        'all_status' => 'كل الحالات',
    ],

    // Status
    'status' => [
        'pending' => 'قيد الانتظار',
        'paid' => 'مدفوعة',
        'failed' => 'فاشلة',
        'expired' => 'منتهية',
        'cancelled' => 'ملغاة',
    ],

    // Team
    'team' => [
        'title' => 'أعضاء الفريق',
        'subtitle' => 'إدارة فريق الوكالة',
        'add_member' => 'إضافة عضو',
        'no_members' => 'لا يوجد أعضاء في الفريق',
        'full_name' => 'الاسم الكامل',
        'phone' => 'رقم الهاتف',
        'email' => 'البريد الإلكتروني',
        'role' => 'الصلاحية',
        'password' => 'كلمة المرور',
        'password_confirm' => 'تأكيد كلمة المرور',
        'create_button' => 'إنشاء العضو',
        'created' => 'تم إنشاء العضو بنجاح',
        'updated' => 'تم تحديث العضو بنجاح',
        'deleted' => 'تم حذف العضو',
        'status_updated' => 'تم تحديث الحالة',
        'cannot_deactivate_self' => 'لا يمكنك تعطيل حسابك الخاص',
        'cannot_delete_self' => 'لا يمكنك حذف حسابك الخاص',
    ],

    // Roles
    'roles' => [
        'super_admin' => 'مدير عام',
        'admin' => 'مدير',
        'accountant' => 'محاسب',
        'agent' => 'موظف',
    ],

    // Agencies
    'agencies' => [
        'title' => 'الوكالات',
        'created' => 'تم إنشاء الوكالة بنجاح',
        'updated' => 'تم تحديث الوكالة بنجاح',
        'deleted' => 'تم حذف الوكالة',
        'status_updated' => 'تم تحديث حالة الوكالة',
    ],

    // Settings
    'settings' => [
        'title' => 'الإعدادات',
        'profile' => 'إعدادات الملف الشخصي',
        'language' => 'اللغة',
        'change_password' => 'تغيير كلمة المرور',
        'current_password' => 'كلمة المرور الحالية',
        'new_password' => 'كلمة المرور الجديدة',
        'confirm_password' => 'تأكيد كلمة المرور',
        'update_password' => 'تحديث كلمة المرور',
        'myfatoorah' => 'إعدادات MyFatoorah',
        'myfatoorah_desc' => 'تكوين بيانات بوابة الدفع',
        'webhooks' => 'الويب هوك',
        'webhooks_desc' => 'تكوين webhooks لـ n8n و API',
        'api_key' => 'مفتاح API',
        'country_code' => 'الدولة',
        'mode' => 'الوضع',
        'test_mode' => 'وضع الاختبار',
        'live_mode' => 'الوضع الحقيقي',
        'test_connection' => 'اختبار الاتصال',
        'last_verified' => 'آخر تحقق',
        'myfatoorah_updated' => 'تم تحديث بيانات MyFatoorah',
        'myfatoorah_invalid' => 'بيانات MyFatoorah غير صالحة',
        'myfatoorah_valid' => 'تم التحقق من البيانات بنجاح',
        'no_credentials' => 'لم يتم تكوين البيانات',
        'profile_updated' => 'تم تحديث الملف الشخصي',
        'password_changed' => 'تم تغيير كلمة المرور بنجاح',
        'webhook_created' => 'تم إنشاء الويب هوك',
        'webhook_deleted' => 'تم حذف الويب هوك',
        'webhook_updated' => 'تم تحديث الويب هوك',
        'no_webhooks' => 'لا توجد webhooks مكونة',
        'triggered' => 'تم التشغيل',
        'last_trigger' => 'آخر تشغيل',
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
        'actions' => 'الإجراءات',
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'activate' => 'تفعيل',
        'deactivate' => 'تعطيل',
        'confirm_action' => 'هل أنت متأكد؟',
        'confirm_delete' => 'هل أنت متأكد من الحذف؟',
    ],

    // Footer
    'footer' => [
        'rights' => 'جميع الحقوق محفوظة',
        'powered_by' => 'بدعم من',
    ],

    // Errors
    'errors' => [
        'unauthorized' => 'غير مصرح لك بتنفيذ هذا الإجراء',
        'no_agency' => 'لم يتم تعيينك لأي وكالة',
        'agency_inactive' => 'وكالتك غير نشطة حالياً',
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
];
