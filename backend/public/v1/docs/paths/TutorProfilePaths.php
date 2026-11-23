<?php
use OpenApi\Attributes as OA;

class TutorProfilePaths
{
    #[OA\Get(
        path: '/api/v1/tutor-profiles', tags: ['Tutor profiles'], summary: 'List tutor profiles',
        responses: [
            new OA\Response(response: 200, description: 'List of records', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/TutorProfile'))),
            new OA\Response(response: 500, description: 'Server error', content: new OA\JsonContent(ref: '#/components/schemas/ApiError'))
        ]
    )]
    public function getAll(): void {}

    #[OA\Get(
        path: '/api/v1/tutor-profiles/{id}', tags: ['Tutor profiles'], summary: 'Get one tutor profile', parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Record', content: new OA\JsonContent(ref: '#/components/schemas/TutorProfile')),
            new OA\Response(response: 404, description: 'Record not found', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 500, description: 'Server error', content: new OA\JsonContent(ref: '#/components/schemas/ApiError'))
        ]
    )]
    public function getById(): void {}

    #[OA\Post(
        path: '/api/v1/tutor-profiles', tags: ['Tutor profiles'], summary: 'Create tutor profile', requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/TutorProfileInput')),
        responses: [
            new OA\Response(response: 201, description: 'Created record', content: new OA\JsonContent(ref: '#/components/schemas/TutorProfile')),
            new OA\Response(response: 400, description: 'Invalid input', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 500, description: 'Server error', content: new OA\JsonContent(ref: '#/components/schemas/ApiError'))
        ]
    )]
    public function create(): void {}

    #[OA\Put(
        path: '/api/v1/tutor-profiles/{id}', tags: ['Tutor profiles'], summary: 'Replace record', parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/TutorProfileInput')),
        responses: [
            new OA\Response(response: 200, description: 'Updated record', content: new OA\JsonContent(ref: '#/components/schemas/TutorProfile')),
            new OA\Response(response: 400, description: 'Invalid input', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 404, description: 'Record not found', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 500, description: 'Server error', content: new OA\JsonContent(ref: '#/components/schemas/ApiError'))
        ]
    )]
    public function replace(): void {}

    #[OA\Patch(
        path: '/api/v1/tutor-profiles/{id}', tags: ['Tutor profiles'], summary: 'Update record', parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/TutorProfileInput')),
        responses: [
            new OA\Response(response: 200, description: 'Updated record', content: new OA\JsonContent(ref: '#/components/schemas/TutorProfile')),
            new OA\Response(response: 400, description: 'Invalid input', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 404, description: 'Record not found', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 500, description: 'Server error', content: new OA\JsonContent(ref: '#/components/schemas/ApiError'))
        ]
    )]
    public function update(): void {}

    #[OA\Delete(
        path: '/api/v1/tutor-profiles/{id}', tags: ['Tutor profiles'], summary: 'Delete record', parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Deleted record', content: new OA\JsonContent(ref: '#/components/schemas/Message')),
            new OA\Response(response: 400, description: 'Record cannot be deleted', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 404, description: 'Record not found', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 500, description: 'Server error', content: new OA\JsonContent(ref: '#/components/schemas/ApiError'))
        ]
    )]
    public function delete(): void {}
}
