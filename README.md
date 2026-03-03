<div align="center">

# 💼 Job Board API (Laravel)

[![PHP](https://img.shields.io/badge/PHP-8.1+-blueviolet?style=flat&logo=php&logoColor=white)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-10.x-red?style=flat&logo=laravel&logoColor=white)](https://laravel.com/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-006699?style=flat&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![License:MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=flat)](LICENSE)

**RESTful Job Board API - شركات + متقدمين + Many-to-Many**
</div>

## 📋 نظرة عامة
API لوحة وظائف كاملة تدعم:
- **شركات**: تسجيل/نشر/تعديل وظائف
- **متقدمين**: بحث/تقديم/إلغاء طلبات
- Many-to-Many مع `applied_at`
- فلترة + بحث متقدم

## 🛠️ التقنيات
|الطبقة|التقنية|الإصدار|
|-|-|-|
|Framework|Laravel|10.x|
|Auth|Sanctum Token|^4.0|
|DB|MySQL|8.0+|
|Relations|Many-to-Many Pivot|✅|

## 🚀 التثبيت
```bash
git clone https://github.com/lana-lababidy/job-board-api.git
cd job-board-api
composer install
cp .env.example .env
# عدّل .env:
DB_DATABASE=job_board
 DB_USERNAME=root
 DB_PASSWORD=
php artisan key:generate
php artisan migrate
php artisan serve
 API: http://127.0.0.1:8000/api

📖 الـ API
🔐 المصادقة (2 أنواع)

# متقدم
POST /api/applicant/register
POST /api/applicant/login

# شركة  
POST /api/company/register
POST /api/company/login
Response: {token, user_data}

Header: Authorization: Bearer {token}

💼 الوظائف (رئيسية)
Method	Endpoint	الوصف	الصلاحية
GET	/api/jobs	قائمة وظائف + فلترة	Public
GET	/api/jobs/{id}	وظيفة واحدة	Public
POST	/api/jobs	إنشاء وظيفة	Company
PUT	/api/jobs/{id}	تعديل	Owner Company
DELETE	/api/jobs/{id}	حذف	Owner Company
فلترة:
GET /api/jobs?location=Remote&type=Full-Time&keyword=Developer
📄 التقديم

POST /api/jobs/{job}/apply     # Applicant only
DELETE /api/jobs/{job}/cancel  # منع التكرار
مثال تقديم:


curl -X POST http://127.0.0.1:8000/api/jobs/1/apply \
-H "Authorization: Bearer {token}" \
-H "Content-Type: application/json"
🧪 Postman
📥 Collection

Run in Postman

📁 الهيكل
job-board-api/
├── app/
│   ├── Models/Job.php
│   ├── Models/Company.php
│   ├── Models/Applicant.php
├── database/migrations/
├── routes/api.php
└── tests/
🔐 الميزات
✅ Many-to-Many مع pivot (applied_at)
✅ Role-based Auth (Company/Applicant)
✅ فلترة متقدمة (location/type/keyword)
✅ Sanctum Token Auth
✅ Eloquent Relations
📊 قاعدة البيانات

Companies → Jobs ← Applicants (Many-to-Many)
                ↓
           Applications (pivot: applied_at)
📞 التواصل
لانا لبابيبدي
✉️ lanalaba8@gmail.com
📱 +963968879073
🐙 @lana-lababidy
