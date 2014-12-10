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

    public function testSetParam()
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

    public function testSetParams()
    {
        $this->di->setParams('EchoServiceContainer', array('test'=>'val1'));

        $this->assertEquals(
            array('test'=>'val1'),
            $this->di->getServiceParams('EchoServiceContainer')
        );
    }

    public function testSetLazy()
    {
        $this->di->setParam('EchoServiceConstruct', 'service', 'This is my service!');
        $this->di->addLazy('EchoServiceConstruct');
        $services = $this->di->getServices();

        // Test that only a closure is made of the object until we use 'get' on it
        $this->assertInstanceOf(
            'Closure',
            $services['EchoServiceConstruct']
        );

        // Test that the closure gets converted to the actual object once 'get' is called
        $this->assertInstanceOf(
            'DIC\\Mocks\\EchoServiceConstruct',
            $this->di->get('EchoServiceConstruct')
        );
    }
}
