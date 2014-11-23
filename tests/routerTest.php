<?php
/**
 * routerTest
 * 
 * @author erikstabij
 */
define('APPLICATION_PATH', 'application/');
define('SYSTEM_PATH', 'system/');

require_once 'system/core/router.php';

class routerTest extends PHPUnit_Framework_TestCase {

	protected $router;

    public function setUp() {
        $this->router = router::getInstance();
    }
    
    public function tearDown() {
        unset($this->router);
    }

    /**
     * @dataProvider routingData
     */
    public function testRoute($fromURI, $toController) { 
    	$destination = $this->router->getController('', $fromURI);
        $this->assertEquals($toController, $destination);
    }
    
    // provides URI and to where it should be routed to
    // to the route() method:
    // uri -> array(controller, method, array(method params))
    public function routingData() {
        return array(
          array('/', 	array('class'=>'index_controller','method'=>'index','params'=>array()))
//          array('/foo/bar', array('class'=>'index_controller','method'=>'index', 'params'=>array('foo','bar'))),
//          array('/foo/baz', array('class'=>'index_controller','method'=>'index', 'params'=>array('foo','baz')))
//          array('/test1', array('class'=>'index_controller','method'=>'index', 'params'=>array('test1'))),
//          array('/test2', array('class'=>'index_controller','method'=>'index','params'=> array('test2'))),
//          array('/Test1', array('class'=>'index_controller','method'=>'index', 'params'=>array('test1'))),
//          array('/TEST2', array('class'=>'index_controller','method'=>'index', 'params'=>array('test2'))),
//          array('/test1/test2/test3', array('class'=>'index_controller','method'=>'index', 'params'=>array('test1', 'test2', 'test3'))),
//          array('/foo/bar/tom/jerry', array('class'=>'index_controller','method'=>'index', 'params'=>array('foo', 'bar', 'tom', 'jerry')))
        );
    }
}
