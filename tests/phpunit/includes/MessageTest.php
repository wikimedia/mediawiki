<?php

class MessageTest extends MediaWikiLangTestCase {

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
		$this->assertEquals( '<i-dont-exist-evar>', wfMessage( 'i-dont-exist-evar' )->plain() );
		$this->assertEquals( '&lt;i-dont-exist-evar&gt;', wfMessage( 'i-dont-exist-evar' )->escaped() );
	}

	function testInLanguage() {
		$this->assertEquals( 'Main Page', wfMessage( 'mainpage' )->inLanguage( 'en' )->text() );
		$this->assertEquals( 'Заглавная страница', wfMessage( 'mainpage' )->inLanguage( 'ru' )->text() );
		$this->assertEquals( 'Main Page', wfMessage( 'mainpage' )->inLanguage( Language::factory( 'en' ) )->text() );
		$this->assertEquals( 'Заглавная страница', wfMessage( 'mainpage' )->inLanguage( Language::factory( 'ru' ) )->text() );
	}

	function testMessageParams() {
		$this->assertEquals( 'Return to $1.', wfMessage( 'returnto' )->text() );
		$this->assertEquals( 'Return to $1.', wfMessage( 'returnto', array() )->text() );
		$this->assertEquals( 'You have foo (bar).', wfMessage( 'youhavenewmessages', 'foo', 'bar' )->text() );
		$this->assertEquals( 'You have foo (bar).', wfMessage( 'youhavenewmessages', array( 'foo', 'bar' ) )->text() );
	}

	function testMessageParamSubstitution() {
		$this->assertEquals( '(Заглавная страница)', wfMessage( 'parentheses', 'Заглавная страница' )->plain() );
		$this->assertEquals( '(Заглавная страница $1)', wfMessage( 'parentheses', 'Заглавная страница $1' )->plain() );
		$this->assertEquals( '(Заглавная страница)', wfMessage( 'parentheses' )->rawParams( 'Заглавная страница' )->plain() );
		$this->assertEquals( '(Заглавная страница $1)', wfMessage( 'parentheses' )->rawParams( 'Заглавная страница $1' )->plain() );
	}

	function testInContentLanguage() {
		global $wgLang, $wgForceUIMsgAsContentMsg;
		$oldLang = $wgLang;
		$wgLang = Language::factory( 'fr' );

		$this->assertEquals( 'Main Page', wfMessage( 'mainpage' )->inContentLanguage()->plain(), 'ForceUIMsg disabled' );
		$wgForceUIMsgAsContentMsg['testInContentLanguage'] = 'mainpage';
		$this->assertEquals( 'Accueil', wfMessage( 'mainpage' )->inContentLanguage()->plain(), 'ForceUIMsg enabled' );

		/* Restore globals */
		$wgLang = $oldLang;
		unset( $wgForceUIMsgAsContentMsg['testInContentLanguage'] );
	}

	/**
	 * @expectedException MWException
	 */
	function testInLanguageThrows() {
		wfMessage( 'foo' )->inLanguage( 123 );
	}
}
