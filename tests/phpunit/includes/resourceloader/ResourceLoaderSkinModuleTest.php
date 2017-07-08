<?php

/**
 * @group Database
 * @group ResourceLoader
 */
class ResourceLoaderSkinModuleTest extends PHPUnit_Framework_TestCase {

	public static function provideGetStyles() {
		return [
			[
				'parent' => [],
				'expected' => [
					'all' => [ '.mw-wiki-logo { background-image: url(/logo.png); }' ],
				],
			],
			[
				'parent' => [
					'screen' => '.example {}',
				],
				'expected' => [
					'screen' => [ '.example {}' ],
					'all' => [ '.mw-wiki-logo { background-image: url(/logo.png); }' ],
				],
			],
		];
	}

	/**
	 * @dataProvider provideGetStyles
	 * @covers ResourceLoaderSkinModule::normalizeStyles
	 * @covers ResourceLoaderSkinModule::getStyles
	 */
	public function testGetStyles( $parent, $expected ) {
		$module = $this->getMockBuilder( ResourceLoaderSkinModule::class )
			->disableOriginalConstructor()
			->setMethods( [ 'readStyleFiles' ] )
			->getMock();
		$module->expects( $this->once() )->method( 'readStyleFiles' )
			->willReturn( $parent );
		$module->setConfig( new HashConfig( [
			'ResourceBasePath' => '/w',
			'Logo' => '/logo.png',
			'LogoHD' => false,
		] ) );

		$ctx = $this->getMockBuilder( ResourceLoaderContext::class )
			->disableOriginalConstructor()->getMock();

		$this->assertEquals(
			$module->getStyles( $ctx ),
			$expected
		);
	}

	/**
	 * @covers ResourceLoaderSkinModule::isKnownEmpty
	 */
	public function testIsKnownEmpty() {
		$module = $this->getMockBuilder( ResourceLoaderSkinModule::class )
			->disableOriginalConstructor()->setMethods( null )->getMock();
		$ctx = $this->getMockBuilder( ResourceLoaderContext::class )
			->disableOriginalConstructor()->getMock();

		$this->assertFalse( $module->isKnownEmpty( $ctx ) );
	}
}
