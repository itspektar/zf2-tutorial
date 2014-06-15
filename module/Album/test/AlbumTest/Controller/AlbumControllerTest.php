<?php

namespace AlbumTest\Controller;

use Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Album\Controller\AlbumController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;
use Zend\Stdlib\Parameters;

class AlbumControllerTest extends \PHPUnit_Framework_TestCase
{
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new AlbumController();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'album'));
        $this->event      = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
    }

	public function testIndexActionCanBeAccessed()
	{
	    $this->routeMatch->setParam('action', 'index');

	    $this->controller->dispatch($this->request);
	    $response = $this->controller->getResponse();

	    $this->assertEquals(200, $response->getStatusCode());
	}

	public function testAddActionCanBeAccessed()
	{
		$this->routeMatch->setParam('action', 'add');

		$this->controller->dispatch($this->request);
		$response = $this->controller->getResponse();

		$this->assertSame(200, $response->getStatusCode());
	}

    public function testAddAction()
   	{
        $this->routeMatch->setParam('action', 'add');

        $params = array(
            'id' => 7,
            'artist' => 'TestArtist',
            'title' => 'TestTitle',

        );
        $parameters = new Parameters();
        $parameters->fromArray($params);
        // $this->request->setQuery($parameters);
        $this->request->setMethod('POST')
             ->setPost($parameters);
        $this->controller->dispatch($this->request);

        $response = $this->controller->getResponse();

   		$this->assertSame(302, $response->getStatusCode());
   	}

    public function testAddActionWithInvalidData()
   	{
        $this->routeMatch->setParam('action', 'add');

        $params = array(
            'artist' => 'TestArtist',
            'title' => 'TestTitle',

        );
        $parameters = new Parameters();
        $parameters->fromArray($params);
        // $this->request->setQuery($parameters);
        $this->request->setMethod('POST')
             ->setPost($parameters);
        $this->controller->dispatch($this->request);

        $response = $this->controller->getResponse();

   		$this->assertSame(200, $response->getStatusCode());
   	}

	public function testEditActionCanBeAccessed()
	{
		$this->routeMatch->setParam('action', 'edit');
		$this->routeMatch->setParam('id', '1');
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
	}

    /**
     * Testing edit action
     * /album/edit/id/7
     *
     * @author skocic <skocic@goodgamestudios.com>
     */
    public function testEditAction()
   	{
        $id = 7;
        $this->routeMatch->setParam('action', 'edit');
        $this->routeMatch->setParam('id', $id);

        $params = array(
            'id' => $id,
            'artist' => 'Some Artist',
            'title' => 'TestTitle ' . date('Y-m-d H:i:s'),
        );
        $parameters = new Parameters();
        $parameters->fromArray($params);
        $this->request->setMethod('POST')
             ->setPost($parameters);

        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

   		$this->assertEquals(302, $response->getStatusCode());
   	}

    /**
     * Testing edit action with invalid data
     * /album/edit/id/7
     *
     * @author skocic <skocic@goodgamestudios.com>
     */
    public function testEditActionWithInvalidData()
   	{
        $id = 7;
        $this->routeMatch->setParam('action', 'edit');
        $this->routeMatch->setParam('id', $id);

        $params = array(
            'artist' => 'TestArtist2',
            'title' => 'TestTitle2',
        );
        $parameters = new Parameters();
        $parameters->fromArray($params);
        $this->request->setMethod('POST')
             ->setPost($parameters);

        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

   		$this->assertEquals(200, $response->getStatusCode());
   	}

	public function testGetAlbumTableReturnsAnInstanceOfAlbumTable()
	{
		$this->assertInstanceOf('Album\Model\AlbumTable', $this->controller->getAlbumTable());
	}

	public function testEditActionRedirect()
	{
		$this->routeMatch->setParam('action', 'edit');
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
	}

	public function testDeleteActionCanBeAccessed() {
		$this->routeMatch->setParam('action', 'delete');
	    $this->routeMatch->setParam('id', '1');
	    $this->controller->dispatch($this->request);
	    $response = $this->controller->getResponse();
	    $this->assertEquals(200, $response->getStatusCode());
	}

	public function testDeleteActionRedirect()
	{
		$this->routeMatch->setParam('action', 'delete');
	    $this->controller->dispatch($this->request);
	    $response = $this->controller->getResponse();
	    $this->assertEquals(302, $response->getStatusCode());
	}

    /**
     * Testing edit action
     * /album/delete/id/7
     *
     * @author skocic <skocic@goodgamestudios.com>
     */
    public function testDeleteAction()
   	{
        $id = 10;
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $id);

        $params = array(
            'id' => $id,
            'del' => 'Yes',
        );
        $parameters = new Parameters();
        $parameters->fromArray($params);
        $this->request->setMethod('POST')
             ->setPost($parameters);

        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

   		$this->assertEquals(302, $response->getStatusCode());
   	}

}
