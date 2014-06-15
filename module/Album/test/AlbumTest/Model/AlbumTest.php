<?php

namespace AlbumTest\Model;

use Album\Model\Album;
use PHPUnit_Framework_TestCase;
use Zend\InputFilter\InputFilter;

class AlbumTest extends PHPUnit_Framework_TestCase
{
	public function testAlbumInitialState()
	{
		$album = new Album();
		$this->assertNull($album->artist, '"artist" should initially be null');
		$this->assertNull($album->id, '"id" should initially be null');
		$this->assertNull($album->title, '"title" should initially be null');
	}

	public function testExchangeArraySetsPropertiesCorrectly()
	{
		$album = new Album();
		$data = array(
			'artist' => 'some artist',
			'id'     => 123,
			'title'  => 'some title');
		$album->exchangeArray($data);
	    $this->assertSame($data['artist'], $album->artist, '"artist" was not set correctly');
	    $this->assertSame($data['id'], $album->id, '"id" was not set correctly');
	    $this->assertSame($data['title'], $album->title, '"title" was not set correctly');
	}

	public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
	{
		$album = new Album();
		$album->exchangeArray(array(
				'artist' => 'some artist',
				'id' => 123,
				'title' => 'some title'));
		$album->exchangeArray(array());
		$this->assertNull($album->artist, '"artist" should have defaulted to null');
		$this->assertNull($album->id, '"id" should have defaulted to null');
		$this->assertNull($album->title, '"title" should have defaulted to null');
	}

    /**
     * Test setInputFilter throws Exception
     *
     * @expectedException \Exception
     * @author skocic <skocic@goodgamestudios.com>
     */
    public function testSetInputFilterThrowsException()
    {
        $album = new Album();
        $inputFilter = new InputFilter();
        $album->setInputFilter($inputFilter);
    }

    /**
     * Test getInputFilter
     *
     * @author skocic <skocic@goodgamestudios.com>
     */
    public function testGetInputFilter()
    {
        $album = new Album();
        $inputFilter = new InputFilter();
        $result = $album->getInputFilter($inputFilter);
        $this->assertTrue($result instanceof InputFilter);
        $this->assertSame(3, count($result));
        $this->assertSame(array('id', 'artist', 'title'), array_keys($result->getInputs()));
    }

}
