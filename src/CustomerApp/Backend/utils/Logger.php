<?php

namespace GoAroundCustomer\utils;

class Logger
{
    public function write($msg){
        file_put_contents('log.txt', $msg . PHP_EOL);
    }
}