<?php

class MessageTest extends MediaWikiLangTestCase {

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( array(
			'wgLang' => Language::factory( 'en' ),
			'wgForceUIMsgAsContentMsg' => array(),
		) );
	}

	/**
	 * @covers Message::__construct
	 * @dataProvider provideConstructor
	 */
	public function testConstructor( $expectedLang, $key, $params, $language ) {
		$reflection = new ReflectionClass( 'Message' );

		$keyProperty = $reflection->getProperty( 'key' );
		$keyProperty->setAccessible( true );

		$paramsProperty = $reflection->getProperty( 'parameters' );
		$paramsProperty->setAccessible( true );

		$langProperty = $reflection->getProperty( 'language' );
		$langProperty->setAccessible( true );

		$message = new Message( $key, $params, $language );

		$this->assertEquals( $key, $keyProperty->getValue( $message ) );
		$this->assertEquals( $params, $paramsProperty->getValue( $message ) );
		$this->assertEquals( $expectedLang, $langProperty->getValue( $message ) );
	}

	public static function provideConstructor() {
		$langDe = Language::factory( 'de' );
		$langEn = Language::factory( 'en' );

		return array(
			array( $langDe, 'foo', array(), $langDe ),
			array( $langDe, 'foo', array( 'bar' ), $langDe ),
			array( $langEn, 'foo', array( 'bar' ), null )
		);
	}

	public static function provideTestParams() {
		return array(
			array( array() ),
			array( array( 'foo' ), 'foo' ),
			array( array( 'foo', 'bar' ), 'foo', 'bar' ),
			array( array( 'baz' ), array( 'baz' ) ),
			array( array( 'baz', 'foo' ), array( 'baz', 'foo' ) ),
			array( array( 'baz', 'foo' ), array( 'baz', 'foo' ), 'hhh' ),
			array( array( 'baz', 'foo' ), array( 'baz', 'foo' ), 'hhh', array( 'ahahahahha' ) ),
			array( array( 'baz', 'foo' ), array( 'baz', 'foo' ), array( 'ahahahahha' ) ),
			array( array( 'baz' ), array( 'baz' ), array( 'ahahahahha' ) ),
		);
	}

	public function getLanguageProvider() {
		return array(
			array( 'foo', array( 'bar' ), 'en' ),
			array( 'foo', array( 'bar' ), 'de' )
		);
	}

	/**
	 * @covers Message::getLanguage
	 * @dataProvider getLanguageProvider
	 */
	public function testGetLanguageCode( $key, $params, $languageCode ) {
		$language = Language::factory( $languageCode );
		$message = new Message( $key, $params, $language );

		$this->assertEquals( $language, $message->getLanguage() );
	}

	/**
	 * @covers Message::params
	 * @dataProvider provideTestParams
	 */
	public function testParams( $expected ) {
		$msg = new Message( 'imasomething' );

		$returned = call_user_func_array( array( $msg, 'params' ), array_slice( func_get_args(), 1 ) );

		$this->assertSame( $msg, $returned );
		$this->assertEquals( $expected, $msg->getParams() );
	}

	/**
	 * @covers Message::exists
	 */
	public function testExists() {
		$this->assertTrue( wfMessage( 'mainpage' )->exists() );
		$this->assertTrue( wfMessage( 'mainpage' )->params( array() )->exists() );
		$this->assertTrue( wfMessage( 'mainpage' )->rawParams( 'foo', 123 )->exists() );
		$this->assertFalse( wfMessage( 'i-dont-exist-evar' )->exists() );
		$this->assertFalse( wfMessage( 'i-dont-exist-evar' )->params( array() )->exists() );
		$this->assertFalse( wfMessage( 'i-dont-exist-evar' )->rawParams( 'foo', 123 )->exists() );
	}

	/**
	 * @covers Message::__construct
	 */
	public function testKey() {
		$this->assertInstanceOf( 'Message', wfMessage( 'mainpage' ) );
		$this->assertInstanceOf( 'Message', wfMessage( 'i-dont-exist-evar' ) );
		$this->assertEquals( 'Main Page', wfMessage( 'mainpage' )->text() );
		$this->assertEquals( '&lt;i-dont-exist-evar&gt;', wfMessage( 'i-dont-exist-evar' )->text() );
		$this->assertEquals( '<i-dont-exist-evar>', wfMessage( 'i-dont-exist-evar' )->plain() );
		$this->assertEquals( '&lt;i-dont-exist-evar&gt;', wfMessage( 'i-dont-exist-evar' )->escaped() );
	}

	/**
	 * @covers Message::inLanguage
	 */
	public function testInLanguage() {
		$this->assertEquals( 'Main Page', wfMessage( 'mainpage' )->inLanguage( 'en' )->text() );
		$this->assertEquals( 'Заглавная страница',
			wfMessage( 'mainpage' )->inLanguage( 'ru' )->text() );

		// NOTE: make sure internal caching of the message text is reset appropriately
		$msg = wfMessage( 'mainpage' );
		$this->assertEquals( 'Main Page', $msg->inLanguage( Language::factory( 'en' ) )->text() );
		$this->assertEquals(
			'Заглавная страница',
			$msg->inLanguage( Language::factory( 'ru' ) )->text()
		);
	}

	/**
	 * @covers Message::__construct
	 */
	public function testMessageParams() {
		$this->assertEquals( 'Return to $1.', wfMessage( 'returnto' )->text() );
		$this->assertEquals( 'Return to $1.', wfMessage( 'returnto', array() )->text() );
		$this->assertEquals(
			'You have foo (bar).',
			wfMessage( 'youhavenewmessages', 'foo', 'bar' )->text()
		);
		$this->assertEquals(
			'You have foo (bar).',
			wfMessage( 'youhavenewmessages', array( 'foo', 'bar' ) )->text()
		);
	}

	/**
	 * @covers Message::__construct
	 * @covers Message::rawParams
	 */
	public function testMessageParamSubstitution() {
		$this->assertEquals(
			'(Заглавная страница)',
			wfMessage( 'parentheses', 'Заглавная страница' )->plain()
		);
		$this->assertEquals(
			'(Заглавная страница $1)',
			wfMessage( 'parentheses', 'Заглавная страница $1' )->plain()
		);
		$this->assertEquals(
			'(Заглавная страница)',
			wfMessage( 'parentheses' )->rawParams( 'Заглавная страница' )->plain()
		);
		$this->assertEquals(
			'(Заглавная страница $1)',
			wfMessage( 'parentheses' )->rawParams( 'Заглавная страница $1' )->plain()
		);
	}

	/**
	 * @covers Message::__construct
	 * @covers Message::params
	 */
	public function testDeliciouslyManyParams() {
		$msg = new RawMessage( '$1$2$3$4$5$6$7$8$9$10$11$12' );
		// One less than above has placeholders
		$params = array( 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k' );
		$this->assertEquals(
			'abcdefghijka2',
			$msg->params( $params )->plain(),
			'Params > 9 are replaced correctly'
		);
	}

	/**
	 * @covers Message::numParams
	 */
	public function testMessageNumParams() {
		$lang = Language::factory( 'en' );
		$msg = new RawMessage( '$1' );

		$this->assertEquals(
			$lang->formatNum( 123456.789 ),
			$msg->inLanguage( $lang )->numParams( 123456.789 )->plain(),
			'numParams is handled correctly'
		);
	}

	/**
	 * @covers Message::durationParams
	 */
	public function testMessageDurationParams() {
		$lang = Language::factory( 'en' );
		$msg = new RawMessage( '$1' );

		$this->assertEquals(
			$lang->formatDuration( 1234 ),
			$msg->inLanguage( $lang )->durationParams( 1234 )->plain(),
			'durationParams is handled correctly'
		);
	}

	/**
	 * FIXME: This should not need database, but Language#formatExpiry does (bug 55912)
	 * @group Database
	 * @covers Message::expiryParams
	 */
	public function testMessageExpiryParams() {
		$lang = Language::factory( 'en' );
		$msg = new RawMessage( '$1' );

		$this->assertEquals(
			$lang->formatExpiry( wfTimestampNow() ),
			$msg->inLanguage( $lang )->expiryParams( wfTimestampNow() )->plain(),
			'expiryParams is handled correctly'
		);
	}

	/**
	 * @covers Message::timeperiodParams
	 */
	public function testMessageTimeperiodParams() {
		$lang = Language::factory( 'en' );
		$msg = new RawMessage( '$1' );

		$this->assertEquals(
			$lang->formatTimePeriod( 1234 ),
			$msg->inLanguage( $lang )->timeperiodParams( 1234 )->plain(),
			'timeperiodParams is handled correctly'
		);
	}

	/**
	 * @covers Message::sizeParams
	 */
	public function testMessageSizeParams() {
		$lang = Language::factory( 'en' );
		$msg = new RawMessage( '$1' );

		$this->assertEquals(
			$lang->formatSize( 123456 ),
			$msg->inLanguage( $lang )->sizeParams( 123456 )->plain(),
			'sizeParams is handled correctly'
		);
	}

	/**
	 * @covers Message::bitrateParams
	 */
	public function testMessageBitrateParams() {
		$lang = Language::factory( 'en' );
		$msg = new RawMessage( '$1' );

		$this->assertEquals(
			$lang->formatBitrate( 123456 ),
			$msg->inLanguage( $lang )->bitrateParams( 123456 )->plain(),
			'bitrateParams is handled correctly'
		);
	}

	/**
	 * @covers Message::inContentLanguage
	 */
	public function testInContentLanguage() {
		$this->setMwGlobals( 'wgLang', Language::factory( 'fr' ) );

		// NOTE: make sure internal caching of the message text is reset appropriately
		$msg = wfMessage( 'mainpage' );
		$this->assertEquals( 'Hauptseite', $msg->inLanguage( 'de' )->plain(), "inLanguage( 'de' )" );
		$this->assertEquals( 'Main Page', $msg->inContentLanguage()->plain(), "inContentLanguage()" );
		$this->assertEquals( 'Accueil', $msg->inLanguage( 'fr' )->plain(), "inLanguage( 'fr' )" );
	}

	/**
	 * @covers Message::inContentLanguage
	 */
	public function testInContentLanguageOverride() {
		$this->setMwGlobals( array(
			'wgLang' => Language::factory( 'fr' ),
			'wgForceUIMsgAsContentMsg' => array( 'mainpage' ),
		) );

		// NOTE: make sure internal caching of the message text is reset appropriately.
		// NOTE: wgForceUIMsgAsContentMsg forces the messages *current* language to be used.
		$msg = wfMessage( 'mainpage' );
		$this->assertEquals(
			'Accueil',
			$msg->inContentLanguage()->plain(),
			'inContentLanguage() with ForceUIMsg override enabled'
		);
		$this->assertEquals( 'Main Page', $msg->inLanguage( 'en' )->plain(), "inLanguage( 'en' )" );
		$this->assertEquals(
			'Main Page',
			$msg->inContentLanguage()->plain(),
			'inContentLanguage() with ForceUIMsg override enabled'
		);
		$this->assertEquals( 'Hauptseite', $msg->inLanguage( 'de' )->plain(), "inLanguage( 'de' )" );
	}

	/**
	 * @expectedException MWException
	 * @covers Message::inLanguage
	 */
	public function testInLanguageThrows() {
		wfMessage( 'foo' )->inLanguage( 123 );
	}

	public function keyProvider() {
		return array(
			'string' => array(
				'key' => 'mainpage',
				'expected' => array( 'mainpage' ),
			),
			'single' => array(
				'key' => array( 'mainpage' ),
				'expected' => array( 'mainpage' ),
			),
			'multi' => array(
				'key' => array( 'mainpage-foo', 'mainpage-bar', 'mainpage' ),
				'expected' => array( 'mainpage-foo', 'mainpage-bar', 'mainpage' ),
			),
			'empty' => array(
				'key' => array(),
				'expected' => null,
				'exception' => 'InvalidArgumentException',
			),
			'null' => array(
				'key' => null,
				'expected' => null,
				'exception' => 'InvalidArgumentException',
			),
			'bad type' => array(
				'key' => 17,
				'expected' => null,
				'exception' => 'InvalidArgumentException',
			),
		);
	}

	/**
	 * @dataProvider keyProvider()
	 *
	 * @covers Message::getKey
	 */
	public function testGetKey( $key, $expected, $exception = null ) {
		if ( $exception ) {
			$this->setExpectedException( $exception );
		}

		$msg = new Message( $key );
		$this->assertEquals( $expected, $msg->getKeysToTry() );
		$this->assertEquals( count( $expected ) > 1, $msg->isMultiKey() );
		$this->assertContains( $msg->getKey(), $expected );
	}
}
