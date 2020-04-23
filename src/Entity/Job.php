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
 *
 */
final class Job extends AbstractEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $url;

    /**
     * @var object
     */
    public $related;
    
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var string
     */
    public $status;
    
    /**
     * @var bool
     */
    public $failed;
}
