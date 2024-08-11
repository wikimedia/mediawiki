<?php

namespace MediaWiki\Tests\Parser;

use MediaWiki\Language\Language;
use MediaWiki\Parser\MagicWord;
use MediaWiki\Parser\MagicWordArray;
use MediaWiki\Parser\MagicWordFactory;
use MediaWikiIntegrationTestCase;
use UnexpectedValueException;

/**
 * @covers \MediaWiki\Parser\MagicWordFactory
 * @covers \MediaWiki\Parser\MagicWord
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

		$this->expectException( UnexpectedValueException::class );
		@$magicWordFactory->get( 'invalid magic word' );
	}

	public function testGetVariableIDs() {
		$magicWordFactory = $this->makeMagicWordFactory();
		$varIds = $magicWordFactory->getVariableIDs();

		$this->assertIsArray( $varIds );
		$this->assertNotEmpty( $varIds );
		$this->assertContainsOnly( 'string', $varIds );
	}

	public function testGetSubstArray() {
		$magicWordFactory = $this->makeMagicWordFactory();
		$substArray = $magicWordFactory->getSubstArray();

		$text = 'SafeSubst:x';
		$this->assertSame( 'safesubst', $substArray->matchStartAndRemove( $text ) );
		$this->assertSame( 'x', $text );
	}

	public function testGetDoubleUnderscoreArray() {
		$magicWordFactory = $this->makeMagicWordFactory();
		$actual = $magicWordFactory->getDoubleUnderscoreArray();

		$this->assertInstanceOf( MagicWordArray::class, $actual );
	}
}
