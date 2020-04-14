<?php

namespace spec\AwxV2\Api;

use AwxV2\Exception\HttpException;

class ActionSpec extends \PhpSpec\ObjectBehavior
{
    /**
     * @param \AwxV2\Adapter\AdapterInterface $adapter
     */
    public function let($adapter)
    {
        $this->beConstructedWith($adapter);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('AwxV2\Api\Action');
    }

    /**
     * @param \AwxV2\Adapter\AdapterInterface $adapter
     */
    public function it_returns_an_empty_array($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/actions?per_page=200')->willReturn('{"actions": []}');

        $actions = $this->getAll();
        $actions->shouldBeArray();
        $actions->shouldHaveCount(0);
    }

    /**
     * @param \AwxV2\Adapter\AdapterInterface $adapter
     */
    public function it_returns_an_array_of_action_entity($adapter)
    {
        $total = 3;
        $adapter
            ->get('https://api.digitalocean.com/v2/actions?per_page=200')
            ->willReturn(sprintf('
                {
                    "actions": [
                        {
                            "id": 1,
                            "status": "completed",
                            "type": "rename",
                            "started_at": "2014-06-10T22:25:14Z",
                            "completed_at": "2014-06-10T23:25:14Z",
                            "resource_id": 1,
                            "resource_type": "droplet",
                            "region": {
                                "name": "New York 3",
                                "slug": "nyc3",
                                "sizes": [ "32gb", "16gb", "2gb", "1gb", "4gb", "8gb", "512mb", "64gb", "48gb" ],
                                "features": [ "virtio", "private_networking", "backups", "ipv6", "metadata" ],
                                "available": true
                            },
                            "region_slug": "nyc2"
                        },
                        {
                            "id": 2,
                            "status": "completed",
                            "type": "rename",
                            "started_at": "2014-06-10T22:25:14Z",
                            "completed_at": "2014-06-10T23:25:14Z",
                            "resource_id": 2,
                            "resource_type": "droplet",
                            "region": {
                                "name": "New York 3",
                                "slug": "nyc3",
                                "sizes": [ "32gb", "16gb", "2gb", "1gb", "4gb", "8gb", "512mb", "64gb", "48gb" ],
                                "features": [ "virtio", "private_networking", "backups", "ipv6", "metadata" ],
                                "available": true
                            },
                            "region_slug": "nyc2"
                        },
                        {
                            "id": 3,
                            "status": "completed",
                            "type": "rename",
                            "started_at": "2014-06-10T22:25:14Z",
                            "completed_at": "2014-06-10T23:25:14Z",
                            "resource_id": 3,
                            "resource_type": "droplet",
                            "region": {
                                "name": "New York 3",
                                "slug": "nyc3",
                                "sizes": [ "32gb", "16gb", "2gb", "1gb", "4gb", "8gb", "512mb", "64gb", "48gb" ],
                                "features": [ "virtio", "private_networking", "backups", "ipv6", "metadata" ],
                                "available": true
                            },
                            "region_slug": "nyc2"
                        }
                    ],
                    "meta": {
                        "total": %d
                    }
                }
            ',
        $total));

        $actions = $this->getAll();
        $actions->shouldBeArray();
        $actions->shouldHaveCount($total);
        foreach ($actions as $action) {
            /**
             * @var \AwxV2\Entity\Action|\PhpSpec\Wrapper\Subject $action
             */
            $action->shouldReturnAnInstanceOf('AwxV2\Entity\Action');

            /**
             * @var \AwxV2\Entity\Region|\PhpSpec\Wrapper\Subject $actionRegion
             */
            $actionRegion = $action->region;
            $actionRegion->shouldReturnAnInstanceOf('AwxV2\Entity\Region');
        }
        $meta = $this->getMeta();
        $meta->shouldBeAnInstanceOf('AwxV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    /**
     * @param \AwxV2\Adapter\AdapterInterface $adapter
     */
    public function it_returns_an_action_entity_get_by_its_id($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/actions/123')
            ->willReturn('
                {
                    "action": {
                        "id": 123,
                        "status": "completed",
                        "type": "rename",
                        "started_at": "2014-06-10T22:25:14Z",
                        "completed_at": "2014-06-10T23:25:14Z",
                        "resource_id": 123,
                        "resource_type": "droplet",
                        "region": {
                            "name": "New York 3",
                            "slug": "nyc3",
                            "sizes": [ "32gb", "16gb", "2gb", "1gb", "4gb", "8gb", "512mb", "64gb", "48gb" ],
                            "features": [ "virtio", "private_networking", "backups", "ipv6", "metadata" ],
                            "available": true
                        },
                        "region_slug": "nyc2"
                    }
                }
            ');

        $action = $this->getById(123);
        $action->shouldReturnAnInstanceOf('AwxV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('AwxV2\Entity\Region');
        $action->regionSlug->shouldReturn('nyc2');

        $this->getMeta()->shouldBeNull();
    }

    /**
     * @param \AwxV2\Adapter\AdapterInterface $adapter
     */
    public function it_throws_an_http_exception_if_requested_action_does_not_exist($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/actions/1234567')
            ->willThrow(new HttpException('Request not processed.'));

        $this->shouldThrow(new HttpException('Request not processed.'))->during('getById', [1234567]);
    }
}
