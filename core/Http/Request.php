<?php

namespace Core\Http;

class Request
{
    private string $method;
    private string $uri;

    /** @var mixed[] */
    private array $params;

    /** @var array<string, string> */
    private array $headers;

    public function __construct()
    {
        $this->method = $_REQUEST['_method'] ?? $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->params = $_REQUEST;
        $this->headers = function_exists('getallheaders') ? getallheaders() : [];
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function input(string $key)
    {

        if (isset($_POST[$key])) {
            return $_POST[$key];
        }

        if (isset($_GET[$key])) {
            return $_GET[$key];
        }

        return null;
    }

    public function getUri(): string
    {
        return $this->uri;
    }
    public function getParams(): array
    {
        return $this->params;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function addParams(array $params): void
    {
        $this->params = array_merge($this->params, $params);
    }

    public function acceptJson(): bool
    {
        return (isset($_SERVER['HTTP_ACCEPT']) && $_SERVER['HTTP_ACCEPT'] === 'application/json');
    }

    public function getParam(string $key, mixed $default = null): mixed
    {
        return $this->params[$key] ?? $default;
    }
}
