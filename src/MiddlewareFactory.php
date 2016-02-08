<?php
/**
 *  Copyright (C) Threefold systems - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited.
 */

namespace Threefold\Middleware;

use Psr\Log\LoggerInterface as Logger;
use Threefold\Middleware\Exception\MiddlewareException;
use Threefold\Middleware\Middleware;
use GuzzleHttp\Client;

/**
 * Middleware Factory
 *
 * @author Aine Hickey <ahickey@threefoldsystems.com>
 */
class MiddlewareFactory
{
    const MIDDLEWARE_PRODUCTION = 'production';
    const MIDDLEWARE_UAT = 'uat';
    const MIDDLEWARE_FAKE = 'fake';

    /**
     * Middleware production URL
     */
    const PRODUCTION_URL = 'https://servicegateway.agora-inc.com/middleware/';
    /**
     * Middleware user acceptance testing URL
     */
    const UAT_URL = 'https://uat.servicegateway.agora-inc.com/middleware/';

    /**
     * @var Logger
     */
    protected $log;

    /**
     * Constuctor
     *
     * @param Logger $log
     */
    public function __construct(Logger $log)
    {
        $this->log = $log;
    }

    /**
     * Create Agora middleware wrapper
     *
     * @param string $token Token
     * @param string $environment (optional, default: uat) Environment (options: production|uat|fake)
     * @return MiddlewareInterface
     * @throws MiddlewareException If invalid environment is given
     */
    public function create($token, $environment = 'uat')
    {
        $this->log->debug('Creating new middleware', ['environment' => $environment]);

        switch ($environment) {
            case self::MIDDLEWARE_PRODUCTION:
                $guzzleClient = new Client(['base_uri' => self::PRODUCTION_URL]);
                return new Middleware($this->log, $guzzleClient, $token);

            case self::MIDDLEWARE_UAT:
                $guzzleClient = new Client(['base_uri' => self::UAT_URL]);
                return new Middleware($this->log, $guzzleClient, $token);

            case self::MIDDLEWARE_FAKE:
                $guzzleClient = new Client(['base_uri' => 'http://example.com']);
                return new MiddlewareFaker($this->log, $guzzleClient, $token);

            default:
                $this->log->error('Invalid environment', ['environment' => $environment]);
                throw new MiddlewareException('Invalid environment: ' . $environment);
        }
    }
}
