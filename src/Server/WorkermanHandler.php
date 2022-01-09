<?php

declare(strict_types=1);

namespace Nyxio\Server;

use Nyxio\Contract\Kernel\Request\RequestHandlerInterface;
use Nyxio\Contract\Server\HandlerInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Request as WorkerRequest;
use Workerman\Protocols\Http\Response as WorkerResponse;

class WorkermanHandler implements HandlerInterface
{
    public function __construct(private readonly ServerRequestFactoryInterface $requestFactory)
    {
    }

    public function message(RequestHandlerInterface $requestHandler): \Closure
    {
        return function (TcpConnection $connection, WorkerRequest $request) use ($requestHandler): void {
            $connection->send($this->getResponse($requestHandler, $request));
        };
    }

    private function getResponse(RequestHandlerInterface $requestHandler, WorkerRequest $request): WorkerResponse
    {
        $response = $requestHandler->handle($this->getRequest($request));

        return new WorkerResponse(
            status:  $response->getStatusCode(),
            headers: \array_merge_recursive(
                         ...
                         \array_map(
                             static fn(string $name) => [$name => $response->getHeaderLine($name)],
                             \array_keys($response->getHeaders())
                         )
                     ),
            body:    (string)$response->getBody()
        );
    }

    private function getRequest(WorkerRequest $request): ServerRequestInterface
    {
        $serverRequest = $this->requestFactory->createServerRequest(
            method:       $request->method(),
            uri:          $request->uri(),
            serverParams: $_SERVER,
        );

        $serverRequest = $serverRequest->withParsedBody($request->post());

        foreach ($request->header() as $name => $value) {
            $serverRequest = $serverRequest->withHeader($name, $value);
        }

        return $serverRequest;
    }
}
