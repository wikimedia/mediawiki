<?php

/**
 * @group Media
 */
class SvgTest extends MediaWikiMediaTestCase {

	protected function setUp() {
		parent::setUp();

		$this->filePath = __DIR__ . '/../../data/media/';

		$this->setMwGlobals( 'wgShowEXIF', true );

		$this->handler = new SvgHandler;
	}

	/**
	 * @param string $filename
	 * @param array $expected The expected independent metadata
	 * @dataProvider providerGetIndependentMetaArray
	 * @covers SvgHandler::getCommonMetaArray
	 */
	public function testGetIndependentMetaArray( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/svg+xml' );
		$res = $this->handler->getCommonMetaArray( $file );

		$this->assertEquals( $res, $expected );
	}

	public static function providerGetIndependentMetaArray() {
		return array(
			array( 'Tux.svg', array(
				'ObjectName' => 'Tux',
				'ImageDescription' =>
					'For more information see: http://commons.wikimedia.org/wiki/Image:Tux.svg',
			) ),
			array( 'Wikimedia-logo.svg', array() )
		);
	}
}
