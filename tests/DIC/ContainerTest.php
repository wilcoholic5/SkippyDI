<?php
namespace DIC;

use DIC\Mocks\Blank;
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
        $this->di->setParam('EchoService', 'text', 'Some text here.');
        $params = $this->di->getParams();
        $this->assertEquals(
            'Some text here.',
            $params['EchoService']['text']
        );
    }

    public function testSet()
    {
        $this->di->setParams('EchoService', array('var1'=>'val1', 'var2'=>'val2'));
        $this->di->add('EchoService', new EchoService);
        $service = $this->di->get('EchoService');

        $this->assertTrue(
            $service === $this->di->get('EchoService')
        );

        $this->assertFalse(
            new EchoService() === $this->di->get('EchoService')
        );
    }

    public function testSetParams()
    {
        $this->di->setParams('EchoService', array('test1'=>'val1', 'test2'=>100));

        $this->assertEquals(
            'val1',
            $this->di->getServiceParams('EchoService')['test1']
        );

        $this->assertEquals(
            100,
            $this->di->getServiceParams('EchoService')['test2']
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

//    public function testDependencies()
//    {
//        $this->di->addLazy('EchoService', array('Blank'=>$this->di->newLazy('Blank')));
//        $this->assertInstanceOf(
//            'Closure',
//            $this->di->getServiceParams('EchoService')['Blank']
//        );
//
//        /**
//         * @var EchoService
//         */
//        $this->assertInstanceOf(
//            '\\DIC\\Mocks\\EchoService',
//            $echo = $this->di->get('EchoService')
//        );
//    }
}
