<?php

use MediaWiki\MainConfigNames;
use Psr\Log\NullLogger;

/**
 * @group GlobalFunctions
 * @covers ::wfThumbIsStandard
 */
class WfThumbIsStandardTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::ThumbLimits => [
				100,
				401
			],
			MainConfigNames::ImageLimits => [
				[ 300, 225 ],
				[ 800, 600 ],
			],
		] );
	}

	public static function provideThumbParams() {
		return [
			// Thumb limits
			[
				'Standard thumb width',
				true,
				[ 'width' => 100 ],
			],
			[
				'Standard thumb width',
				true,
				[ 'width' => 401 ],
			],
			// wfThumbIsStandard should match Linker::processResponsiveImages
			// in its rounding behaviour.
			[
				'Standard thumb width (HiDPI 1.5x) - incorrect rounding',
				false,
				[ 'width' => 601 ],
			],
			[
				'Standard thumb width (HiDPI 1.5x)',
				true,
				[ 'width' => 602 ],
			],
			[
				'Standard thumb width (HiDPI 2x)',
				true,
				[ 'width' => 802 ],
			],
			[
				'Non-standard thumb width',
				false,
				[ 'width' => 300 ],
			],
			// Image limits
			// Note: Image limits are measured as pairs. Individual values
			// may be non-standard based on the aspect ratio.
			[
				'Standard image width/height pair',
				true,
				[ 'width' => 250, 'height' => 225 ],
			],
			[
				'Standard image width/height pair',
				true,
				[ 'width' => 667, 'height' => 600 ],
			],
			[
				'Standard image width where image does not fit aspect ratio',
				false,
				[ 'width' => 300 ],
			],
			[
				'Implicit width from image width/height pair aspect ratio fit',
				true,
				// 2000x1800 fit inside 300x225 makes w=250
				[ 'width' => 250 ],
			],
			[
				'Height-only is always non-standard',
				false,
				[ 'height' => 225 ],
			],
		];
	}

	/**
	 * @dataProvider provideThumbParams
	 */
	public function testIsStandard( $message, $expected, $params ) {
		$handlers = $this->getServiceContainer()->getMainConfig()->get( 'ParserTestMediaHandlers' );
		$this->setService(
			'MediaHandlerFactory',
			new MediaHandlerFactory( new NullLogger(), $handlers )
		);
		$this->assertSame(
			$expected,
			wfThumbIsStandard( new FakeDimensionFile( [ 2000, 1800 ], 'image/jpeg' ), $params ),
			$message
		);
	}
}
