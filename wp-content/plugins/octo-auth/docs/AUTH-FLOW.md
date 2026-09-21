# Octo Auth — Execution Flow & Function Map

> وضعیت این سند بر اساس کد واقعی شاخه `style/digikala-ui-refresh` تهیه شده است. هدف آن توضیح «چه چیزی، چه زمانی، با چه ورودی و چه خروجی‌ای اجرا می‌شود» است؛ نه تعریف معماری ایده‌آل.

---

## 1. نقطه شروع افزونه

فایل:

`octo-auth.php`

وردپرس با لود شدن افزونه این فایل را اجرا می‌کند.

### ترتیب کلی

```
octo-auth.php
│
├── تعریف Constants
│
├── require validation.php
├── require user_lookup.php
├── require ip_guard.php
├── require rate_limit.php
├── require otp_guard.php
├── require auth_state.php
│
├── require sms-ir.php
│
├── require phone.php
├── require otp.php
├── require password.php
├── require login.php
├── require logout.php
├── require register.php
│
├── require ajax.php
└── require admin/settings-page.php
```

نکته: فایل‌ها در این مرحله فقط لود می‌شوند؛ اجرای اکثر توابع بعداً و در پاسخ به درخواست AJAX یا Hookهای وردپرس اتفاق می‌افتد.

---

# 2. Constants

## مسیر و URL افزونه

### `OCTO_AUTH_PATH`

مسیر فیزیکی افزونه را نگه می‌دارد.

کاربرد:
- ساخت مسیر `require_once` برای فایل‌های داخلی افزونه.

### `OCTO_AUTH_URL`

URL افزونه را نگه می‌دارد.

در کد فعلی که بررسی شد استفاده مستقیمی از آن وجود ندارد.

---

## OTP

### `OCTO_OTP_TTL = 120`

مدت اعتبار OTP بر حسب ثانیه:

```
120 seconds = 2 minutes
```

در `octo_send_otp()` برای Transient کد OTP استفاده می‌شود.

همچنین در پاسخ AJAX مقدار `expire` را تعیین می‌کند.

---

## Auth State

### `OCTO_AUTH_STATE_TTL = OCTO_OTP_TTL * 5`

با مقدار فعلی:

```
120 × 5 = 600 seconds = 10 minutes
```

برای عمر Auth State استفاده می‌شود.

Auth State شامل این موارد است:

```
phone
flow
time
verified
```

---

## Rate Limit عمومی

### `OCTO_RATE_LIMIT_WINDOW = 60`

پنجره Rate Limit ارسال OTP:

```
60 seconds
```

### `OCTO_RATE_LIMIT_MAX_REQUESTS = 3`

حداکثر تعداد درخواست OTP در این پنجره:

```
3 requests
```

این دو Constant در `octo_send_otp()` استفاده می‌شوند.

---

## Rate Limit ورود با رمز

### `OCTO_LOGIN_RATE_WINDOW = 300`

پنجره Rate Limit ورود:

```
300 seconds = 5 minutes
```

### `OCTO_LOGIN_MAX_ATTEMPTS = 5`

حداکثر تلاش ناموفق ورود با رمز:

```
5 attempts
```

در `octo_login()` استفاده می‌شوند.

---

## IP Block

### `OCTO_IP_BLOCK_TTL = 30`

در `octo_send_otp()` و `octo_login()` به `octo_block_ip()` ارسال می‌شود.

**نکته مهم:** در کد فعلی پارامتر `octo_block_ip()` بر حسب «دقیقه» تفسیر می‌شود، نه ثانیه. بنابراین مقدار 30 فعلی یعنی:

```
30 minutes
```

نه 30 ثانیه.

---

## شماره موبایل

### `OCTO_PHONE_META_KEYS`

```
octo_auth_phone
billing_phone
phone
mobile
```

ترتیب جستجوی شماره موبایل کاربر را مشخص می‌کند.

در:

```
octo_find_user_by_phone()
```

استفاده می‌شود.

اگر کاربر در یکی از این Metaها پیدا نشود، تابع در نهایت `user_login` را نیز بررسی می‌کند.

---

## Redirect

### `OCTO_AUTH_REDIRECT`

در کد فعلی:

```php
home_url('/my-account/')
```

مقداردهی می‌شود.

در:

```
octo_get_redirect_url()
```

برگردانده می‌شود.

---

## Password Rules

### `OCTO_PASSWORD_MIN_LENGTH`

حداقل طول رمز:

```
8
```

### `OCTO_PASSWORD_REQUIRE_UPPERCASE`

وجود حداقل یک حرف بزرگ.

### `OCTO_PASSWORD_REQUIRE_LOWERCASE`

وجود حداقل یک حرف کوچک.

### `OCTO_PASSWORD_REQUIRE_NUMBER`

وجود حداقل یک عدد.

### `OCTO_PASSWORD_REQUIRE_SPECIAL`

وجود حداقل یک کاراکتر خاص.

این Constantها در:

```
octo_validate_password()
```

استفاده می‌شوند.

---

# 3. AJAX Entry Points

فایل:

`inc/ajax/ajax.php`

این فایل مشخص می‌کند هر Action وردپرس به کدام تابع متصل شود.

| AJAX Action | تابع | کاربرد |
|---|---|---|
| `octo_check_phone` | `octo_check_phone()` | بررسی شماره |
| `octo_send_otp` | `octo_send_otp()` | ارسال OTP |
| `octo_verify_otp` | `octo_verify_otp()` | بررسی OTP |
| `octo_login` | `octo_login()` | ورود با رمز |
| `octo_login_with_otp` | `octo_login_with_otp()` | ورود با OTP |
| `octo_register` | `octo_register()` | ثبت‌نام |
| `octo_forgot_password` | `octo_forgot_password()` | شروع بازیابی رمز |
| `octo_reset_password` | `octo_reset_password()` | تعیین رمز جدید |
| `octo_logout` | `octo_logout()` | خروج |

اکثر Actionهای احراز هویت فقط برای کاربر مهمان با `wp_ajax_nopriv_*` ثبت شده‌اند.

`octo_logout` فقط با `wp_ajax_*` ثبت شده است.

---

# 4. Flow شماره موبایل

شروع تمام مسیرها از اینجا است:

```
User enters phone
        │
        ▼
octo_check_phone()
```

## octo_check_phone()

### ورودی

```
phone
```

### مراحل

```
phone
 │
 ▼
octo_validate_phone()
 │
 ├── invalid → JSON error
 │
 ▼
octo_find_user_by_phone()
 │
 ├── found
 │    │
 │    ▼
 │  octo_set_auth_state(phone, "login")
 │    │
 │    ▼
 │  step = password
 │
 └── not found
      │
      ▼
    octo_set_auth_state(phone, "register")
      │
      ▼
    step = otp
```

### خروجی در صورت وجود کاربر

```json
{
  "success": true,
  "data": {
    "step": "password",
    "token": "..."
  }
}
```

### خروجی در صورت نبودن کاربر

```json
{
  "success": true,
  "data": {
    "step": "otp",
    "token": "..."
  }
}
```

---

# 5. Auth State

فایل:

`inc/security/auth_state.php`

Auth State جلسه موقت احراز هویت است.

## octo_set_auth_state()

### ورودی

```
phone
flow
```

Flowهای فعلی:

```
login
register
reset_password
```

### عملیات

یک Token تصادفی 32 کاراکتری می‌سازد و در Transient زیر ذخیره می‌کند:

```
octo_auth_{token}
```

داده:

```
[
    'phone'    => ...,
    'flow'     => ...,
    'time'     => ...,
    'verified' => false
]
```

### خروجی

```
token
```

---

## octo_get_auth_state()

### ورودی

```
token
```

### عملیات

Transient مربوط به Token را می‌خواند.

### خروجی

Auth State یا `false/null` در صورت نبودن.

---

## octo_verify_auth_state()

بعد از Verify موفق OTP اجرا می‌شود.

### عملیات

```
verified = true
```

و Auth State دوباره ذخیره می‌شود.

### خروجی

```
true
```

در صورت نبودن State:

```
false
```

---

## octo_clear_auth_state()

### ورودی

```
token
```

Auth State را حذف می‌کند.

این تابع در پایان Login، Register و Reset Password استفاده می‌شود.

---

# 6. Register Flow

```
Phone
 │
 ▼
octo_check_phone()
 │
 └── User not found
        │
        ▼
 flow = register
        │
        ▼
octo_send_otp()
        │
        ▼
SMS.ir
        │
        ▼
octo_verify_otp()
        │
        ▼
verified = true
        │
        ▼
octo_register()
        │
        ├── validate name
        ├── validate password
        ├── wp_insert_user()
        ├── update_user_meta()
        ├── wp_set_current_user()
        ├── wp_set_auth_cookie()
        └── octo_clear_auth_state()
```

## octo_register()

### ورودی

```
token
name
password
password_confirmation
```

### وابستگی‌ها

```
octo_get_auth_state()
wp_insert_user()
update_user_meta()
wp_set_current_user()
wp_set_auth_cookie()
octo_clear_auth_state()
```

### نتیجه

کاربر با Role:

```
subscriber
```

ساخته می‌شود.

شماره در:

```
octo_auth_phone
```

ذخیره می‌شود.

سپس کاربر بلافاصله Login می‌شود.

### خروجی

```json
{
  "success": true,
  "data": {
    "message": "Account created successfully.",
    "redirect": "home_url()"
  }
}
```

---

# 7. OTP Flow

فایل:

`inc/auth/otp.php`

دو تابع اصلی:

```
octo_send_otp()
octo_verify_otp()
```

## ارسال OTP

```
octo_send_otp()
 │
 ├── octo_get_auth_state()
 │
 ├── octo_is_ip_blocked()
 │
 ├── octo_is_rate_limited()
 │
 ├── octo_increment_rate_limit()
 │
 ├── random_int()
 │
 ├── set_transient()
 │
 └── octo_send_sms_ir()
```

OTP در این Key ذخیره می‌شود:

```
octo_otp_{md5(phone)}
```

داده:

```
[
    'code'       => 6 digit code,
    'attempts'   => 0,
    'created_at' => timestamp
]
```

مدت اعتبار:

```
OCTO_OTP_TTL = 120 seconds
```

### خروجی موفق

```json
{
  "success": true,
  "data": {
    "message": "کد ارسال شد",
    "expire": 120
  }
}
```

---

# 8. Verify OTP

```
token + code
    │
    ▼
octo_verify_otp()
    │
    ├── octo_get_auth_state()
    │
    ├── get_transient(OTP)
    │
    ├── compare code
    │
    ├── octo_verify_auth_state()
    │
    ├── delete_transient(OTP)
    │
    └── return state.flow
```

### خروجی موفق

```json
{
  "success": true,
  "data": {
    "message": "OTP verified successfully.",
    "step": "register | login | reset_password"
  }
}
```

بنابراین `flow` تعیین می‌کند بعد از Verify باید چه اتفاقی بیفتد.

---

# 9. Login با Password

```
phone
 │
 ▼
octo_check_phone()
 │
 ▼
flow = login
 │
 ▼
password
 │
 ▼
octo_login()
```

## octo_login()

### مسیر کامل

```
octo_login()
 │
 ├── octo_get_auth_state()
 │
 ├── octo_is_ip_blocked()
 │
 ├── octo_is_rate_limited()
 │
 ├── octo_find_user_by_phone()
 │
 ├── wp_check_password()
 │      │
 │      ├── wrong → octo_increment_rate_limit()
 │      │            → error
 │      │
 │      └── correct
 │
 ├── octo_clear_rate_limit()
 │
 ├── update_user_meta() [در صورت نیاز]
 │
 ├── wp_set_current_user()
 │
 ├── wp_set_auth_cookie()
 │
 ├── octo_clear_auth_state()
 │
 └── success + redirect
```

### خروجی موفق

```json
{
  "success": true,
  "data": {
    "message": "Login successful.",
    "redirect": "..."
  }
}
```

---

# 10. Login با OTP

این مسیر از همان OTP مشترک استفاده می‌کند.

```
phone
 │
 ▼
octo_check_phone()
 │
 ▼
login
 │
 ▼
OTP
 │
 ├── octo_send_otp()
 │
 └── octo_verify_otp()
        │
        ▼
      verified = true
        │
        ▼
octo_login_with_otp()
```

## octo_login_with_otp()

بررسی می‌کند:

1. Token وجود دارد.
2. Auth State وجود دارد.
3. `verified = true`.
4. کاربر پیدا می‌شود.

سپس:

```
update_user_meta() [در صورت نیاز]
wp_set_current_user()
wp_set_auth_cookie()
octo_clear_auth_state()
```

---

# 11. پیدا کردن User

فایل:

`inc/security/user_lookup.php`

## octo_find_user_by_phone()

ترتیب جستجو:

```
1. octo_auth_phone
2. billing_phone
3. phone
4. mobile
5. user_login
```

اگر در Meta پیدا شود:

```
[
    'user'     => WP_User,
    'meta_key' => matched meta key
]
```

اگر از `user_login` پیدا شود:

```
[
    'user'     => WP_User,
    'meta_key' => 'user_login'
]
```

اگر پیدا نشود:

```
false
```

این موضوع باعث می‌شود کاربری که قبلاً توسط WooCommerce یا سیستم دیگری ساخته شده نیز قابل شناسایی باشد.

---

# 12. Rate Limit

فایل:

`inc/security/rate_limit.php`

ساختار Transient:

```
octo_rate_{md5(key)}
```

داده:

```
[
    'count'      => ...,
    'expires_at' => ...
]
```

## octo_is_rate_limited()

می‌پرسد:

```
آیا count >= limit است؟
```

خروجی:

```
[
    'limited' => true/false
]
```

## octo_increment_rate_limit()

شمارنده را افزایش می‌دهد.

اگر اولین درخواست باشد:

```
count = 1
expires_at = now + window
```

در درخواست‌های بعدی فقط `count` افزایش پیدا می‌کند.

## octo_clear_rate_limit()

Transient مربوط به Rate Limit را حذف می‌کند.

---

# 13. IP Guard

فایل:

`inc/security/ip_guard.php`

## octo_get_ip()

اولویت:

```
HTTP_CF_CONNECTING_IP
        ↓
HTTP_X_FORWARDED_FOR
        ↓
REMOTE_ADDR
```

## octo_is_ip_blocked()

Transient زیر را بررسی می‌کند:

```
octo_block_ip_{md5(ip)}
```

## octo_block_ip()

IP را برای تعداد دقیقه مشخص‌شده Block می‌کند.

در کد فعلی:

```
octo_block_ip(OCTO_IP_BLOCK_TTL)
```

و `OCTO_IP_BLOCK_TTL = 30` است؛ یعنی 30 دقیقه.

---

# 14. Password Validation

فایل:

`inc/security/validation.php`

## octo_validate_phone()

فرمت فعلی:

```
09xxxxxxxxx
```

خروجی:

```
true / false
```

## octo_validate_otp()

OTP شش رقمی را بررسی می‌کند.

خروجی:

```
true / false
```

**در کد فعلی این تابع توسط Flowهای موجود مستقیماً فراخوانی نمی‌شود.**

## octo_validate_password()

تمام خطاهای Password را جمع می‌کند.

خروجی:

```
[
    'valid'  => true/false,
    'errors' => [...]
]
```

در حال حاضر در `octo_reset_password()` استفاده می‌شود.

**نکته:** `octo_register()` در نسخه فعلی از این تابع استفاده نمی‌کند و فقط حداقل طول 8 کاراکتر را چک می‌کند.

---

# 15. Reset Password

```
Password Step
    │
    ▼
Forgot Password
    │
    ▼
octo_forgot_password()
    │
    ├── get old state
    ├── create new state
    │       flow = reset_password
    └── clear old state
            │
            ▼
          OTP
            │
            ▼
      octo_verify_otp()
            │
            ▼
        verified=true
            │
            ▼
    octo_reset_password()
```

## octo_forgot_password()

Auth State جدید می‌سازد:

```
flow = reset_password
```

و Token قبلی را حذف می‌کند.

---

## octo_reset_password()

### ورودی

```
token
password
password_confirm
```

### مسیر

```
octo_reset_password()
 │
 ├── octo_get_auth_state()
 ├── check verified
 ├── octo_validate_password()
 ├── compare passwords
 ├── octo_find_user_by_phone()
 ├── wp_set_password()
 ├── wp_set_current_user()
 ├── wp_set_auth_cookie()
 ├── octo_clear_auth_state()
 └── return redirect
```

---

# 16. SMS.ir

فایل:

`inc/sms/sms-ir.php`

## octo_send_sms_ir()

### ورودی

```
phone
code
```

تنظیمات را از WordPress Options می‌خواند:

```
octo_auth_sms_api_key
octo_auth_sms_template_id
octo_auth_sms_line_number
```

درخواست را به SMS.ir ارسال می‌کند.

### خروجی موفق

```
true
```

### خروجی خطا

Exception پرتاب می‌کند.

در `octo_send_otp()` این Exception با `try/catch` گرفته می‌شود.

**نکته:** `octo_auth_sms_line_number` فعلاً خوانده می‌شود ولی در Request ارسال SMS استفاده نشده است.

---

# 17. Admin Settings

فایل:

`inc/admin/settings-page.php`

توابع:

```
octo_auth_add_settings_page()
octo_auth_register_settings()
octo_auth_render_settings_page()
```

### octo_auth_add_settings_page()

با Hook:

```
admin_menu
```

صفحه تنظیمات Octo Auth را به Settings وردپرس اضافه می‌کند.

### octo_auth_register_settings()

با Hook:

```
admin_init
```

این Options را Register می‌کند:

```
octo_auth_sms_api_key
octo_auth_sms_template_id
octo_auth_sms_line_number
```

### octo_auth_render_settings_page()

فرم تنظیمات را نمایش می‌دهد.

---

# 18. Logout

فایل:

`inc/auth/logout.php`

تابع:

```
octo_logout()
```

در نسخه فعلی **خالی است** و هنوز عملیات خروج در آن پیاده‌سازی نشده است.

---

# 19. OTP Guard

فایل:

`inc/security/otp_guard.php`

در نسخه فعلی **خالی است**.

---

# 20. نقشه تابع‌ها

```
                    octo_check_phone()
                           │
              ┌────────────┴────────────┐
              │                         │
    octo_validate_phone()     octo_find_user_by_phone()
                                        │
                              ┌─────────┴─────────┐
                              │                   │
                           login              register
                              │                   │
                         octo_login()        octo_send_otp()
                              │                   │
                              │              octo_verify_otp()
                              │                   │
                              │              octo_register()
                              │
                              └── octo_login_with_otp()
                                        ▲
                                        │
                                  OTP verified


Forgot Password
      │
      ▼
octo_forgot_password()
      │
      ▼
octo_send_otp()
      │
      ▼
octo_verify_otp()
      │
      ▼
octo_reset_password()
```

---

# 21. توابع مشترک و نقش آنها

| تابع | نقش |
|---|---|
| `octo_get_auth_state()` | خواندن Session موقت |
| `octo_set_auth_state()` | ساخت Session موقت |
| `octo_verify_auth_state()` | علامت‌گذاری OTP به عنوان Verify شده |
| `octo_clear_auth_state()` | حذف Session |
| `octo_find_user_by_phone()` | پیدا کردن WordPress User |
| `octo_is_rate_limited()` | بررسی Rate Limit |
| `octo_increment_rate_limit()` | افزایش Rate Limit |
| `octo_clear_rate_limit()` | پاک کردن Rate Limit |
| `octo_get_ip()` | تشخیص IP |
| `octo_is_ip_blocked()` | بررسی Block بودن IP |
| `octo_block_ip()` | Block کردن IP |
| `octo_validate_phone()` | اعتبارسنجی موبایل |
| `octo_validate_otp()` | اعتبارسنجی فرمت OTP |
| `octo_validate_password()` | اعتبارسنجی Password |
| `octo_send_sms_ir()` | ارسال SMS |

---

# 22. نکات مهم وضعیت فعلی

این‌ها «رفتار واقعی فعلی» هستند و با معماری پیشنهادی اشتباه نشوند:

1. `OCTO_AUTH_STATE_TTL` فعلاً 10 دقیقه است، چون `120 × 5 = 600`.
2. `OCTO_IP_BLOCK_TTL = 30` فعلاً به معنی 30 دقیقه است، چون `octo_block_ip()` ورودی را در `MINUTE_IN_SECONDS` ضرب می‌کند.
3. `octo_validate_otp()` وجود دارد ولی در Flow فعلی استفاده نمی‌شود.
4. `otp_guard.php` فعلاً خالی است.
5. `octo_logout()` فعلاً خالی است.
6. Register فقط حداقل طول 8 کاراکتر را بررسی می‌کند و از `octo_validate_password()` استفاده نمی‌کند.
7. Reset Password از `octo_validate_password()` استفاده می‌کند.
8. در `octo_reset_password()` خطاهای Validation با کلید `errors` برگردانده می‌شوند، نه `message`.
9. در Login، مقدار `retry_after` فعلاً از Rate Limit برگردانده نمی‌شود.
10. `OCTO_AUTH_REDIRECT` هنگام Load افزونه با `home_url()` ساخته می‌شود.
11. Register در پاسخ موفق مستقیماً `home_url()` را برمی‌گرداند و از `octo_get_redirect_url()` استفاده نمی‌کند.
12. `octo_auth_sms_line_number` در تنظیمات ذخیره می‌شود اما در Request فعلی SMS.ir استفاده نمی‌شود.

---

# 23. خلاصه ذهنی سیستم

اگر بخواهی فقط یک تصویر در ذهن داشته باشی:

```
                    PHONE
                      │
                      ▼
              check_phone()
                      │
             ┌────────┴────────┐
             │                 │
          USER EXISTS       NEW USER
             │                 │
             ▼                 ▼
           LOGIN            REGISTER
             │                 │
       ┌─────┴─────┐           │
       │           │           │
    PASSWORD      OTP          OTP
       │           │           │
       │      verify_otp() ◄───┘
       │           │
       │        verified
       │           │
       ▼           ▼
      LOGIN      REGISTER


             PASSWORD
                 │
                 ▼
          Forgot Password
                 │
                 ▼
        reset_password Flow
                 │
                 ▼
                OTP
                 │
                 ▼
              VERIFY
                 │
                 ▼
          NEW PASSWORD
                 │
                 ▼
        wp_set_password()
                 │
                 ▼
               LOGIN
```

**اصل سیستم این است:**  
`phone` مسیر را شروع می‌کند، `token` مسیر را بین درخواست‌های AJAX نگه می‌دارد، `flow` مشخص می‌کند این Token متعلق به کدام سناریو است، و `verified` مشخص می‌کند OTP با موفقیت تأیید شده یا نه.
