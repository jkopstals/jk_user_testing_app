<?php
namespace App;

/**
 * Request class centralizes all data normally associated with a request
 * Has some additional functions for internal redirects (e.g. passing errors)
 */

class Request
{

    private $server = [];
    private $cookies = [];
    private $get = [];
    private $post = [];
    private $body;
    private $uri;
    private $method;
    private $errors;
    
    public function __construct(
        array $server = [],
        array $cookies = [],
        array $get = [],
        $post = null,
        $body = 'php://input'
    ) {
        $this->server  = $server ?: $_SERVER;
        $this->cookies  = $_COOKIE;
        $this->get   = $_GET;
        $this->post    = $_POST;
        if ($body === 'php://input') {
            //$this->body = new \PhpInputStream();
        }

        $this->uri = '/';
        if (isset($this->server['REQUEST_URI'])) {
            $this->uri = $this->server['REQUEST_URI'];
        }
        $this->method = 'GET';
        if (isset($this->server['REQUEST_METHOD'])) {
            $this->method = $this->server['REQUEST_METHOD'];
        }
    }

    public function setErrors($errors = null) {
        $this->errors = $errors;
    }
    
    public function getErrors() {
        return $this->errors;
    }

    public function method() {
        return $this->method;
    }

    public function uri() {
        return $this->uri;
    }

    public function getParams() {
        return $this->get;
    }

    public function getParam($param) {
        if (isset($this->get[$param])) {
            return $this->get[$param];
        }
    }

    public function postParams() {
        return $this->post;
    }
    
    public function postParam($param) {
        if (isset($this->post[$param])) {
            return $this->post[$param];
        }
    }
}