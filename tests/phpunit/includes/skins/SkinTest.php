<?php

class SkinTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers Skin::getDefaultModules
	 */
	public function testGetDefaultModules() {
		$skin = $this->getMockBuilder( Skin::class )
			->setMethods( [ 'outputPage' ] )
			->getMock();

		$modules = $skin->getDefaultModules();
		$this->assertTrue( isset( $modules['core'] ), 'core key is set by default' );
		$this->assertTrue( isset( $modules['styles'] ), 'style key is set by default' );
	}

	/**
	 * @covers Skin::isResponsive
	 *
	 * @dataProvider provideSkinResponsiveOptions
	 *
	 * @param array $options
	 * @param bool $expected
	 */
	public function testIsResponsive( array $options, bool $expected ) {
		$skin = new class( $options ) extends Skin {
			/**
			 * @inheritDoc
			 */
			public function outputPage() {
			}
		};

		$this->assertSame( $expected, $skin->isResponsive() );
	}

	public function provideSkinResponsiveOptions() : array {
		return [
			'responsive not set' => [ [], false ],
			'responsive false' => [ [ 'responsive' => false ], false ],
			'responsive true' => [ [ 'responsive' => true ], true ]
		];
	}
}
