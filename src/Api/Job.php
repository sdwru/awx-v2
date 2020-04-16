<?php

/*
 * This file is part of the AwxV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AwxV2\Api;

use AwxV2\Entity\Job as JobEntity;
use AwxV2\Exception\HttpException;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
class Job extends AbstractApi
{
    /**
     * @param int $per_page
     * @param int $page
     *
     * @return DomainEntity[]
     */
    public function getAll($per_page = 200, $page = 1)
    {
        $vars = $this->adapter->get(sprintf('%s/jobs/?page_size=%d&page=%d', $this->endpoint, $per_page, $page));

        $vars = json_decode($vars);

        $this->extractMeta($vars);

        return array_map(function ($var) {
            return new JobEntity($var);
        }, $vars->results);
    }

    /**
     * @param int $id
     *
     * @throws HttpException
     *
     * @return JobEntity
     */
    public function getById($id)
    {
        $var = $this->adapter->get(sprintf('%s/jobs/%s/', $this->endpoint, $id));

        $var = json_decode($var);

        return new JobEntity($var->results);
    }

    /**
     * @param string $name
     * @param string $ipAddress
     *
     * @throws HttpException
     *
     * @return DomainEntity
     */
    public function create($name, $ipAddress)
    {
        $content = ['name' => $name, 'ip_address' => $ipAddress];

        $var = $this->adapter->post(sprintf('%s/jobs/', $this->endpoint), $content);

        $var = json_decode($var);

        return new JobEntity($var->results);
    }

    /**
     * @param string $domain
     *
     * @throws HttpException
     */
    public function delete($var)
    {
        $this->adapter->delete(sprintf('%s/jobs/%s', $this->endpoint, $var));
    }
}
