<?php

namespace AlbumTest\Model;

use Album\Model\AlbumTable;
use Album\Model\Album;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

class AlbumTableTest extends PHPUnit_Framework_TestCase
{
	public function testFetchAllReturnsAllAlbums()
	{
		$resultSet = new ResultSet();
		/** @var Zend\Db\TableGateway\TableGateway $mockTableGateway */
		$mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
		$mockTableGateway->expects($this->once())
		                 ->method('select')
		                 ->with()
		                 ->will($this->returnValue($resultSet));
		$albumTable = new AlbumTable($mockTableGateway);
		$this->assertSame($resultSet, $albumTable->fetchAll());
	}

	public function testCanRetrieveAnAlbumByItsId() {
		$album = new Album();
		$album->exchangeArray(array(
			'id' => 123,
            'artist' => 'The Military Wives',
			'title'  => 'In My Dreams'));
		$resultSet = new ResultSet();
		$resultSet->setArrayObjectPrototype(new Album());
		$resultSet->initialize(array($album));
		/** @var Zend\Db\TableGateway\TableGateway $mockTableGateway */
		$mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
			array('select'),
			array(),
			'',
			false);
		$mockTableGateway->expects($this->once())
						 ->method('select')
						 ->with(array('id' => 123))
						 ->will($this->returnValue($resultSet));
		$albumTable = new AlbumTable($mockTableGateway);
		$this->assertSame($album, $albumTable->getAlbum(123));
	}

	public function testCanDeleteAnAlbumByItsId()
	{
		/** @var Zend\Db\TableGateway\TableGateway $mockTableGateway */
		$mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
			array('delete'),
			array(),
			'',
			false);
		$mockTableGateway->expects($this->once())
						 ->method('delete')
						 ->with(array('id' => 123));
		$albumTable = new AlbumTable($mockTableGateway);
        $albumTable->deleteAlbum(123);
	}

	public function testSaveAlbumWillInsertNewAlbumsIfTheyDontAlreadyHaveAnId()
	{
		$albumData = array('artist' => 'The Military Wives', 'title' => 'In My Dreams');
		$album = new Album();
		$album->exchangeArray($albumData);
		/** @var Zend\Db\TableGateway\TableGateway $mockTableGateway */
		$mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
			array('insert'),
			array(),
			'',
			false);
		$mockTableGateway->expects($this->once())
						 ->method('insert')
						 ->with($albumData);
		$albumTable = new AlbumTable($mockTableGateway);
        $albumTable->saveAlbum($album);
	}

	public function testSaveAlbumWillUpdateExistingAlbumsIfTheyAlreadyHaveAnId() {
		$albumData = array('id' => 123, 'artist' => 'The Military Wives', 'title' => 'In My Dreams');
		$album = new Album();
		$album->exchangeArray($albumData);
		$resultSet = new ResultSet();
		$resultSet->setArrayObjectPrototype(new Album());
		$resultSet->initialize(array($album));
		/** @var Zend\Db\TableGateway\TableGateway $mockTableGateway */
		$mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
			array('select', 'update'),
			array(),
			'',
			false);
        $mockTableGateway->expects($this->once())
		                 ->method('select')
						 ->with(array('id' => 123))
                         ->will($this->returnValue($resultSet));
        $mockTableGateway->expects($this->once())
	                     ->method('update')
						 ->with(array('artist' => 'The Military Wives', 'title' => 'In My Dreams'),
								array('id' => 123));
		$albumTable = new AlbumTable($mockTableGateway);
        $albumTable->saveAlbum($album);
	}

    /**
     * Testing that the exception is thrown
     *
     * @expectedException \Exception
     * @author skocic <skocic@goodgamestudios.com>
     */
    public function testSaveAlbumExceptionWithInvalidGetAlbumResponse() {
   		$albumData = array('id' => 123, 'artist' => 'The Military Wives', 'title' => 'In My Dreams');
   		$album = new Album();
   		$album->exchangeArray($albumData);
   		$resultSet = new ResultSet();
   		$resultSet->setArrayObjectPrototype(new Album());
   		$resultSet->initialize(array($album));
        /** @var Album\Model\AlbumTable $albumTable */
        $albumTable = $this->getMock('Album\Model\AlbumTable',
            array('getAlbum'),
            array(),
            '',
            false);
        $albumTable->expects($this->once())
            ->method('getAlbum')
            ->with($albumData['id'])
            ->will($this->returnValue(false));
        $albumTable->saveAlbum($album);
   	}

	public function testExceptionIsThrownWhenGettingNonexistentAlbum()
	{
		$resultSet = new ResultSet();
		$resultSet->setArrayObjectPrototype(new Album());
		$resultSet->initialize(array());
		/** @var Zend\Db\TableGateway\TableGateway $mockTableGateway */
		$mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
			array('select'),
			array(),
			'',
			false);
		$mockTableGateway->expects($this->once())
						 ->method('select')
						 ->with(array('id' => 123))
						 ->will($this->returnValue($resultSet));
		$albumTable = new AlbumTable($mockTableGateway);
		try
		{
			$albumTable->getAlbum(123);
		}
		catch (\Exception $e)
		{
			$this->assertSame('Could not find row 123', $e->getMessage());
			return;
		}
		$this->fail('Expected exception was not thrown');
	}
}
