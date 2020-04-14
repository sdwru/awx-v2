<?php

namespace spec\AwxV2\Api;

class RegionSpec extends \PhpSpec\ObjectBehavior
{
    /**
     * @param \AwxV2\Adapter\AdapterInterface $adapter
     */
    function let($adapter)
    {
        $this->beConstructedWith($adapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('AwxV2\Api\Region');
    }

    /**
     * @param \AwxV2\Adapter\AdapterInterface $adapter
     */
    function it_returns_an_array_of_region_entity($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/regions?per_page=200')->willReturn('{"regions": [{},{},{}]}');

        $regions = $this->getAll();
        $regions->shouldBeArray();
        $regions->shouldHaveCount(3);

        foreach ($regions as $region) {
            /**
             * @var \AwxV2\Entity\Region|\PhpSpec\Wrapper\Subject $region
             */
            $region->shouldReturnAnInstanceOf('AwxV2\Entity\Region');
        }
    }
}
