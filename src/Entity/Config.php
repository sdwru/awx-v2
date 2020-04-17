<?php

/*
 * This file is part of the AwxV2 library.
 *
 * (c) Sdwru https://github.com/sdwru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AwxV2\Entity;

/**
 * 
 */
final class Config extends AbstractEntity
{
    /**
     * @var string
     */
    public $time_zone;

    /**
     * @var object
     */
    public $license_info;

    /**
     * @var string
     */
    public $version;

    /**
     * @var string
     */
    public $ansible_version;

    /**
     * @var string
     */
    public $analytics_status;

    /**
     * @var array
     */
    public $become_methods;

    /**
     * @var string
     */
    public $project_base_dir;
}
