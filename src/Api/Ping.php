<?php

/*
 * This file is part of the AwxV2 library.
 *
 * (c) Sdwru https://github.com/sdwru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AwxV2\Api;

use Awx\Entity\Ping as PingEntity;

/**
 * 
 * 
 */
class Ping extends AbstractApi
{
    /**
     * @return PingEntity
     */
    public function get()
    {
        $var = $this->adapter->get(sprintf('%s/ping/', $this->endpoint));

        $var = json_decode($var);

        return new PingEntity($var);
    }
}
