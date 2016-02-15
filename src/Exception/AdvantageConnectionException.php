<?php
/**
 *  Copyright (C) Threefold systems - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace Threefold\Middleware\Exception;

/**
 * Advantage Connection Exception
 *
 * Something has gone wrong with the connection to Advantage
 *
 * @author Aine Hickey <ahickey@threefoldsystems.com>
 * @package Threefold\Middleware\Exception
 */
class AdvantageConnectionException extends \RuntimeException implements MiddlewareExceptionInterface
{
}
