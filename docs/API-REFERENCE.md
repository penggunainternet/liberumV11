# API Reference — Liberum V11

Updated: 2025-11-03

This document describes the public REST API exposed under `/api` using Laravel 11 + Sanctum. All authenticated endpoints require a Bearer token.

Base URL examples:

-   Local (Laragon): http://localhost/api

## Authentication

Use token-based auth from Laravel Sanctum.

Headers for authenticated requests:

-   Authorization: Bearer <token>
-   Accept: application/json

### Register

POST /api/register

Request (JSON):
{
"name": "Alice",
"email": "alice@example.com",
"password": "secret123",
"password_confirmation": "secret123"
}

Response 201:
{
"user": {
"id": 1,
"name": "Alice",
"email": "alice@example.com",
"username": null,
"created_at": "2025-11-03T10:00:00.000000Z",
"updated_at": "2025-11-03T10:00:00.000000Z"
},
"token": "<SANCTUM_TOKEN>"
}

### Login

POST /api/login

Request (JSON):
{
"email": "alice@example.com",
"password": "secret123"
}

Response 200:
{
"user": { ... },
"token": "<SANCTUM_TOKEN>"
}

Errors:

-   401 Invalid credentials => { "message": "Invalid credentials" }

### Logout

POST /api/logout
Headers: Authorization: Bearer <token>

Response 200:
{ "message": "Logged out" }

### Me (Profile summary)

GET /api/me
Headers: Authorization: Bearer <token>

Response 200:
{
"id": 1,
"username": "alice",
"email": "alice@example.com",
"profile_photo_url": "https://...",
"created_at": "2025-10-31T12:00:00.000000Z",
"threads_count": 12,
"replies_count": 34
}

---

## Threads

Resource path: /api/threads (auth required)

Fields (ThreadResource):

-   id, title, body, status
-   author: { id, name, profile_photo_url }
-   category: { id, name }
-   media: [ { id, filename, original_filename, url, thumbnail_url, mime_type, size, formatted_size } ]
-   created_at (string datetime)

### List Threads (paginated)

GET /api/threads

Notes:

-   Only approved threads are returned
-   Returns standard Laravel pagination shape (links, meta)

Response 200 (excerpt):
{
"data": [
{
"id": 10,
"title": "Best Laravel 11 Tips",
"body": "<html or markdown>",
"status": "approved",
"author": { "id": 2, "name": "Bob", "profile_photo_url": "https://..." },
"category": { "id": 3, "name": "Laravel" },
"media": [ { "id": 55, "filename": "...jpg", "url": "https://.../storage/threads/...jpg", "thumbnail_url": "https://.../storage/threads/thumbnails/...jpg", "mime_type": "image/jpeg", "size": 123456, "formatted_size": "120.56 KB" } ],
"created_at": "2025-11-03 10:22:11"
}
],
"links": { ... },
"meta": { ... }
}

### Create Thread

POST /api/threads
Headers: Authorization: Bearer <token>; Content-Type: multipart/form-data

Body (multipart):

-   title: string (required)
-   body: string (required)
-   category_id: integer (exists:categories,id)
-   images[]: file (image, optional, multiple; max 2MB each)

Response 201:
{
"data": {
"id": 11,
"title": "My First Thread",
"body": "...",
"status": null,
"author": { ... },
"category": { ... },
"media": [],
"created_at": "2025-11-03 11:00:01"
}
}

Notes:

-   Images are stored to storage/app/public/threads and thumbnails to storage/app/public/threads/thumbnails for the first image.

### Show Thread

GET /api/threads/{id}

Response 200:
{
"data": { ThreadResource }
}

### Update Thread

PUT /api/threads/{id}

Body (JSON):
{
"title": "Updated Title",
"body": "Updated body"
}

Response 200:
{
"data": { ThreadResource }
}

AuthZ:

-   Requires ownership; policy `update` enforced.

### Delete Thread

DELETE /api/threads/{id}

Response 200:
{ "message": "Thread deleted" }

AuthZ:

-   Requires ownership; policy `delete` enforced.

---

## Replies

Resource path: /api/reply (auth required)

Notes:

-   Controller returns raw models with relations (author, media) loaded; shape may include Eloquent timestamps/keys.

### List Replies

GET /api/reply

Response 200 (excerpt):
{
"data": [
{
"id": 1,
"body": "Nice thread!",
"replyable_type": "threads",
"replyable_id": 11,
"author_id": 1,
"created_at": "2025-11-03T11:05:00.000000Z",
"updated_at": "2025-11-03T11:05:00.000000Z",
"author": { "id": 1, "name": "Alice", "username": "alice", ... },
"media": [ { "id": 90, "filename": "...png", "url": "https://...", "thumbnail_url": "https://...", "mime_type": "image/png", "size": 23456, "formatted_size": "22.92 KB" } ]
}
]
}

### Create Reply

POST /api/reply

Body (JSON):
{
"body": "This helps a lot",
"replyable_type": "thread" | "threads" | "reply" | "replies",
"replyable_id": 11
}

Response 201:
{
"message": "Reply berhasil dibuat",
"data": { ...reply fields... }
}

Errors:

-   400 Invalid replyable_type => { "error": "Invalid replyable_type" }

### Show Reply

GET /api/reply/{id}

Response 200:
{
"data": { ...reply with author, media... }
}

### Update Reply

PUT /api/reply/{id}

Body (JSON):
{ "body": "Edited" }

Response 200:
{
"message": "Reply berhasil diperbarui",
"data": { ... }
}

### Delete Reply

DELETE /api/reply/{id}

Response 200:
{ "message": "Reply berhasil dihapus" }

---

## Categories

Resource path: /api/categories (auth required)

### List Categories

GET /api/categories

Response 200:
{
"data": [ { "id": 1, "name": "Laravel", "slug": "laravel", "created_at": "..", "updated_at": ".." }, ... ]
}

### Create Category

POST /api/categories

Body (JSON):
{ "name": "Livewire" }

Response 201:
{
"message": "Kategori berhasil dibuat",
"data": { "id": 5, "name": "Livewire", "slug": "livewire", ... }
}

Validation:

-   name required|string|max:255|unique:categories,name

### Show Category

GET /api/categories/{id}

Response 200:
{
"data": {
"id": 1,
"name": "Laravel",
"slug": "laravel",
"threads": [ ...optional eager loaded via controller... ]
}
}

### Update Category

PUT /api/categories/{id}

Body (JSON):
{ "name": "Laravel 11" }

Response 200:
{
"message": "Kategori berhasil diperbarui",
"data": { "id": 1, "name": "Laravel 11", "slug": "laravel-11", ... }
}

### Delete Category

DELETE /api/categories/{id}

Response 200:
{ "message": "Kategori berhasil dihapus" }

---

## Errors & Validation

Common statuses:

-   401 Unauthorized (missing/invalid token): { "message": "Unauthenticated." }
-   403 Forbidden (policy): { "message": "This action is unauthorized." }
-   404 Not Found
-   422 Validation error: { "message": "The given data was invalid.", "errors": { ... } }

---

## Notes

-   Authentication uses Laravel Sanctum personal access tokens.
-   Thread media is returned when relation `media` is loaded (already eager-loaded in index/show).
-   Replies API currently returns raw Eloquent models, not a Resource; the payload may change if a Resource is introduced later.
-   See ERD for data model details: ../ERD-DIAGRAM.md
