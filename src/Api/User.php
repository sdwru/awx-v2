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

use AwxV2\Entity\User as UserEntity;
use AwxV2\Exception\HttpException;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
class User extends AbstractApi
{
    /**
     * @param int $per_page
     * @param int $page
     *
     * @return DomainEntity[]
     */
    public function getAll($per_page = 200, $page = 1)
    {
        $users = $this->adapter->get(sprintf('%s/users/?page_size=%d&page=%d', $this->endpoint, $per_page, $page));

        $users = json_decode($users);

        return array_map(function ($user) {
            return new UserEntity($user);
        }, $users->results);
    }

    /**
     * @param string $domainName
     *
     * @throws HttpException
     *
     * @return DomainEntity
     */
    public function getByName($domainName)
    {
        $domain = $this->adapter->get(sprintf('%s/domains/%s', $this->endpoint, $domainName));

        $domain = json_decode($domain);

        return new DomainEntity($domain->domain);
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

        $domain = $this->adapter->post(sprintf('%s/domains', $this->endpoint), $content);

        $domain = json_decode($domain);

        return new DomainEntity($domain->domain);
    }

    /**
     * @param string $domain
     *
     * @throws HttpException
     */
    public function delete($domain)
    {
        $this->adapter->delete(sprintf('%s/domains/%s', $this->endpoint, $domain));
    }
}
