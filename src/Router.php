<?php
declare(strict_types=1);
namespace App;

/**
 * Web/API router class, that maps methods and uri's to dynamically callable controller methods
 */
class Router
{
    protected $routes = [];

    public function get($uri, $callback) {
        $this->routes['GET'][$uri] = $callback;
    }

    public function post($uri, $callback) {
        $this->routes['POST'][$uri] = $callback;
    }

    public function map(string $method, string $path, $handler)
    {
        $path  = sprintf('/%s', ltrim($path, '/'));
        if (!isset($this->routes[$method])) {
            $this->routes[$method] = [];
        }
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(Request $request)
    {
        $method = $request->method();
        $uri = $request->uri();
        if (!isset($this->routes[$method])) {
            throw new \Exception("Request method not found: ".$method);
        }
        if (!isset($this->routes[$method][$uri])) {
            throw new \Exception("Request uri not found: ".$uri);
        }
        $handler = $this->routes[$method][$uri];

        $callable = $this->getCallable($handler);
        $callable();
    }

    public function getCallable($handler) : callable
    {
        $callable = $handler;
        if (is_string($callable) && strpos($callable, '::') !== false) {
            $callable = explode('::', $callable);
        }
        if (is_array($callable) && isset($callable[0]) && is_object($callable[0])) {
            $callable = [$callable[0], $callable[1]];
        }
        if (is_array($callable) && isset($callable[0]) && is_string($callable[0])) {
            $callable = [$this->resolveClass($callable[0]), $callable[1]];
        }
        if (is_string($callable) && method_exists($callable, '__invoke')) {
            $callable = $this->resolveClass($callable);
        }
        if (! is_callable($callable)) {
            throw new InvalidArgumentException('Could not resolve a callable for this route');
        }
        return $callable;
    }

    protected function resolveClass(string $class)
    {
        return new $class();
    }

}