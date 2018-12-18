<?php

/**
 * @covers \MagicWordFactory
 *
 * @author Derick N. Alangi
 */
class MagicWordFactoryTest extends MediaWikiTestCase {
	/**
	 * Make magic word factory
	 */
	private function makeMagicWordFactory( $contLang ) {
		if ( $contLang === null ) {
			$mwf = new MagicWordFactory( Language::factory( 'en' ) );
			return $mwf;
		}
		return new MagicWordFactory( $contLang );
	}

	public function testGetContentLanguage() {
		$contLang = Language::factory( 'en' );

		$magicWordFactory = $this->makeMagicWordFactory( $contLang );
		$mwfActual = $magicWordFactory->getContentLanguage();

		$this->assertSame( $contLang, $mwfActual );
	}

	public function testGetMagicWord() {
		$magicWordIdValid = 'pageid';
		$magicWordFactory = $this->makeMagicWordFactory( null );
		$mwActual = $magicWordFactory->get( $magicWordIdValid );
		$contLang = $magicWordFactory->getContentLanguage();
		$expected = new MagicWord( $magicWordIdValid, [ 'PAGEID' ], false, $contLang );

		$this->assertEquals( $expected, $mwActual );
	}

	public function testGetInvalidMagicWord() {
		$magicWordFactory = $this->makeMagicWordFactory( null );

		$this->setExpectedException( MWException::class );
		\Wikimedia\suppressWarnings();
		try {
			$magicWordFactory->get( 'invalid magic word' );
		} finally {
			\Wikimedia\restoreWarnings();
		}
	}

	public function testGetVariableIDs() {
		$magicWordFactory = $this->makeMagicWordFactory( null );
		$varIds = $magicWordFactory->getVariableIDs();

		$this->assertContainsOnly( 'string', $varIds );
		$this->assertNotEmpty( $varIds );
		$this->assertInternalType( 'array', $varIds );
	}

	public function testGetSubstIDs() {
		$magicWordFactory = $this->makeMagicWordFactory( null );
		$substIds = $magicWordFactory->getSubstIDs();

		$this->assertContainsOnly( 'string', $substIds );
		$this->assertNotEmpty( $substIds );
		$this->assertInternalType( 'array', $substIds );
	}

	/**
	 * Test both valid and invalid caching hints paths
	 */
	public function testGetCacheTTL() {
		$magicWordFactory = $this->makeMagicWordFactory( null );
		$actual = $magicWordFactory->getCacheTTL( 'localday' );

		$this->assertSame( 3600, $actual );

		$actual = $magicWordFactory->getCacheTTL( 'currentmonth' );
		$this->assertSame( 86400, $actual );

		$actual = $magicWordFactory->getCacheTTL( 'invalid' );
		$this->assertSame( -1, $actual );
	}

	public function testGetDoubleUnderscoreArray() {
		$magicWordFactory = $this->makeMagicWordFactory( null );
		$actual = $magicWordFactory->getDoubleUnderscoreArray();

		$this->assertInstanceOf( MagicWordArray::class, $actual );
	}
}
