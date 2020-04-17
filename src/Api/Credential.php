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

use AwxV2\Entity\Credential as CredentialEntity;
use AwxV2\Exception\HttpException;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
class Credential extends AbstractApi
{
    /**
     * @param int $per_page
     * @param int $page
     *
     * @return CredentialEntity[]
     */
    public function getAll($per_page = 200, $page = 1)
    {
        $vars = $this->adapter->get(sprintf('%s/credentials/?page_size=%d&page=%d', $this->endpoint, $per_page, $page));

        $vars = json_decode($vars);

        return array_map(function ($var) {
            return new CredentialEntity($var);
        }, $vars->results);
    }

    /**
     * @param int $id
     *
     * @throws HttpException
     *
     * @return CredentialEntity
     */
    public function getById($id)
    {
        $var = $this->adapter->get(sprintf('%s/credentials/%s/', $this->endpoint, $id));

        $var = json_decode($var);

        return new CredentialEntity($var);
    }

    /**
     * @param string $name
     * @param string $ipAddress
     *
     * @throws HttpException
     *
     * @return CredentialEntity
     */
    public function create($name, $ipAddress)
    {
        $content = ['name' => $name, 'ip_address' => $ipAddress];

        $var = $this->adapter->post(sprintf('%s/credentials/', $this->endpoint), $content);

        $var = json_decode($var);

        return new CredentialEntity($var->results);
    }

    /**
     * @param string $credential
     *
     * @throws HttpException
     */
    public function delete($var)
    {
        $this->adapter->delete(sprintf('%s/credentials/%s', $this->endpoint, $var));
    }
}
