<?php

namespace Psr\Http\Message;

class UrlClass implements UriInterface
{
    private $scheme = '';

    private $userInfo = '';

    private $host = '';

    private $port;

    private $path = '';

    private $query = '';

    private $fragment = '';

    private $composedComponents;
    public function __construct()
    {
        $this->scheme = $_SERVER['REQUEST_SCHEME'];

        $this->userInfo = $_SERVER['USER'];

        $this->host = $_SERVER['HOSTNAME'];

        $this->port = $_SERVER['SERVER_PORT'];

        $this->path = $_SERVER['PATH'];

        $this->query = $_SERVER['QUERY_STRING'];
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getAuthority(): string
    {
        $authority = $this->host;
        if ($this->userInfo !== '') {
            $authority = $this->userInfo . '@' . $authority;
        }

        if ($this->port !== null) {
            $authority .= ':' . $this->port;
        }

        return $authority;
    }

    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getFragment()
    {
        // TODO: Implement getFragment() method.
    }

    public function withScheme($scheme): UriInterface
    {
        if ($this->scheme === $scheme) {
            return $this;
        }

        $new = clone $this;
        $new->scheme = $scheme;
        $new->composedComponents = null;

        return $new;
    }

    public function withUserInfo($user, $password = null): UriInterface
    {
        $info = $user;
        if ($password !== null) {
            $info .= ':' . $password;
        }

        if ($this->userInfo === $info) {
            return $this;
        }

        $new = clone $this;
        $new->userInfo = $info;
        $new->composedComponents = null;

        return $new;
    }

    public function withHost($host): UriInterface
    {
        if ($this->host === $host) {
            return $this;
        }

        $new = clone $this;
        $new->host = $host;
        $new->composedComponents = null;

        return $new;
    }

    public function withPort($port): UriInterface
    {
        if ($this->port === $port) {
            return $this;
        }

        $new = clone $this;
        $new->port = $port;
        $new->composedComponents = null;

        return $new;
    }

    public function withPath($path): UriInterface
    {
        if ($this->path === $path) {
            return $this;
        }

        $new = clone $this;
        $new->path = $path;
        $new->composedComponents = null;

        return $new;
    }

    public function withQuery($query): UriInterface
    {
        if ($this->query === $query) {
            return $this;
        }

        $new = clone $this;
        $new->query = $query;
        $new->composedComponents = null;

        return $new;
    }

    public function withFragment($fragment)
    {
        // TODO: Implement withFragment() method.
    }

    public static function composeComponents(?string $scheme, ?string $authority, string $path, ?string $query): string
    {
        $uri = '';

        // weak type checks to also accept null until we can add scalar type hints
        if ($scheme !== '') {
            $uri .= $scheme . ':';
        }

        if ($authority !== '' || $scheme === 'file') {
            $uri .= '//' . $authority;
        }

        if ($authority !== '' && $path !== '' && $path[0] !== '/') {
            $path = '/' . $path;
        }

        $uri .= $path;

        if ($query !== '') {
            $uri .= '?' . $query;
        }

        return $uri;
    }

    public function __toString(): string
    {
        if ($this->composedComponents === null) {
            $this->composedComponents = self::composeComponents(
                $this->scheme,
                $this->getAuthority(),
                $this->path,
                $this->query,
            );
        }

        return $this->composedComponents;
    }
}