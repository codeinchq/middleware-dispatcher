<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material  is strictly forbidden unless prior   |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     10/04/2018
// Time:     18:49
// Project:  MiddlewareDispatcher
//
declare(strict_types=1);
namespace CodeInc\MiddlewareDispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class AbstractMiddlewareDispatcher
 *
 * @package CodeInc\MiddlewareDispatcher
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractMiddlewareDispatcher implements \IteratorAggregate, RequestHandlerInterface, MiddlewareInterface
{
    /**
     * Returns the middleware.
     *
     * @return \Iterator|MiddlewareInterface[]
     */
    abstract public function getMiddleware():\Iterator;

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @return null|ResponseInterface
     * @throws MiddlewareDispatcherException
     */
    public function handle(ServerRequestInterface $request):ResponseInterface
    {
        while ($this->getIterator()->valid()) {
            $middleware = $this->getIterator()->current();
            if (!$middleware instanceof MiddlewareInterface) {
                throw MiddlewareDispatcherException::notAMiddleware($middleware);
            }
            $this->getIterator()->next();
            return $middleware->process($request, $this);
        }

        // if no middleware generated a response, sending NoResponseAvailable response
        return new NoResponseAvailable();
    }

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws MiddlewareDispatcherException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
    {
        $resp = $this->handle($request);
        if (!$resp instanceof NoResponseAvailable) {
            return $resp;
        }
        else {
            return $handler->handle($request);
        }
    }

    /**
     * Alias of getMiddleware().
     *
     * @uses AbstractMiddlewareDispatcher::getMiddleware()
     * @inheritdoc
     * @return \Generator
     */
    public function getIterator():\Generator
    {
        return $this->getMiddleware();
    }
}