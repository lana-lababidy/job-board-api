
# Job Board API (Laravel)

A RESTful Job Board API built with Laravel. It allows **companies** to manage job postings and **applicants** to register, search for jobs, and apply to them. The project focuses on real-world backend skills: authentication, authorization, relationships, validation, and filtering.

---

## Features

- Companies can register, login, create, update, and delete their own jobs.
- Applicants can register, login, view jobs, and apply to jobs.
- Many-to-Many relationship between jobs and applicants with pivot data (`applied_at`).
- Token-based authentication using Laravel Sanctum for both companies and applicants.
- Role-based authorization:
  - Only the owning company can edit/delete its jobs.
  - Only authenticated applicants can apply to jobs.
- Job filtering by location, type, and keyword in the title.

---

## Tech Stack

- **Framework:** Laravel
- **Auth:** Laravel Sanctum (API token authentication)
- **Database:** MySQL (or any SQL database supported by Laravel)
- **Testing Tool:** Postman (manual API testing)

---

## Getting Started

### Prerequisites

- PHP (version compatible with your Laravel version)
- Composer
- MySQL (or another database)
- Node.js (optional, if you plan to add frontend later)

### Installation

1. Clone the repository:

git clone https://github.com/your-username/job-board-api.git
cd job-board-api

text

2. Install dependencies:

composer install

text

3. Copy the example environment file and configure it:

cp .env.example .env

text

Update the database settings in `.env`:

DB_DATABASE=job_board_api
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

text

4. Generate application key:

php artisan key:generate

text

5. Run migrations:

php artisan migrate

text

6. Start the development server:

php artisan serve

text

The API will be available at: `http://127.0.0.1:8000/api`

---

## Authentication

This API uses **Laravel Sanctum** for token-based authentication.

There are two user types:

- **Company**
- **Applicant**

Both have separate register and login endpoints and receive a **Bearer token** to access protected routes.

---

## Main Endpoints

### 1. Applicant Auth

#### Register

`POST /api/applicant/register`

{
"name": "Test Applicant",
"email": "test@app.com",
"password": "123456",
"password_confirmation": "123456",
"resume_link": "https://example.com/cv.pdf"
}

text

#### Login

`POST /api/applicant/login`

**Response:**

{
"message": "Login successful",
"applicant": {
"id": 1,
"name": "Test Applicant",
"email": "test@app.com"
},
"token": "YOUR_TOKEN_HERE",
"token_type": "Bearer"
}

text

Use the token as a Bearer token in the `Authorization` header:

Authorization: Bearer YOUR_TOKEN_HERE

text

---

### 2. Company Auth

#### Register

`POST /api/company/register`

{
"name": "Tech Company",
"email": "company@example.com",
"password": "123456",
"password_confirmation": "123456",
"description": "Software company"
}

text

#### Login

`POST /api/company/login`

Response shape is similar to applicant login (includes `company` and `token`).

---

### 3. Jobs

#### Public – List Jobs (with filters)

`GET /api/jobs`

Optional query parameters:

- `location` – filter by location  
- `type` – `Full-Time` or `Part-Time`  
- `keyword` – search in job title

Examples:

- `GET /api/jobs?location=Remote`
- `GET /api/jobs?type=Full-Time`
- `GET /api/jobs?location=Remote&type=Full-Time&keyword=Developer`

#### Public – Show Single Job

`GET /api/jobs/{id}`

Returns job details with company and applicants.

#### Protected – Create Job (Company only)

`POST /api/jobs`

Headers:

Authorization: Bearer COMPANY_TOKEN
Accept: application/json

text

Body:

{
"title": "Backend Developer",
"description": "Work with PHP/Laravel",
"location": "Remote",
"type": "Full-Time"
}

text

The `company_id` is automatically set from the authenticated company.

#### Protected – Update Job (Owner Company only)

`PUT /api/jobs/{id}`

Body (example):

{
"title": "Senior Backend Developer"
}

text

#### Protected – Delete Job (Owner Company only)

`DELETE /api/jobs/{id}`

---

### 4. Job Applications

#### Apply to a Job (Applicant only)

`POST /api/jobs/{job}/apply`

Headers:

Authorization: Bearer APPLICANT_TOKEN
Accept: application/json

text

Body: (empty JSON is enough)

{}

text

- Prevents duplicate applications for the same job.
- Stores `applied_at` in the pivot table.

#### Cancel Application (Optional)

`DELETE /api/jobs/{job}/cancel`

---

## Project Highlights

- Real-world **Job Board API** with companies and applicants.
- Clean separation of roles with different auth flows.
- Use of **Eloquent relationships** (One-to-Many, Many-to-Many with pivot data).
- Token-based authentication and authorization with **Laravel Sanctum**.
- Search and filter jobs by multiple criteria.
- Tested manually using Postman.

---

## Possible Improvements

- Add pagination to jobs listing.
- Add admin role and dashboard.
- Add frontend (Vue/React) consuming this API.
يمكنك تعديل اسم المشروع، رابط الريبو، وأي تفاصيل خاصة بك.