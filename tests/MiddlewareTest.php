<?php
/**
 *  Copyright (C) Threefold systems - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace Threefold\Middleware;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use Threefold\Middleware\Exception\AdvantageConnectionException;


/**
 * @coversDefaultClass
 */
class MiddlewareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Bug: Advantage Server Exception - failed (500 error)
     * https://threefoldsystems.teamwork.com/tasks/4928239
     *
     * @expectedException \Threefold\Middleware\Exception\AdvantageConnectionException
     */
    public function testAdvantageServerException()
    {
        // Setup
        $log = new \Monolog\Logger('middleware');
        $log->pushHandler(new \Monolog\Handler\TestHandler());
        $token = '123abc';
        $customerId = '123456';

        // Adding a mock exception
        $mock = new MockHandler([
            new Response(500, ['X-Foo' => 'Bar'], '"Failed, advantage connection"'),
            new RequestException(
                "Server error: `GET"
                . " https://servicegateway.agora-inc.com/middleware/adv/list/signup/customernumber/$customerId`",
                new Request('GET', "adv/list/signup/customernumber/$customerId")
            )
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        // Test
        $object = new Middleware($log, $client, $token);
        $object->getCustomerListSignupsById($customerId);
    }
}
