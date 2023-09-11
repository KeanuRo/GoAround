<?php

namespace Psr\Http\Message;

interface ServerRequestInterface extends MessageInterface
{
    public function getStatusCode();

    public function withStatus($code, $reasonPhrase = '');

    public function getReasonPhrase();
}