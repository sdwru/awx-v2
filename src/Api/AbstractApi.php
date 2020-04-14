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

use AwxV2\Adapter\AdapterInterface;
use AwxV2\Entity\Meta;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 * @author Graham Campbell <graham@alt-three.com>
 */
abstract class AbstractApi
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var Meta
     */
    protected $meta;

    /**
     * @param AdapterInterface $adapter
     * @param string|null      $endpoint
     */
    public function __construct(AdapterInterface $adapter, $baseEndpoint)
    {
        $this->adapter = $adapter;
        $this->endpoint = $baseEndpoint . '/v2';
    }

    /**
     * @param \stdClass $data
     *
     * @return Meta|null
     */
    protected function extractMeta(\StdClass $data)
    {
        if (isset($data->meta)) {
            $this->meta = new Meta($data->meta);
        }

        return $this->meta;
    }

    /**
     * @return Meta|null
     */
    public function getMeta()
    {
        return $this->meta;
    }
}
