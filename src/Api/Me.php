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
    public function getAll($per_page = 200, $page = 1)
    {
        $vars = $this->adapter->get(sprintf('%s/me/?page_size=%d&page=%d', $this->endpoint, $per_page, $page));

        $vars = json_decode($vars);

        return array_map(function ($var) {
            return new JobEntity($var);
        }, $vars->results);
    }
}
