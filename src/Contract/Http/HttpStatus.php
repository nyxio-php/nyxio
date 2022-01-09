<?php

declare(strict_types=1);

namespace Nyxio\Contract\Http;

enum HttpStatus: int
{
    case Ok = 200;
    case Created = 201;
    case BadRequest = 400;
    case Unauthorized = 401;
    case NotAllowed = 403;
    case PageNotFound = 404;
    case MethodNotAllowed = 405;
    case InternalServerError = 500;
}
