<?php

namespace PlugRoute;

class RouteType
{
    public const GET = 'get';

    public const POST = 'post';

    public const PUT = 'put';

    public const PATCH = 'patch';

    public const DELETE = 'delete';

    public const OPTIONS = 'options';

    public const FALLBACK = 'fallback';

    public static function getTypes(): array
    {
        return [
            self::GET,
            self::POST,
            self::PUT,
            self::DELETE,
            self::PATCH,
            self::OPTIONS
        ];
    }
}