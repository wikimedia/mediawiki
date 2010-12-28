<?php

class MessageTest extends MediaWikiTestCase {

	function setUp() {
		global $wgLanguageCode, $wgLang, $wgContLang, $wgMessageCache;

		$wgLanguageCode = 'en'; # For mainpage to be 'Main Page'
		//Note that a Stub Object is not enough for this test
		$wgContLang = $wgLang = Language::factory( $wgLanguageCode );
		$wgMessageCache = new MessageCache( false, false, 3600 );
	}

	function testExists() {
		$this->assertTrue( wfMessage( 'mainpage' )->exists() );
		$this->assertTrue( wfMessage( 'mainpage' )->params( array() )->exists() );
		$this->assertTrue( wfMessage( 'mainpage' )->rawParams( 'foo', 123 )->exists() );
		$this->assertFalse( wfMessage( 'i-dont-exist-evar' )->exists() );
		$this->assertFalse( wfMessage( 'i-dont-exist-evar' )->params( array() )->exists() );
		$this->assertFalse( wfMessage( 'i-dont-exist-evar' )->rawParams( 'foo', 123 )->exists() );
	}

	function testKey() {
		$this->assertInstanceOf( 'Message', wfMessage( 'mainpage' ) );
		$this->assertInstanceOf( 'Message', wfMessage( 'i-dont-exist-evar' ) );
		$this->assertEquals( 'Main Page', wfMessage( 'mainpage' )->text() );
		$this->assertEquals( '&lt;i-dont-exist-evar&gt;', wfMessage( 'i-dont-exist-evar' )->text() );
	}

	function testInLanguage() {
		$this->assertEquals( 'Main Page', wfMessage( 'mainpage' )->inLanguage( 'en' )->text() );
		$this->assertEquals( 'Заглавная страница', wfMessage( 'mainpage' )->inLanguage( 'ru' )->text() );
		$this->assertEquals( 'Main Page', wfMessage( 'mainpage' )->inLanguage( Language::factory( 'en' ) )->text() );
		$this->assertEquals( 'Заглавная страница', wfMessage( 'mainpage' )->inLanguage( Language::factory( 'ru' ) )->text() );
	}

	function testMessagePararms() {
		$this->assertEquals( 'Return to $1.', wfMessage( 'returnto' )->text() );
		$this->assertEquals( 'Return to $1.', wfMessage( 'returnto', array() )->text() );
		$this->assertEquals( 'You have foo (bar).', wfMessage( 'youhavenewmessages', 'foo', 'bar' )->text() );
		$this->assertEquals( 'You have foo (bar).', wfMessage( 'youhavenewmessages', array( 'foo', 'bar' ) )->text() );
	}

	/**
	 * @expectedException MWException
	 */
	function testInLanguageThrows() {
		wfMessage( 'foo' )->inLanguage( 123 );
	}
}
