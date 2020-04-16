<?php

/*
 * This file is part of the AwxV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AwxV2;

use AwxV2\Adapter\AdapterInterface;
use AwxV2\Api\Account;
use AwxV2\Api\Action;
use AwxV2\Api\Certificate;
use AwxV2\Api\Domain;
use AwxV2\Api\DomainRecord;
use AwxV2\Api\Droplet;
use AwxV2\Api\FloatingIp;
use AwxV2\Api\Image;
use AwxV2\Api\Job;
use AwxV2\Api\Key;
use AwxV2\Api\LoadBalancer;
use AwxV2\Api\RateLimit;
use AwxV2\Api\Region;
use AwxV2\Api\Size;
use AwxV2\Api\Snapshot;
use AwxV2\Api\User;
use AwxV2\Api\Volume;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 * @author Graham Campbell <graham@alt-three.com>
 */
class AwxV2
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;
    
    /**
     * @string $url
     */
    protected $url;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter, $baseApiUrl)
    {
        $this->adapter = $adapter;
        $this->url = $baseApiUrl;
    }

    /**
     * @return Account
     */
    public function account()
    {
        return new Account($this->adapter, $this->url);
    }

    /**
     * @return Action
     */
    public function action()
    {
        return new Action($this->adapter, $this->url);
    }

    /**
     * @return Certificate
     */
    public function certificate()
    {
        return new Certificate($this->adapter, $this->url);
    }

    /**
     * @return Domain
     */
    public function domain()
    {
        return new Domain($this->adapter, $this->url);
    }

    /**
     * @return DomainRecord
     */
    public function domainRecord()
    {
        return new DomainRecord($this->adapter, $this->url);
    }

    /**
     * @return Droplet
     */
    public function droplet()
    {
        return new Droplet($this->adapter, $this->url);
    }

    /**
     * @return FloatingIp
     */
    public function floatingIp()
    {
        return new FloatingIp($this->adapter, $this->url);
    }

    /**
     * @return Image
     */
    public function image()
    {
        return new Image($this->adapter, $this->url);
    }
    
    /**
     * @return Job
     */
    public function job()
    {
        return new Job($this->adapter, $this->url);
    }

    /**
     * @return Key
     */
    public function key()
    {
        return new Key($this->adapter, $this->url);
    }

    /**
     * @return LoadBalancer
     */
    public function loadBalancer()
    {
        return new LoadBalancer($this->adapter, $this->url);
    }

    /**
     * @return RateLimit
     */
    public function rateLimit()
    {
        return new RateLimit($this->adapter, $this->url);
    }

    /**
     * @return Region
     */
    public function region()
    {
        return new Region($this->adapter, $this->url);
    }

    /**
     * @return Size
     */
    public function size()
    {
        return new Size($this->adapter, $this->url);
    }
    
    /**
     * @return User
     */
    public function user()
    {
        return new User($this->adapter, $this->url);
    }

    /**
     * @return Volume
     */
    public function volume()
    {
        return new Volume($this->adapter, $this->url);
    }

    /**
     * @return Snapshot
     */
    public function snapshot()
    {
        return new Snapshot($this->adapter, $this->url);
    }
}
