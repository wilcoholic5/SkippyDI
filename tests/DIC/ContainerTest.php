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

    public function testDependencies()
    {
        $this->di->addLazy('\\DIC\\Mocks\\EchoService', array('\\DIC\\Mocks\\Blank' => $this->di->newLazy('\\DIC\\Mocks\\Blank'), '\\DIC\\Mocks\\BlankTwo' => $this->di->newLazy('\\DIC\\Mocks\\BlankTwo'), 'name' => 'Echo'));
        $this->assertInstanceOf(
            'Closure',
            $this->di->getServiceParams('\\DIC\\Mocks\\EchoService')['\\DIC\\Mocks\\Blank']
        );
        /**
         * @var EchoService
         */
        $echo = $this->di->get('\\DIC\\Mocks\\EchoService');
        /**
         * @var EchoService
         */
        $this->assertInstanceOf(
            '\\DIC\\Mocks\\EchoService',
            $echo
        );

        $this->assertInstanceOf(
            '\\DIC\\Mocks\\Blank',
            $echo->getBlank()
        );

        $this->assertInstanceOf(
            '\\DIC\\Mocks\\BlankTwo',
            $echo->getBlankTwo()
        );

        $this->assertEquals(
            'Echo',
            $echo->getName()
        );
    }

//    public function testSetParam()
//    {
//        $this->di->setParam('\\DIC\\Mocks\\EchoService', 'text', 'Some text here.');
//
//        $this->assertEquals(
//            'Some text here.',
//            $this->di->getParams()['\\DIC\\Mocks\\EchoService']['text']
//        );
//
//        $this->di->setParam('\\DIC\\Mocks\\EchoService', 'text', 'Some other text here.');
//
//        $this->assertEquals(
//            'Some other text here.',
//            $this->di->getParams()['\\DIC\\Mocks\\EchoService']['text']
//        );
//
//        $this->di->setParam("\\DIC\\Mocks\\Auto", "echo", $this->di->newLazy("\\DIC\\Mocks\\EchoService"));
//        $this->assertInstanceOf(
//            'closure',
//            $this->di->getParams()["\\DIC\\Mocks\\Auto"]["echo"]
//        );
//    }
//
//    public function testSet()
//    {
//        $this->di->setParams('\\DIC\\Mocks\\EchoService', array('var1'=>'val1', 'var2'=>'val2'));
//        $this->di->add('\\DIC\\Mocks\\EchoService', new EchoService);
//        $service = $this->di->get('\\DIC\\Mocks\\EchoService');
//
//        $this->assertTrue(
//            $service === $this->di->get('\\DIC\\Mocks\\EchoService')
//        );
//
//        $this->assertFalse(
//            new EchoService() === $this->di->get('\\DIC\\Mocks\\EchoService')
//        );
//    }
//
//    public function testSetParams()
//    {
//        $this->di->setParams('\\DIC\\Mocks\\EchoService', array('test1'=>'val1', 'test2'=>100));
//        $this->di->setParams('\\DIC\\Mocks\\EchoServiceConstruct', array('test100' => 'val100'));
////        var_dump($this->di->getParams());
//
//        $this->assertEquals(
//            'val1',
//            $this->di->getServiceParams('\\DIC\\Mocks\\EchoService')['test1']
//        );
//
//        $this->assertEquals(
//            100,
//            $this->di->getServiceParams('\\DIC\\Mocks\\EchoService')['test2']
//        );
//    }
//
//    public function testAdd()
//    {
//        $this->di->add('\\DIC\\Mocks\\EchoService', new EchoService());
//
//        $this->assertInstanceOf(
//            '\\DIC\\Mocks\\EchoService',
//            $this->di->get('\\DIC\\Mocks\\EchoService')
//        );
//    }
//
//    public function testSetLazy()
//    {
//        $this->di->setParam('\\DIC\\Mocks\\EchoServiceConstruct', 'service', 'This is my service!');
//
//        $this->di->addLazy('\\DIC\\Mocks\\EchoServiceConstruct');
//        $services = $this->di->getServices();
//        // Test that only a closure is made of the object until we use 'get' on it
//        $this->assertInstanceOf(
//            'Closure',
//            $services['\\DIC\\Mocks\\EchoServiceConstruct']
//        );
//
//        // Test that the closure gets converted to the actual object once 'get' is called
//        $this->assertInstanceOf(
//            '\\DIC\\Mocks\\EchoServiceConstruct',
//            $this->di->get('\\DIC\\Mocks\\EchoServiceConstruct')
//        );
//    }
}
