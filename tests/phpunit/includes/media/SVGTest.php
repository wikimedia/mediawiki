<?php

class SvgTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		$this->filePath = __DIR__ . '/../../data/media/';

		$this->setMwGlobals( 'wgShowEXIF', true );

		$this->backend = new FSFileBackend( array(
			'name' => 'localtesting',
			'wikiId' => wfWikiId(),
			'containerPaths' => array( 'data' => $this->filePath )
		) );
		$this->repo = new FSRepo( array(
			'name' => 'temp',
			'url' => 'http://localhost/thumbtest',
			'backend' => $this->backend
		) );

		$this->handler = new SvgHandler;
	}

	/**
	 * @param $filename String
	 * @param $expected Array The expected independent metadata
	 * @dataProvider providerGetIndependentMetaArray
	 * @covers SvgHandler::getCommonMetaArray
	 */
	public function testGetIndependentMetaArray( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/svg+xml' );
		$res = $this->handler->getCommonMetaArray( $file );

		$this->assertEquals( $res, $expected );
	}

	public function providerGetIndependentMetaArray() {
		return array(
			array( 'Tux.svg', array(
				'ObjectName' => 'Tux',
				'ImageDescription' => 'For more information see: http://commons.wikimedia.org/wiki/Image:Tux.svg',
			) ),
			array( 'Wikimedia-logo.svg', array() )
		);
	}

	private function dataFile( $name, $type ) {
		return new UnregisteredLocalFile( false, $this->repo,
			"mwstore://localtesting/data/$name", $type );
	}
}
