<?php
use OpenApi\Attributes as OA;
class BookPaths
{
    #[OA\Get(
        path: '/api/v1/books', tags: ['Books'], summary: 'Search learning books through Open Library',
        parameters: [new OA\Parameter(name: 'q', in: 'query', required: true, description: 'Search term, 2 to 100 characters.', schema: new OA\Schema(type: 'string', minLength: 2, maxLength: 100), example: 'algebra')],
        responses: [
            new OA\Response(response: 200, description: 'Normalized book search results', content: new OA\JsonContent(ref: '#/components/schemas/BookSearchResult')),
            new OA\Response(response: 400, description: 'Invalid search query', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 500, description: 'Open Library or server is unavailable', content: new OA\JsonContent(ref: '#/components/schemas/ApiError'))
        ]
    )]
    public function searchBooks(): void {}
}
