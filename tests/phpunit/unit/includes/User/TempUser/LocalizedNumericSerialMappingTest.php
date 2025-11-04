<?php

namespace MediaWiki\Tests\User\TempUser;

use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\User\TempUser\LocalizedNumericSerialMapping;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\User\TempUser\LocalizedNumericSerialMapping
 */
class LocalizedNumericSerialMappingTest extends MediaWikiUnitTestCase {

	protected Language $language;
	protected array $config;

	protected function setUp(): void {
		$this->language = $this->createMock( Language::class );
		$this->config = [ 'language' => 'ar' ];
	}

	public function testConstruct() {
		$languageFactory = $this->createMock( LanguageFactory::class );
		$languageFactory->expects( $this->once() )
			->method( 'getLanguage' )
			->with( 'ar' )
			->willReturn( $this->language );

		$map = new LocalizedNumericSerialMapping( $this->config, $languageFactory );
		$this->assertInstanceOf( LocalizedNumericSerialMapping::class, $map );
	}

	/**
	 * Provide data for testGetSerialIdForIndex
	 */
	public static function provideGetSerialIdForIndex(): array {
		return [
			[
				[ 'language' => 'ar' ],
				10,
				'١٠',
			],
			[
				[ 'language' => 'ar' ],
				100,
				'١٠٠',
			],
			[
				[ 'language' => 'ar' ],
				1000,
				'١٠٠٠',
			],
		];
	}

	/**
	 * @dataProvider provideGetSerialIdForIndex
	 *
	 * @param array $config
	 * @param int $id
	 * @param string $expected
	 */
	public function testGetSerialIdForIndex( array $config, int $id, string $expected ) {
		$languageFactory = $this->createMock( LanguageFactory::class );
		$languageFactory->expects( $this->once() )
			->method( 'getLanguage' )
			->with( 'ar' )
			->willReturn( $this->language );

		$map = new LocalizedNumericSerialMapping( $config, $languageFactory );
		$this->language->expects( $this->once() )
			->method( 'formatNum' )
			->with( $id )
			->willReturn( $expected );

		$this->assertSame( $expected, $map->getSerialIdForIndex( $id ) );
	}
}
