<?php

namespace Helpers;

class DotEnvLoader
{
    function read() {
        if (!file_exists('./../.env')){
            return null;
        } else {
            return file_get_contents('./../.env');
        }
    }
}