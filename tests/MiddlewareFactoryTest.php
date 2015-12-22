<?php
/**
 *  Copyright (C) Threefold systems - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace Threefold\Middleware;

use Psr\Log\LoggerInterface;

/**
 * Class MiddlewareFactoryTest
 *
 * @author Aine Hickey <ahickey@threefoldsystems.com>
 * @package Threefold\Middleware
 * @coversDefaultClass \Threefold\Middleware\MiddlewareFactory
 */
class MiddlewareFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MiddlewareFactory
     */
    protected $object;

    /**
     * @covers ::__construct
     */
    protected function setUp()
    {
        parent::setUp();

        $log = new \Monolog\Logger('name');
        $log->pushHandler(new \Monolog\Handler\TestHandler());

        $this->object = new MiddlewareFactory($log);
    }

    /**
     * @covers ::create
     */
    public function testCreateDefault()
    {
        $token = 'abc123';
        $middleware = $this->object->create($token);
        $this->assertInstanceOf('\Threefold\Middleware\Middleware', $middleware);
    }

    /**
     * @covers ::create
     */
    public function testCreateWithProduction()
    {
        $token = 'abc123';
        $middleware = $this->object->create($token, MiddlewareFactory::MIDDLEWARE_PRODUCTION);
        $this->assertInstanceOf('\Threefold\Middleware\Middleware', $middleware);
    }

    /**
     * @covers ::create
     */
    public function testCreateWithUat()
    {
        $token = 'abc123';
        $middleware = $this->object->create($token, MiddlewareFactory::MIDDLEWARE_UAT);
        $this->assertInstanceOf('\Threefold\Middleware\Middleware', $middleware);
    }

    /**
     * @covers ::create
     */
    public function testCreateWithFaker()
    {
        $token = 'abc123';
        $middleware = $this->object->create($token, MiddlewareFactory::MIDDLEWARE_FAKE);
        $this->assertInstanceOf('\Threefold\Middleware\MiddlewareFaker', $middleware);
    }

    /**
     * @covers ::create
     * @expectedException Threefold\Middleware\Exception\MiddlewareException
     * @expectedExceptionMessage Invalid environment: banana
     */
    public function testCreateWithInvalidEnvThrowsException()
    {
        $token = 'abc123';
        $this->object->create($token, 'banana');
    }
}
