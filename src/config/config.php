<?php

use Config\Config\Config;

return [
    'dotEnvPath' => ROOT_DIR . DIRECTORY_SEPARATOR . '.env',
    'databasePassword' => Config::getEnv('DATABASE_PASSWORD'),
];