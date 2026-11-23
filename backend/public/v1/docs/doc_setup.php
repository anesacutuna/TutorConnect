<?php
use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'TutorConnect API',
    version: '1.0.0',
    description: 'REST API for tutor profiles, bookings, reviews, subjects, users, and learning books.'
)]
#[OA\Server(url: 'http://localhost:8000', description: 'Local development server')]
#[OA\Tag(name: 'Users', description: 'User accounts')]
#[OA\Tag(name: 'Subjects', description: 'Learning subjects')]
#[OA\Tag(name: 'Tutor profiles', description: 'Tutor catalog profiles')]
#[OA\Tag(name: 'Bookings', description: 'Lesson bookings')]
#[OA\Tag(name: 'Reviews', description: 'Booking reviews')]
#[OA\Tag(name: 'Books', description: 'Open Library search')]
#[OA\Tag(name: 'API', description: 'API status')]
class TutorConnectApiDocumentation {}

#[OA\Schema(schema: 'ApiError', type: 'object', properties: [
    new OA\Property(property: 'status', type: 'integer', example: 400),
    new OA\Property(property: 'error', type: 'string', example: "Field 'name' is required.")
])]
class ApiErrorSchema {}

#[OA\Schema(schema: 'Message', type: 'object', properties: [
    new OA\Property(property: 'message', type: 'string', example: 'Record deleted.')
])]
class MessageSchema {}

#[OA\Schema(schema: 'User', type: 'object', properties: [
    new OA\Property(property: 'id', type: 'integer', example: 2),
    new OA\Property(property: 'full_name', type: 'string', example: 'Amina Kovač'),
    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'amina@student.local'),
    new OA\Property(property: 'role', type: 'string', enum: ['student', 'admin'], example: 'student'),
    new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time')
])]
class UserSchema {}
#[OA\Schema(schema: 'UserInput', type: 'object', required: ['full_name','email','password'], properties: [
    new OA\Property(property: 'full_name', type: 'string', minLength: 2, maxLength: 120),
    new OA\Property(property: 'email', type: 'string', format: 'email'),
    new OA\Property(property: 'password', type: 'string', format: 'password', minLength: 8)
])]
class UserInputSchema {}

#[OA\Schema(schema: 'Subject', type: 'object', properties: [
    new OA\Property(property: 'id', type: 'integer'),
    new OA\Property(property: 'name', type: 'string'),
    new OA\Property(property: 'description', type: 'string', nullable: true)
])]
class SubjectSchema {}
#[OA\Schema(schema: 'SubjectInput', type: 'object', required: ['name'], properties: [
    new OA\Property(property: 'name', type: 'string', minLength: 2, maxLength: 100),
    new OA\Property(property: 'description', type: 'string', nullable: true)
])]
class SubjectInputSchema {}

#[OA\Schema(schema: 'TutorProfile', type: 'object', properties: [
    new OA\Property(property: 'id', type: 'integer'),
    new OA\Property(property: 'subject_id', type: 'integer'),
    new OA\Property(property: 'full_name', type: 'string'),
    new OA\Property(property: 'bio', type: 'string'),
    new OA\Property(property: 'hourly_rate', type: 'number', format: 'float'),
    new OA\Property(property: 'subject_name', type: 'string', nullable: true)
])]
class TutorProfileSchema {}
#[OA\Schema(schema: 'TutorProfileInput', type: 'object', required: ['subject_id','full_name','bio','hourly_rate'], properties: [
    new OA\Property(property: 'subject_id', type: 'integer'),
    new OA\Property(property: 'full_name', type: 'string', minLength: 2, maxLength: 120),
    new OA\Property(property: 'bio', type: 'string', minLength: 10, maxLength: 3000),
    new OA\Property(property: 'hourly_rate', type: 'number', minimum: 0.01)
])]
class TutorProfileInputSchema {}

#[OA\Schema(schema: 'Booking', type: 'object', properties: [
    new OA\Property(property: 'id', type: 'integer'),
    new OA\Property(property: 'student_id', type: 'integer'),
    new OA\Property(property: 'tutor_profile_id', type: 'integer'),
    new OA\Property(property: 'starts_at', type: 'string', format: 'date-time', description: 'Stored in UTC; input local time is interpreted in Europe/Sarajevo.'),
    new OA\Property(property: 'duration_minutes', type: 'integer', minimum: 30, maximum: 180),
    new OA\Property(property: 'status', type: 'string', enum: ['pending','confirmed','completed','cancelled']),
    new OA\Property(property: 'notes', type: 'string', nullable: true)
])]
class BookingSchema {}
#[OA\Schema(schema: 'BookingInput', type: 'object', required: ['student_id','tutor_profile_id','starts_at','duration_minutes'], properties: [
    new OA\Property(property: 'student_id', type: 'integer'),
    new OA\Property(property: 'tutor_profile_id', type: 'integer'),
    new OA\Property(property: 'starts_at', type: 'string', format: 'date-time', example: '2026-12-01T10:00'),
    new OA\Property(property: 'duration_minutes', type: 'integer', minimum: 30, maximum: 180, example: 60),
    new OA\Property(property: 'status', type: 'string', enum: ['pending','confirmed','completed','cancelled']),
    new OA\Property(property: 'notes', type: 'string', nullable: true)
])]
class BookingInputSchema {}

#[OA\Schema(schema: 'Review', type: 'object', properties: [
    new OA\Property(property: 'id', type: 'integer'),
    new OA\Property(property: 'booking_id', type: 'integer'),
    new OA\Property(property: 'rating', type: 'integer', minimum: 1, maximum: 5),
    new OA\Property(property: 'comment', type: 'string')
])]
class ReviewSchema {}
#[OA\Schema(schema: 'ReviewInput', type: 'object', required: ['booking_id','rating','comment'], properties: [
    new OA\Property(property: 'booking_id', type: 'integer'),
    new OA\Property(property: 'rating', type: 'integer', minimum: 1, maximum: 5),
    new OA\Property(property: 'comment', type: 'string', minLength: 2, maxLength: 2000)
])]
class ReviewInputSchema {}

#[OA\Schema(schema: 'BookResult', type: 'object', properties: [
    new OA\Property(property: 'title', type: 'string'),
    new OA\Property(property: 'authors', type: 'array', items: new OA\Items(type: 'string')),
    new OA\Property(property: 'first_publish_year', type: 'integer', nullable: true),
    new OA\Property(property: 'cover_url', type: 'string', nullable: true),
    new OA\Property(property: 'open_library_url', type: 'string', format: 'uri')
])]
class BookResultSchema {}
#[OA\Schema(schema: 'BookSearchResult', type: 'object', properties: [
    new OA\Property(property: 'query', type: 'string'),
    new OA\Property(property: 'count', type: 'integer'),
    new OA\Property(property: 'books', type: 'array', items: new OA\Items(ref: '#/components/schemas/BookResult')),
    new OA\Property(property: 'source', type: 'string', example: 'Open Library')
])]
class BookSearchResultSchema {}
