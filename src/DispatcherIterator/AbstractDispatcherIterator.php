<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2018 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material is strictly forbidden unless prior    |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     25/09/2018
// Project:  MiddlewareDispatcher
//
declare(strict_types=1);
namespace CodeInc\MiddlewareDispatcher\DispatcherIterator;
use CodeInc\MiddlewareDispatcher\AbstractDispatcher;


/**
 * Class AbstractDispatcherIterator
 *
 * @package CodeInc\MiddlewareDispatcher\DispatcherIterator
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractDispatcherIterator implements \IteratorAggregate
{
    /**
     * Returns the middleware dispatcher.
     *
     * @return AbstractDispatcher
     */
    abstract protected function getDispatcher():AbstractDispatcher;

    /**
     * @inheritdoc
     * @return \Generator
     */
    public function getIterator():\Generator
    {
        yield from $this->getDispatcher()->getMiddleware();
    }
}
