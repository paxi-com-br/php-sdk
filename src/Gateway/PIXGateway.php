<?php

namespace PAXI\SDK\Gateway;

use PAXI\SDK\PAXI;

class PIXGateway extends BaseGateway
{
    /**
     * @param PAXI $paxi
     */
    public function __construct(PAXI $paxi)
    {
        parent::__construct($paxi);
    }
}
