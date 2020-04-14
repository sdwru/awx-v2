<?php

namespace spec\AwxV2\Api;

class SizeSpec extends \PhpSpec\ObjectBehavior
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
        $this->shouldHaveType('AwxV2\Api\Size');
    }

    /**
     * @param \AwxV2\Adapter\AdapterInterface $adapter
     */
    function it_returns_an_array_of_size_entity($adapter)
    {
        $total = 3;
        $adapter->get('https://api.digitalocean.com/v2/sizes?per_page=200')
            ->willReturn(sprintf('{"sizes": [{},{},{}], "meta": {"total": %d}}', $total));

        $sizes = $this->getAll();
        $sizes->shouldBeArray();
        $sizes->shouldHaveCount($total);
        foreach ($sizes as $size) {
            /**
             * @var \AwxV2\Entity\Size|\PhpSpec\Wrapper\Subject $size
             */
            $size->shouldReturnAnInstanceOf('AwxV2\Entity\Size');
        }

        $meta = $this->getMeta();
        $meta->shouldHaveType('AwxV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }
}
