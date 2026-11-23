<?php
class HttpException extends RuntimeException
{
    public function __construct(string $message, private int $status = 400)
    {
        parent::__construct($message);
    }
    public function get_status(): int { return $this->status; }
}
class ValidationException extends HttpException
{
    public function __construct(string $message) { parent::__construct($message, 400); }
}
class NotFoundException extends HttpException
{
    public function __construct(string $message) { parent::__construct($message, 404); }
}

class UnauthorizedException extends HttpException
{
    public function __construct(string $message = 'Authentication is required.') { parent::__construct($message, 401); }
}
class ForbiddenException extends HttpException
{
    public function __construct(string $message = 'You do not have permission to perform this action.') { parent::__construct($message, 403); }
}
