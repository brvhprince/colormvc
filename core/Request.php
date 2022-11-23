<?php
/**
 * Project: colormvc
 * File: Requests.php
 * Author: pennycodes
 * Organization: Colorbrace LLC
 * Author URI: https://pennycodes.dev
 * Created: 23/11/2022 at 10:16 am.
 *
 * Copyright (c) 2022 Colorbrace LLC. All rights reserved.
 */

namespace app\core;

class Request
{
    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position === false) {
            return $path;
        }
        return substr($path, 0, $position);
    }

    /**
     * Returns the value of the request method in lowercase
     * getMethod
     * @return string
     */

    public function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }


    public function isGet(): bool
    {
        return $this->method() === 'get';
    }

    public function isPost(): bool
    {
        return $this->method() === 'post';
    }

    public function getBody(): array
    {
        $body = [];
        if ($this->method() === 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->method() === 'post') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }

    public function getBodyParam(string $key)
    {
        $body = $this->getBody();
        return $body[$key] ?? '';
    }

    public function getQueryParams(): array
    {
        $queryParams = [];
        foreach ($_GET as $key => $value) {
            $queryParams[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        return $queryParams;
    }

    public function getQueryParam(string $key)
    {
        $queryParams = $this->getQueryParams();
        return $queryParams[$key] ?? '';
    }

    public function getPostParams(): array
    {
        $postParams = [];
        foreach ($_POST as $key => $value) {
            $postParams[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        return $postParams;
    }

    public function getPostParam(string $key)
    {
        $postParams = $this->getPostParams();
        return $postParams[$key] ?? '';
    }

    public function getCookieParams(): array
    {
        $cookieParams = [];
        foreach ($_COOKIE as $key => $value) {
            $cookieParams[$key] = filter_input(INPUT_COOKIE, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        return $cookieParams;
    }

    public function getCookieParam(string $key)
    {
        $cookieParams = $this->getCookieParams();
        return $cookieParams[$key] ?? '';
    }

    public function getServerParams(): array
    {
        $serverParams = [];
        foreach ($_SERVER as $key => $value) {
            $serverParams[$key] = filter_input(INPUT_SERVER, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        return $serverParams;
    }

    public function getServerParam(string $key)
    {
        $serverParams = $this->getServerParams();
        return $serverParams[$key] ?? '';
    }




}