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

use AwxV2\Entity\Me as MeEntity;
use AwxV2\Exception\HttpException;

/**
 *
 *
 */
class Me extends AbstractApi
{
    /**
     * @param int $per_page
     * @param int $page
     *
     * @return MeEntity[]
     */
    public function get()
    {
        $var = $this->adapter->get(sprintf('%s/me/', $this->endpoint));

        $var = json_decode($var);

        return new MeEntity($var);
    }
}
