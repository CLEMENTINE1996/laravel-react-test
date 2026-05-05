# Ticket Management System

A full-stack **Issue Intake and Smart Summary System** built with **Laravel (API backend)** and **React + Vite (frontend)**.

This project was developed as part of the Software Developer Practical Assessment to demonstrate practical development skills including:

* Clean backend architecture
* RESTful API design
* Database-driven ticket management
* AI-powered issue summarization
* Authentication with protected routes
* Frontend integration with backend APIs
* Automated testing

---

## Project Structure

```bash
laravel-react-test/
├── laravel-api/       # Backend API (Laravel)
└── frontend-react/    # Frontend UI (React + Vite)
```

### Backend (`laravel-api`)

Responsible for:

* Authentication
* Ticket CRUD operations
* Validation
* AI ticket analysis
* Business logic (escalation / summaries)
* Database persistence
* Feature testing

### Frontend (`frontend-react`)

Responsible for:

* Login interface
* Ticket dashboard
* Filtering/searching
* Ticket creation modal
* Smart summary modal
* API integration with backend

---

# Features

## Authentication

Secure login using Laravel Sanctum.

Seeded users are included for quick access.

Use **Manager** credentials after seeding.

---

## Ticket Management

Users can:

* Create tickets
* View all tickets
* Search tickets
* Filter by:

  * Status
  * Priority
* Edit tickets
* Delete tickets

---

## Smart AI Analysis

Each ticket generates:

### Summary

A concise explanation of the issue.

### Suggested Next Actions

Actionable recommendations for support resolution.

Example:

* Review existing implementation
* Investigate root cause
* Route to proper technical team

---

## Escalation Logic

High-priority tickets are automatically flagged for escalation.

This satisfies the practical assessment business rule requirement.

---

# Tech Stack

## Backend

* Laravel
* PHP
* MySQL
* Laravel Sanctum
* Groq API

## Frontend

* React
* Vite
* Axios
* TailwindCSS / modern CSS styling

---

# Why Laravel + React?

## Laravel

Chosen because it provides:

* Excellent API structure
* Built-in validation
* Authentication support
* Testing tools
* Clean service-oriented architecture

## React + Vite

Chosen because it offers:

* Fast development iteration
* Clean component-based UI
* Easy API consumption
* Lightweight frontend deployment

---

# Why Groq AI?

Groq was selected for the AI layer because it offers:

## Extremely Fast Inference

Important for generating ticket summaries instantly.

## Low Latency UX

Users receive smart analysis quickly after creating tickets.

## Cost Efficiency

Suitable for practical production experimentation.

## JSON Response Control

The system requests strict JSON output for reliable parsing.

---

# AI Analysis Flow

When a ticket is submitted:

1. Description is sent to `AnalyzeTicketService`
2. Service checks if Groq API key exists
3. If available:

   * Sends request to Groq
   * Receives structured JSON
4. If unavailable/fails:

   * Uses rules-based fallback engine

This ensures graceful degradation.

---

# Rules-Based Fallback Logic

Fallback detects keywords such as:

| Keyword         | Action                    |
| --------------- | ------------------------- |
| login/password  | Authentication team       |
| database/server | DevOps escalation         |
| crash/error     | Engineering investigation |
| payment/billing | Finance/Billing           |
| ui/ux           | Frontend review           |
| security        | Security assessment       |

This guarantees the system still works without AI.

---

# Setup Guide

## 1. Clone Repository

```bash
git clone https://github.com/CLEMENTINE1996/laravel-react-test.git
cd laravel-react-test
```

---

# Backend Setup (`laravel-api`)

## Step 1: Navigate

```bash
cd laravel-api
```

## Step 2: Install Dependencies

```bash
composer install
composer update
```

## Step 3: Copy Environment File

```bash
cp .env.example .env
```

Windows:

```bash
copy .env.example .env
```

## Step 4: Configure Database

Edit `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ticket_management
DB_USERNAME=root
DB_PASSWORD=
```

---

## Step 5: Configure Groq (Optional but Recommended)

Add:

```env
GROQ_API_KEY=your_key_here
GROQ_API_URL=https://api.groq.com/openai/v1/chat/completions
GROQ_MODEL=llama3-8b-8192
```

If omitted, fallback logic is used.

---

## Step 6: Generate App Key

```bash
php artisan key:generate
```

---

## Step 7: Run Migrations + Seeders

**Important**: Required for login users.

```bash
php artisan migrate:fresh --seed
```

This creates:

* Users
* Sample tickets

---

## Step 8: Serve Backend

```bash
php artisan serve
```

Default:

```bash
http://localhost:8000
```

API base URL:

```bash
http://localhost:8000/api/v1
```

---

# Frontend Setup (`frontend-react`)

## Step 1: Navigate

```bash
cd frontend-react
```

## Step 2: Install Dependencies

```bash
npm install
```

## Step 3: Copy Environment File

```bash
cp .env.example .env
```

Windows:

```bash
copy .env.example .env
```

## Step 4: Configure Backend URL

Edit `.env`

```env
VITE_API_URL=http://localhost:8000/api/v1
```

This must match your Laravel backend URL.

---

## Step 5: Run Frontend

```bash
npm run dev
```

Default:

```bash
http://localhost:5173
```

---

# Login Credentials

After running seeders, log in using the seeded Manager account.

(See database seeders for exact credentials.)

---

# Running Tests

Backend feature tests:

```bash
php artisan test tests/Feature/TicketControllerTest.php
```

Tests validate:

* Ticket creation
* Validation
* Retrieval
* Updates
* Delete operations
* API response correctness

---

# API Endpoints (Postman)

Base URL:

```bash
http://localhost:8000/api/v1
```

All protected endpoints require:

```http
Authorization: Bearer <token>
Accept: application/json
Content-Type: application/json
```

---

## 1. Login

### Endpoint

```http
POST /login
```

### Headers

```http
Accept: application/json
Content-Type: application/json
```

### Request Body

```json
{
  "email": "manager@example.com",
  "password": "password"
}
```

### Validation

| Field    | Type         | Required |
| -------- | ------------ | -------- |
| email    | string/email | Yes      |
| password | string       | Yes      |

### Success Response

Returns:

* Auth token
* Authenticated user details

---

## 2. List Tickets

### Endpoint

```http
GET /tickets
```

### Headers

```http
Authorization: Bearer <token>
```

### Optional Query Parameters

| Parameter | Description              |
| --------- | ------------------------ |
| status    | Filter by status         |
| priority  | Filter by priority       |
| search    | Search title/description |

Example:

```http
GET /tickets?status=open&priority=high
```

---

## 3. View Single Ticket

### Endpoint

```http
GET /tickets/{id}
```

### Path Parameters

| Parameter | Type    | Description |
| --------- | ------- | ----------- |
| id        | integer | Ticket ID   |

---

## 4. Create Ticket

### Endpoint

```http
POST /tickets
```

### Headers

```http
Authorization: Bearer <token>
Content-Type: application/json
```

### Request Parameters

| Field       | Type   | Required | Description                 |
| ----------- | ------ | -------- | --------------------------- |
| title       | string | Yes      | Ticket title                |
| description | string | Yes      | Detailed issue description  |
| category    | string | Yes      | e.g. Bug, Feature Request   |
| status      | string | Yes      | Open / In Progress / Closed |
| priority    | string | Yes      | Low / Medium / High         |

### Sample Body

```json
{
  "title": "Login Issue",
  "description": "Users cannot authenticate after password reset.",
  "category": "Bug",
  "status": "open",
  "priority": "high"
}
```

### What Happens Internally

When created:

1. Request is validated
2. Ticket saved to database
3. AI analysis triggered
4. Summary generated
5. Suggested next actions generated
6. High priority issues automatically escalated

---

## 5. Update Ticket

### Endpoint

```http
PUT /tickets/{id}
```

### Path Parameters

| Parameter | Type    |
| --------- | ------- |
| id        | integer |

### Updatable Fields

* title
* description
* category
* status
* priority

### Example Body

```json
{
  "status": "in_progress",
  "priority": "medium"
}
```

---

## 6. Delete Ticket

### Endpoint

```http
DELETE /tickets/{id}
```

### Path Parameters

| Parameter | Type    |
| --------- | ------- |
| id        | integer |

Deletes the selected ticket permanently.

---

## Postman Workflow

### Step 1

Login using `/login`

### Step 2

Copy returned bearer token

### Step 3

Set Authorization tab to:

```http
Bearer Token
```

Paste token.

### Step 4

Test CRUD endpoints.

---

## Expected API Response Pattern

Successful responses generally return:

```json
{
  "success": true,
  "data": {},
  "message": "Operation successful"
}
```

Validation failures return:

```json
{
  "success": false,
  "errors": {}
}
```

---

# Architecture Decisions

## Service Layer Pattern

Business logic is separated from controllers.

Benefits:

* Cleaner controllers
* Easier testing
* Better maintainability

---

## Resource Layer

Laravel Resources standardize API responses.

This ensures predictable frontend consumption.

---

## Protected Route Design

Authentication middleware secures ticket operations.

Only authenticated users can access ticket APIs.

---

## AI Isolation

AI logic is isolated in:

`AnalyzeTicketService`

This allows easy replacement of providers in future.

---

# What I Would Improve With More Time

## Role-Based Permissions

Different access levels:

* Admin
* Manager
* Support Agent

---

## Ticket Assignment Workflow

Assign tickets to individual team members.

---

## Better Audit Logging

Track:

* Who updated tickets
* Status history
* Resolution timeline

---

## Real-Time Updates

Using WebSockets for live dashboard updates.

---

## Enhanced AI Context

Provide historical ticket context for better suggestions.

---

## Pagination / Sorting

For larger production datasets.

---

# Assessment Deliverables Checklist

✅ Working source code
✅ README with setup steps
✅ Sample seed data
✅ Architecture explanation
✅ Key engineering decisions
✅ Improvement notes

---

# Screens Included

The UI demonstrates:

* Login page
* Dashboard
* Ticket creation modal
* Smart AI summary modal

The interface is intentionally simple, functional, and easy to evaluate.

---

# Final Notes

This project focuses on practical engineering trade-offs:

* Fast setup
* Clean separation of concerns
* Reliable fallback behavior
* Real API-driven frontend integration
* Testability

It demonstrates production-minded implementation while staying lightweight enough for assessment review.
