<?php
namespace DIC;

use DIC\Mocks\EchoService;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Container
     */
    protected $di;

    public function setUp()
    {
        $this->di = new Container();
    }

    public function testSetParams()
    {
        $this->di->setParam('Echo', 'text', 'Some text here.');
        $params = $this->di->getParams();
        $this->assertEquals(
            'Some text here.',
            $params['Echo']['text']
        );
    }

    public function testSet()
    {
        $this->di->add('Echo', new EchoService);
        $service = $this->di->get('Echo');

        $this->assertTrue(
            $service === $this->di->get('Echo')
        );

        $this->assertFalse(
            new EchoService() === $this->di->get('Echo')
        );
    }

    public function testSetLazy()
    {
        $this->di->addLazy('EchoService');
        $this->di->setParam('EchoService', 'service', 'This is my service!');
        var_dump($this->di->getServices());

        var_dump($this->di->get('EchoService'));
    }
}
