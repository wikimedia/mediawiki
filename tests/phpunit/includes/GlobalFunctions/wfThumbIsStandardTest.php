<?php

/**
 * @group GlobalFunctions
 * @covers ::wfThumbIsStandard
 */
class WfThumbIsStandardTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( array(
			'wgThumbLimits' => array(
				100,
				401
			),
			'wgImageLimits' => array(
				array( 300, 225 ),
				array( 800, 600 ),
			),
			'wgMediaHandlers' => array(
				'unknown/unknown' => 'MockBitmapHandler',
			),
		) );
	}

	public static function provideThumbParams() {
		return array(
			// Thumb limits
			array(
				'Standard thumb width',
				true,
				array( 'width' => 100 ),
			),
			array(
				'Standard thumb width',
				true,
				array( 'width' => 401 ),
			),
			// wfThumbIsStandard should match Linker::processResponsiveImages
			// in its rounding behaviour.
			array(
				'Standard thumb width (HiDPI 1.5x) - incorrect rounding',
				false,
				array( 'width' => 601 ),
			),
			array(
				'Standard thumb width (HiDPI 1.5x)',
				true,
				array( 'width' => 602 ),
			),
			array(
				'Standard thumb width (HiDPI 2x)',
				true,
				array( 'width' => 802 ),
			),
			array(
				'Non-standard thumb width',
				false,
				array( 'width' => 300 ),
			),
			// Image limits
			// Note: Image limits are measured as pairs. Individual values
			// may be non-standard based on the aspect ratio.
			array(
				'Standard image width/height pair',
				true,
				array( 'width' => 250, 'height' => 225 ),
			),
			array(
				'Standard image width/height pair',
				true,
				array( 'width' => 667, 'height' => 600 ),
			),
			array(
				'Standard image width where image does not fit aspect ratio',
				false,
				array( 'width' => 300 ),
			),
			array(
				'Implicit width from image width/height pair aspect ratio fit',
				true,
				// 2000x1800 fit inside 300x225 makes w=250
				array( 'width' => 250 ),
			),
			array(
				'Height-only is always non-standard',
				false,
				array( 'height' => 225 ),
			),
		);
	}

	/**
	 * @dataProvider provideThumbParams
	 */
	public function testIsStandard( $message, $expected, $params ) {
		$this->assertSame(
			$expected,
			wfThumbIsStandard( new FakeDimensionFile( array( 2000, 1800 ) ), $params ),
			$message
		);
	}
}
