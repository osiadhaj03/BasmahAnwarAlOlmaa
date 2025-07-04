# نظام QR Token الديناميكي - دليل المطور

## نظرة عامة

تم تطبيق نظام QR Token ديناميكي آمن لتسجيل الحضور مع صلاحية 15 دقيقة فقط لكل QR Code. هذا النظام يحل محل النظام القديم ويوفر أماناً أكبر ومرونة في الاستخدام.

## الميزات الرئيسية

### 1. QR Token ديناميكي
- **صلاحية محدودة**: كل QR Token صالح لمدة 15 دقيقة فقط
- **استخدام واحد**: كل token يمكن استخدامه مرة واحدة فقط
- **تشفير آمن**: استخدام Laravel Crypt و Base64 encoding
- **عدم تخزين الصور**: لا يتم تخزين صور QR في قاعدة البيانات

### 2. إدارة تلقائية
- **توليد تلقائي**: توليد tokens جديدة عند الحاجة
- **تنظيف تلقائي**: حذف tokens منتهية الصلاحية والمستخدمة
- **تحديث في الوقت الفعلي**: عداد تنازلي وتحديث حالة QR

### 3. واجهات محسّنة
- **واجهة المعلم**: عرض QR مع عداد تنازلي وإمكانية التجديد
- **واجهة الطالب**: ماسح QR محسّن مع دعم الإدخال اليدوي

## البنية التقنية

### قاعدة البيانات

#### جدول `qr_tokens`
```sql
CREATE TABLE qr_tokens (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    lesson_id BIGINT NOT NULL,
    token VARCHAR(255) UNIQUE NOT NULL,
    generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL,
    used_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
    INDEX (lesson_id, token),
    INDEX (expires_at)
);
```

### Models

#### QrToken Model
- `isExpired()`: فحص انتهاء الصلاحية
- `isUsed()`: فحص الاستخدام
- `isValid()`: فحص صحة Token
- `markAsUsed()`: تحديد Token كمستخدم

#### Lesson Model (محدّث)
- `generateQRCodeToken()`: توليد token جديد مع صلاحية 15 دقيقة
- `getValidQRToken()`: الحصول على token صالح حالي
- `qrTokens()`: علاقة مع QrTokens

### Controllers

#### QrCodeController (محدّث)
- `generateQR()`: توليد صورة QR لـ token معين
- `displayQR()`: عرض صفحة QR للعرض
- `scanAttendance()`: معالجة مسح QR وتسجيل الحضور
- `getTokenInfo()`: معلومات Token للتحديث في الوقت الفعلي
- `refreshToken()`: توليد token جديد

## Routes الجديدة

### للمعلمين والمدراء
```php
GET  /admin/lessons/{lesson}/qr-display     # عرض صفحة QR
GET  /admin/lessons/{lesson}/qr-info        # معلومات Token (API)
POST /admin/lessons/{lesson}/qr-refresh     # توليد token جديد

GET  /teacher/lessons/{lesson}/qr-display   # نفس الوظائف للمعلم
GET  /teacher/lessons/{lesson}/qr-info      
POST /teacher/lessons/{lesson}/qr-refresh   
```

### للطلاب
```php
GET /qr-scanner                            # صفحة ماسح QR
GET /attendance/scan?token={token}         # معالجة مسح Token
```

### عامة
```php
GET /qr-generate/{lesson}                  # توليد صورة QR
```

## كيفية العمل

### 1. توليد QR Token
```php
// في Lesson Model
public function generateQRCodeToken()
{
    // حذف tokens منتهية الصلاحية
    $this->qrTokens()->where(function($query) {
        $query->where('expires_at', '<', now())
              ->orWhereNotNull('used_at');
    })->delete();

    // توليد token جديد
    $tokenData = [
        'lesson_id' => $this->id,
        'timestamp' => now()->timestamp,
        'random' => Str::random(16)
    ];
    
    $encryptedToken = base64_encode(Crypt::encrypt(json_encode($tokenData)));
    
    // حفظ في قاعدة البيانات
    return $this->qrTokens()->create([
        'token' => $encryptedToken,
        'generated_at' => now(),
        'expires_at' => now()->addMinutes(15),
    ]);
}
```

### 2. معالجة المسح
```php
// في QrCodeController
public function scanAttendance(Request $request)
{
    $qrToken = QrToken::where('token', $request->token)->first();
    
    if (!$qrToken || !$qrToken->isValid()) {
        return response()->json(['success' => false, 'message' => 'Token غير صالح']);
    }
    
    // تسجيل الحضور
    $attendance = Attendance::create([...]);
    
    // تحديد Token كمستخدم
    $qrToken->markAsUsed();
    
    return response()->json(['success' => true, ...]);
}
```

## الواجهات

### واجهة المعلم - QR Display
- عرض معلومات الدرس
- QR Code مع تحديث تلقائي
- عداد تنازلي (15:00 → 00:00)
- زر توليد QR جديد
- تحديث حالة كل 10 ثوانٍ

### واجهة الطالب - QR Scanner  
- ماسح QR باستخدام كاميرا الجهاز
- إدخال يدوي للـ token
- رسائل حالة واضحة
- عرض تفاصيل الحضور بعد النجاح

## الأمان

### تشفير Token
```php
$tokenData = [
    'lesson_id' => $this->id,
    'timestamp' => now()->timestamp,
    'random' => Str::random(16)
];
$encryptedToken = base64_encode(Crypt::encrypt(json_encode($tokenData)));
```

### التحقق من الصلاحية
- فحص انتهاء الوقت (15 دقيقة)
- فحص الاستخدام المسبق
- فحص تسجيل الطالب في الدرس
- فحص عدم وجود حضور مسبق لنفس اليوم

## صيانة النظام

### تنظيف تلقائي
```bash
# تشغيل أمر التنظيف
php artisan qr:clean-expired

# إضافة لجدولة المهام (في Kernel.php)
$schedule->command('qr:clean-expired')->hourly();
```

### مراقبة الأداء
- عدد tokens النشطة
- معدل الاستخدام
- tokens منتهية الصلاحية

## استخدام النظام

### للمعلم:
1. الدخول لصفحة الدرس
2. النقر على "QR Code للحضور"
3. عرض QR للطلاب (الصلاحية 15 دقيقة)
4. تجديد QR عند انتهاء الصلاحية

### للطالب:
1. الدخول لصفحة ماسح QR
2. تشغيل الكاميرا ومسح QR
3. أو إدخال token يدوياً
4. تأكيد تسجيل الحضور

## الميزات المتقدمة

### تحديث في الوقت الفعلي
- عداد تنازلي دقيق
- تحديث تلقائي كل 10 ثواني
- إشعارات انتهاء الصلاحية

### مرونة في الاستخدام
- دعم الإدخال اليدوي للـ token
- رسائل خطأ واضحة ومفيدة
- واجهة مستخدم سهلة

### تتبع وإحصائيات
- سجل استخدام tokens
- معدلات النجاح/الفشل
- تحليل أنماط الحضور

## الملاحظات المهمة

1. **صلاحية QR**: 15 دقيقة فقط لضمان الأمان
2. **استخدام واحد**: كل token يُستخدم مرة واحدة فقط
3. **تنظيف دوري**: يُنصح بتشغيل أمر التنظيف بانتظام
4. **لا تخزين صور**: QR يتم توليده ديناميكياً
5. **تشفير قوي**: استخدام Laravel Crypt

## التطوير المستقبلي

### إمكانيات إضافية:
- إحصائيات استخدام QR
- تنبيهات انتهاء الصلاحية
- دعم QR للدروس المتعددة
- تحليل أنماط الحضور
- API للتطبيقات الخارجية

---

**تم التطبيق بنجاح!** ✅  
نظام QR Token الديناميكي جاهز للاستخدام مع صلاحية 15 دقيقة لكل QR Code.
