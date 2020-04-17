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

use Awx\Entity\Config as AccountEntity;

/**
 * 
 * 
 */
class Config extends AbstractApi
{
    /**
     * @return ConfigEntity
     */
    public function get()
    {
        $var = $this->adapter->get(sprintf('%s/config/', $this->endpoint));

        $var = json_decode($var);

        return new ConfigEntity($var);
    }
    
    
}
