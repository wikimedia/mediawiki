<?php

use MediaWiki\Parser\MagicWord;
use MediaWiki\Parser\MagicWordArray;
use MediaWiki\Parser\MagicWordFactory;

/**
 * @covers \MediaWiki\Parser\MagicWordFactory
 *
 * @author Derick N. Alangi
 */
class MagicWordFactoryTest extends MediaWikiIntegrationTestCase {
	private function makeMagicWordFactory( Language $contLang = null ) {
		$services = $this->getServiceContainer();
		return new MagicWordFactory( $contLang ?:
			$services->getLanguageFactory()->getLanguage( 'en' ),
			$services->getHookContainer()
		);
	}

	public function testGetContentLanguage() {
		$contLang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );

		$magicWordFactory = $this->makeMagicWordFactory( $contLang );
		$magicWordContLang = $magicWordFactory->getContentLanguage();

		$this->assertSame( $contLang, $magicWordContLang );
	}

	public function testGetMagicWord() {
		$magicWordIdValid = 'pageid';
		$magicWordFactory = $this->makeMagicWordFactory();
		$mwActual = $magicWordFactory->get( $magicWordIdValid );
		$contLang = $magicWordFactory->getContentLanguage();
		$expected = new MagicWord( $magicWordIdValid, [ 'PAGEID' ], false, $contLang );

		$this->assertEquals( $expected, $mwActual );
	}

	public function testGetInvalidMagicWord() {
		$magicWordFactory = $this->makeMagicWordFactory();

		$this->expectException( MWException::class );
		@$magicWordFactory->get( 'invalid magic word' );
	}

	public function testGetVariableIDs() {
		$magicWordFactory = $this->makeMagicWordFactory();
		$varIds = $magicWordFactory->getVariableIDs();

		$this->assertIsArray( $varIds );
		$this->assertNotEmpty( $varIds );
		$this->assertContainsOnly( 'string', $varIds );
	}

	public function testGetSubstIDs() {
		$magicWordFactory = $this->makeMagicWordFactory();
		$substIds = $magicWordFactory->getSubstIDs();

		$this->assertIsArray( $substIds );
		$this->assertNotEmpty( $substIds );
		$this->assertContainsOnly( 'string', $substIds );
	}

	/**
	 * Test both valid and invalid caching hints paths
	 */
	public function testGetCacheTTL() {
		$magicWordFactory = $this->makeMagicWordFactory();
		$actual = $magicWordFactory->getCacheTTL( 'localday' );

		$this->assertSame( 3600, $actual );

		$actual = $magicWordFactory->getCacheTTL( 'currentmonth' );
		$this->assertSame( 86400, $actual );

		$actual = $magicWordFactory->getCacheTTL( 'invalid' );
		$this->assertSame( -1, $actual );
	}

	public function testGetDoubleUnderscoreArray() {
		$magicWordFactory = $this->makeMagicWordFactory();
		$actual = $magicWordFactory->getDoubleUnderscoreArray();

		$this->assertInstanceOf( MagicWordArray::class, $actual );
	}
}
