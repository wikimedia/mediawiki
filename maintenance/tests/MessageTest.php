<?php

class MessageTest extends PHPUnit_Framework_TestCase {

	function setUp() {
		global $wgContLanguageCode;
		
		$wgContLanguageCode = 'en'; # For mainpage to be 'Main Page'
	}

	function testExists() {
		$this->assertTrue( Message::key( 'mainpage' )->exists() );
		$this->assertTrue( Message::key( 'mainpage' )->params( array() )->exists() );
		$this->assertTrue( Message::key( 'mainpage' )->rawParams( 'foo', 123 )->exists() );
		$this->assertFalse( Message::key( 'i-dont-exist-evar' )->exists() );
		$this->assertFalse( Message::key( 'i-dont-exist-evar' )->params( array() )->exists() );
		$this->assertFalse( Message::key( 'i-dont-exist-evar' )->rawParams( 'foo', 123 )->exists() );
	}

	function testKey() {
		$this->assertType( 'Message', Message::key( 'mainpage' ) );
		$this->assertType( 'Message', Message::key( 'i-dont-exist-evar' ) );
		$this->assertEquals( 'Main Page', Message::key( 'mainpage' )->text() );
		$this->assertEquals( '&lt;i-dont-exist-evar&gt;', Message::key( 'i-dont-exist-evar' )->text() );
	}

	function testInLanguage() {
		$this->assertEquals( 'Main Page', Message::key( 'mainpage' )->inLanguage( 'en' )->text() );
		$this->assertEquals( 'Заглавная страница', Message::key( 'mainpage' )->inLanguage( 'ru' )->text() );
		$this->assertEquals( 'Main Page', Message::key( 'mainpage' )->inLanguage( Language::factory( 'en' ) )->text() );
		$this->assertEquals( 'Заглавная страница', Message::key( 'mainpage' )->inLanguage( Language::factory( 'ru' ) )->text() );
	}

	/**
	 * @expectedException MWException
	 */
	function testInLanguageThrows() {
		Message::key( 'foo' )->inLanguage( 123 );
	}
}
