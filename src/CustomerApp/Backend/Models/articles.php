<?php

namespace GoAroundCustomer\Models;

use GoAroundCustomer\utils\Logger;
use Model;

class articles implements Model
{
    protected Logger $logger;

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function all(): array
    {
        $this->logger->write('NFNFN');
        return [
            ['id' => 1, 'title' => 'Article from db'],
            ['id' => 2, 'title' => 'Other article from db']
        ];
    }
}