<?php
use OpenApi\Attributes as OA;

class HealthPaths
{
    #[OA\Get(
        path: '/api/v1',
        tags: ['API'],
        summary: 'Check that TutorConnect API is running',
        responses: [
            new OA\Response(
                response: 200,
                description: 'API status',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'TutorConnect API is running.'),
                        new OA\Property(property: 'version', type: 'string', example: '1.0')
                    ]
                )
            )
        ]
    )]
    public function status(): void {}
}
