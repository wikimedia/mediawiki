<?php

class BitmapScalingTest extends MediaWikiTestCase {
	/**
	 * @dataProvider provideNormaliseParams
	 */
	function testNormaliseParams( $fileDimensions, $expectedParams, $params, $msg ) {
		$file = new FakeDimensionFile( $fileDimensions );
		$handler = new BitmapHandler;
		$handler->normaliseParams( $file, $params );
		$this->assertEquals( $expectedParams, $params, $msg );
	}
	
	function provideNormaliseParams() {
		return array(
			/* Regular resize operations */	
			array(
				array( 1024, 768 ),
				array( 
					'width' => 512, 'height' => 384, 
					'physicalWidth' => 512, 'physicalHeight' => 384,
					'page' => 1,
				),
				array( 'width' => 512 ),
				'Resizing with width set',
			),
			array(
				array( 1024, 768 ),
				array( 
					'width' => 512, 'height' => 384, 
					'physicalWidth' => 512, 'physicalHeight' => 384,
					'page' => 1, 
				),
				array( 'width' => 512, 'height' => 768 ),
				'Resizing with height set too high',
			),
			array(
				array( 1024, 768 ),
				array( 
					'width' => 512, 'height' => 384, 
					'physicalWidth' => 512, 'physicalHeight' => 384,
					'page' => 1, 
				),
				array( 'width' => 1024, 'height' => 384 ),
				'Resizing with height set',
			),
			
			/* Very tall images */
			array(
				array( 1000, 100 ),
				array( 
					'width' => 5, 'height' => 1,
					'physicalWidth' => 5, 'physicalHeight' => 1,
					'page' => 1, 
				),
				array( 'width' => 5 ),
				'Very wide image',
			),
			
			array(
				array( 100, 1000 ),
				array( 
					'width' => 1, 'height' => 10,
					'physicalWidth' => 1, 'physicalHeight' => 10,
					'page' => 1, 
				),
				array( 'width' => 1 ),
				'Very high image',
			),
			array(
				array( 100, 1000 ),
				array( 
					'width' => 1, 'height' => 5,
					'physicalWidth' => 1, 'physicalHeight' => 10,
					'page' => 1, 
				),
				array( 'width' => 10, 'height' => 5 ),
				'Very high image with height set',
			),
		);
	} 
}

class FakeDimensionFile extends File {
	public function __construct( $dimensions ) {
		parent::__construct( Title::makeTitle( NS_FILE, 'Test' ), null );
		
		$this->dimensions = $dimensions;
	}
	public function getWidth( $page = 1 ) {
		return $this->dimensions[0];
	}
	public function getHeight( $page = 1 ) {
		return $this->dimensions[1];
	}
}