# php-middleware
PHP code to connect to Pub Services middleware


## Installation

To install this package pull it in through Composer.

    "require": {
        "threefold/middleware": "*"
    },
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/ThreefoldSystems/php-middleware"
        }
    ]
    
If you want the latest dev version, use:

    "threefold/middleware": "1.1.*-dev"
    
## Usage

A token and a logger are needed to make a call. Each customer has their own token, which should be in Confluence.

The logger must implement [Psr\Log\LoggerInterface](https://packagist.org/packages/psr/log) based on 
[PSR-3 log](http://www.php-fig.org/psr/psr-3/). In the below examples we use 
[Monolog](https://github.com/Seldaek/monolog), but any compliant log
can be used.

    $log = new \Monolog\Logger('middleware');
    $log->pushHandler(new \Monolog\Handler\TestHandler());
    
    $factory = new MiddlewareFactory($log);
    $middleware = $factory->create($token);
    
### Environment

We can connect to the UAT server and production server. There is also a fake middleware class, that can be used for 
local testing. The default value returned is UAT.

```php
// Production
$middleware = $factory->create($token, MiddlewareFactory::MIDDLEWARE_PRODUCTION);
// UAT
$middleware = $factory->create($token, MiddlewareFactory::MIDDLEWARE_UAT);
// Fake - calls return hard coded values
$middleware = $factory->create($token, MiddlewareFactory::MIDDLEWARE_FAKE);
```
    
**If you use the fake class, know that not all methods have been faked. Please let me know if you need one faked!**
    
### Calls

The list of all possible calls are in the MiddlewareInterface.

    $results = $middleware->getAccountByEmail('fred@example.com'); 
    
The results will come back as a JSON string.
    
### Putting it all together

Code:

    $token = 'ABC*123abc123abc123abc$';
    
    $log = new \Monolog\Logger('middleware');
    $log->pushHandler(new Monolog\Handler\TestHandler());
    
    $factory = new \Threefold\Middleware\MiddlewareFactory($log);
    $middleware = $factory->create($token, \Threefold\Middleware\MiddlewareFactory::MIDDLEWARE_UAT);
 
    $results = $middleware->getAccountByEmail('fred@example.com');
    
    
Results:
    
    [
      {
        "cviNbr": "000001234567",
        "customerNumber": "000087654321",
        "role": "",
        "temp": false,
        "password": "xxx",
        "id": {
          "userName": "FRED123",
          "portalCode": {
            "authGroup": "xxx"
          },
          "authType": "L"
        },
        "denyAccess": "N",
        "authStatus": "A"
        }
    ]
    