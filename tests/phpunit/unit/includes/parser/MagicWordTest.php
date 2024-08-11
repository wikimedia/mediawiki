<?php

namespace MediaWiki\Tests\Parser;

use MediaWiki\Language\Language;
use MediaWiki\Parser\MagicWord;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Parser\MagicWord
 */
class MagicWordTest extends MediaWikiUnitTestCase {

	public function testAllFeatures() {
		$lang = $this->createNoOpMock( Language::class );
		$mw = new MagicWord( 'ID', [ 'SYN', 'SYN2' ], false, $lang );

		$this->assertSame( [ 'SYN', 'SYN2' ], $mw->getSynonyms() );
		$this->assertSame( 'SYN', $mw->getSynonym( 0 ) );
		$this->assertFalse( $mw->isCaseSensitive() );

		$this->assertSame( '/SYN2|SYN/iu', $mw->getRegex() );
		$this->assertSame( 'iu', $mw->getRegexCase() );
		$this->assertSame( '/^(?:SYN2|SYN)/iu', $mw->getRegexStart() );
		$this->assertSame( '/^(?:SYN2|SYN)$/iu', $mw->getRegexStartToEnd() );
		$this->assertSame( 'SYN2|SYN', $mw->getBaseRegex() );

		$this->assertFalse( $mw->match( 'ID' ), 'identifier is not automatically valid syntax' );
		$this->assertTrue( $mw->match( '…SYN…' ) );
		$this->assertFalse( $mw->matchStartToEnd( 'ID' ) );
		$this->assertFalse( $mw->matchStartToEnd( 'SYN…' ) );
		$this->assertTrue( $mw->matchStartToEnd( 'SYN' ) );

		$text = 'ID';
		$this->assertFalse( $mw->matchAndRemove( $text ) );
		$text = '…SYN…';
		$this->assertTrue( $mw->matchAndRemove( $text ) );
		$this->assertSame( '……', $text );

		$text = '…SYN';
		$this->assertFalse( $mw->matchStartAndRemove( $text ) );
		$text = 'SYN…';
		$this->assertTrue( $mw->matchStartAndRemove( $text ) );
		$this->assertSame( '…', $text );

		$this->assertSame( '…NEW…', $mw->replace( 'NEW', '…SYN…' ) );
	}

}
