<?php
/**
 *  Copyright (C) Threefold systems - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace Threefold\Middleware\Exception;

/**
 * Email already exists exception
 *
 * @author Aine Hickey <ahickey@threefoldsystems.com>
 * @package Threefold\Middleware\Exception
 */
class EmailAlreadyExistsException extends \RuntimeException implements MiddlewareExceptionInterface
{
}
