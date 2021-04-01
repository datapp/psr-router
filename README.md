# PSR-7, PSR-15 and PSR-17 compatible router

this router library uses regular expression for routing and is compatible to the
PHP Standards Recommendations 7 (HTTP Message Interface), 15 (HTTP Handlers) 
and 17 (HTTP Factories).

## Installation

```
composer require datapp/router
```

## Usage

```
<?php

require 'vendor/autoload.php';

$router = new Router(new Dispatcher());
// route without middlewares
$router->addRoute(Route::create('GET', '/^(help)$/', new Help($responseFactory, $streamFactory)));
// route with middlewares
$router->addRoute(Route::create('POST', '/^(login)$/', new Login($responseFactory, $streamFactory))
                ->withMiddleware(new SessionMiddleware(), new AuthMiddleware()));
// using variables (named groups) in regex
$router->addRoute(Route::create('GET', '/^(user)\/(?<id>[\d]+)$/', new UserShow($responseFactory, $streamFactory))
                ->withMiddleware(new SessionMiddleware(), new AuthMiddleware()));
```


