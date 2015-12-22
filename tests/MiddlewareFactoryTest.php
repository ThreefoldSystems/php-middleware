<?php
/**
 *  Copyright (C) Threefold systems - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace Threefold\Middleware;

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
     * @var \Monolog\Handler\TestHandler
     */
    protected $logTestHandler;

    /**
     * @covers ::__construct
     */
    protected function setUp()
    {
        parent::setUp();

        $log = new \Monolog\Logger('middleware');
        $this->logTestHandler = new \Monolog\Handler\TestHandler();
        $log->pushHandler($this->logTestHandler);

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

        $logRecords = $this->logTestHandler->getRecords();
        $this->assertCount(1, $logRecords);
        $this->assertStringEndsWith(
            'middleware.DEBUG: Creating new middleware {"environment":"uat"} []' . PHP_EOL,
            $logRecords[0]['formatted']
        );
    }

    /**
     * @covers ::create
     */
    public function testCreateWithProduction()
    {
        $token = 'abc123';
        $middleware = $this->object->create($token, MiddlewareFactory::MIDDLEWARE_PRODUCTION);
        $this->assertInstanceOf('\Threefold\Middleware\Middleware', $middleware);

        $logRecords = $this->logTestHandler->getRecords();
        $this->assertCount(1, $logRecords);
        $this->assertStringEndsWith(
            'middleware.DEBUG: Creating new middleware {"environment":"production"} []' . PHP_EOL,
            $logRecords[0]['formatted']
        );
    }

    /**
     * @covers ::create
     */
    public function testCreateWithUat()
    {
        $token = 'abc123';
        $middleware = $this->object->create($token, MiddlewareFactory::MIDDLEWARE_UAT);
        $this->assertInstanceOf('\Threefold\Middleware\Middleware', $middleware);

        $logRecords = $this->logTestHandler->getRecords();
        $this->assertCount(1, $logRecords);
        $this->assertStringEndsWith(
            'middleware.DEBUG: Creating new middleware {"environment":"uat"} []' . PHP_EOL,
            $logRecords[0]['formatted']
        );
    }

    /**
     * @covers ::create
     */
    public function testCreateWithFaker()
    {
        $token = 'abc123';
        $middleware = $this->object->create($token, MiddlewareFactory::MIDDLEWARE_FAKE);
        $this->assertInstanceOf('\Threefold\Middleware\MiddlewareFaker', $middleware);

        $logRecords = $this->logTestHandler->getRecords();
        $this->assertCount(1, $logRecords);
        $this->assertStringEndsWith(
            'middleware.DEBUG: Creating new middleware {"environment":"fake"} []' . PHP_EOL,
            $logRecords[0]['formatted']
        );
    }

    /**
     * @covers ::create
     * @expectedException \Threefold\Middleware\Exception\MiddlewareException
     * @expectedExceptionMessage Invalid environment: banana
     */
    public function testCreateWithInvalidEnvThrowsException()
    {
        try {
            $token = 'abc123';
            $this->object->create($token, 'banana');
        } catch (\Exception $e) {
            $logRecords = $this->logTestHandler->getRecords();
            $this->assertCount(2, $logRecords);
            $this->assertStringEndsWith(
                'middleware.DEBUG: Creating new middleware {"environment":"banana"} []' . PHP_EOL,
                $logRecords[0]['formatted']
            );
            $this->assertStringEndsWith(
                'middleware.ERROR: Invalid environment {"environment":"banana"} []' . PHP_EOL,
                $logRecords[1]['formatted']
            );

            throw $e;
        }
    }
}
