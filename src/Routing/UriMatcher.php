<?php

declare(strict_types=1);

namespace Nyxio\Routing;

use Nyxio\Contract\Routing\UriMatcherInterface;
use Nyxio\Contract\Validation\RulesCheckerInterface;
use Nyxio\Routing\Attribute\Route;
use Nyxio\Validation\DTO\Field;
use Psr\Http\Message\ServerRequestInterface;

use function Nyxio\Helper\Text\parseFromString;

class UriMatcher implements UriMatcherInterface
{
    private array $params = [];

    public function __construct(private readonly RulesCheckerInterface $rulesChecker)
    {
    }

    /**
     * @param ServerRequestInterface $request
     * @param Route $route
     * @return bool
     */
    public function compare(ServerRequestInterface $request, Route $route): bool
    {
        if ($route->getUri() === $request->getUri()->getPath()) {
            return true;
        }

        $partsRequest = \explode('/', $request->getUri()->getPath());
        $partsRoute = \explode('/', $route->getUri());

        if (\count($partsRequest) !== \count($partsRoute)) {
            return false;
        }

        $difference = \array_diff_assoc($partsRoute, $partsRequest);

        $filtered = \array_filter($difference, static function ($value) {
            return ($value[0] ?? null) === '@';
        });

        if (\count($difference) !== \count($filtered)) {
            return false;
        }

        foreach ($difference as $key => $param) {
            $param = \substr($param, 1);

            $parsedValue = parseFromString($partsRequest[$key]);

            $validator = $route->getField($param);

            $this->params[$param] = $parsedValue;

            if ($validator instanceof Field
                && !empty($this->rulesChecker->check($this->params, $validator))) {
                return false;
            }
        }

        return true;
    }

    public function getQueryParams(): array
    {
        return $this->params;
    }
}
