<?php

namespace MediaWiki\Tests\Parser;

use MediaWiki\Language\Language;
use MediaWiki\Parser\MagicWord;
use MediaWiki\Parser\MagicWordArray;
use MediaWiki\Parser\MagicWordFactory;
use MediaWikiUnitTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Parser\MagicWordArray
 * @covers \MediaWiki\Parser\MagicWord
 */
class MagicWordArrayTest extends MediaWikiUnitTestCase {

	private const MAGIC_WORDS = [
		'notitleconvert' => [ 0, '__NOTITLECONVERT__', '__NOTC__' ],
		'notoc' => [ 0, '__NOTOC__' ],
		'img_thumbnail' => [ 1, 'thumb', 'thumbnail' ],
		'img_upright' => [ 1, 'upright', 'upright=$1', 'upright $1' ],
		'img_width' => [ 1, '$1px' ],
	];

	public function testConstructor() {
		$array = new MagicWordArray( [ 'ID' ], $this->getFactory() );
		$this->assertSame( [ 'ID' ], $array->getNames() );
		$this->assertSame( [ '(?i:(?P<a_ID>SYNONYM)|(?P<b_ID>alt\=\$1))', '(?!)' ],
			$array->getBaseRegex() );
		$this->assertSame( [ '(?i:SYNONYM|alt\=\$1)', '(?!)' ],
			$array->getBaseRegex( false ) );
		$this->assertSame( 'ID', $array->matchStartToEnd( 'SyNoNyM' ) );
	}

	public function testAdd() {
		$array = new MagicWordArray( [], $this->getFactory() );
		$array->add( 'ADD' );
		$this->assertSame( [ 'ADD' ], $array->getNames() );
		$this->assertSame( [ '(?i:(?P<a_ADD>SYNONYM)|(?P<b_ADD>alt\=\$1))', '(?!)' ],
			$array->getBaseRegex() );
	}

	/**
	 * @dataProvider provideMatchStartToEndCaseSensitive
	 */
	public function testMatchStartToEndCaseSensitive( string $input, $expected ) {
		$array = new MagicWordArray( [ 'ID' ], $this->getFactory( true ) );
		$this->assertSame( $expected, $array->matchStartToEnd( $input ) );
	}

	public static function provideMatchStartToEndCaseSensitive() {
		return [
			'identifier is not automatically valid syntax' => [ 'ID', false ],
			'mismatch' => [ 'unknown', false ],
			'incorrect capitalization' => [ 'synonym', false ],
			'exact match' => [ 'SYNONYM', 'ID' ],
		];
	}

	/**
	 * @dataProvider provideMatchVariableStartToEnd
	 */
	public function testMatchVariableStartToEnd(
		string $input,
		array $expected = [ false, false ]
	) {
		$array = new MagicWordArray( [ 'ID' ], $this->getFactory() );
		$this->assertSame( $expected, $array->matchVariableStartToEnd( $input ) );
	}

	public static function provideMatchVariableStartToEnd() {
		return [
			'identifier is not automatically valid syntax' => [ 'ID' ],
			'match' => [ 'SyNoNyM', [ 'ID', false ] ],
			'end does not match' => [ 'SyNoNyMx' ],
			'with empty parameter' => [ 'alt=', [ 'ID', '' ] ],
			'with parameter' => [ 'alt=Description', [ 'ID', 'Description' ] ],
			'whitespace is not ignored' => [ 'alt =' ],
		];
	}

	/**
	 * @dataProvider provideMatchVariableStartToEndMultiple
	 */
	public function testMatchVariableStartToEndMultiple(
		string $input,
		array $expected = [ false, false ]
	) {
		$array = new MagicWordArray( array_keys( self::MAGIC_WORDS ), $this->getFactory() );
		$this->assertSame( $expected, $array->matchVariableStartToEnd( $input ) );

		/** @var MagicWordArray $spy */
		$spy = TestingAccessWrapper::newFromObject( $array );
		$this->assertSame( [
			'/^(?:(?i:(?P<a_notitleconvert>__NOTITLECONVERT__)|(?P<b_notitleconvert>__NOTC__)|' .
				'(?P<a_notoc>__NOTOC__)))$/JSu',
			'/^(?:(?P<a_img_thumbnail>thumb)|(?P<b_img_thumbnail>thumbnail)|' .
				'(?P<a_img_upright>upright)|(?P<b_img_upright>upright\=(.*?))|(?P<c_img_upright>upright (.*?))|' .
				'(?P<a_img_width>(.*?)px))$/JS',
		], $spy->getVariableStartToEndRegex() );
	}

	public static function provideMatchVariableStartToEndMultiple() {
		return [
			[ 'thumb', [ 'img_thumbnail', false ] ],
			[ 'upright=1.2', [ 'img_upright', '1.2' ] ],
			[ 'upright=', [ 'img_upright', '' ] ],
			[ 'upright', [ 'img_upright', false ] ],
			[ '100px', [ 'img_width', '100' ] ],
			[ 'px', [ 'img_width', '' ] ],
			[ 'PX' ],
		];
	}

	/**
	 * @dataProvider provideMatchStartAndRemove
	 */
	public function testMatchStartAndRemove(
		string $input,
		$expectedMatches,
		?string $expectedText = null
	) {
		$array = new MagicWordArray( [ 'ID' ], $this->getFactory() );
		$text = $input;
		$this->assertSame( $expectedMatches, $array->matchStartAndRemove( $text ) );
		$this->assertSame( $expectedText ?? $input, $text );
	}

	public static function provideMatchStartAndRemove() {
		return [
			'identifier is not automatically valid syntax' => [ 'ID', false ],
			'match' => [ 'SyNoNyMx', 'ID', 'x' ],
			'not at the start' => [ 'xSyNoNyMx', false ],
			'this method does not support parameters' => [ 'alt=x', false ],
			'unexpected behavior when used with parameters' => [ 'alt=$1x', 'ID', 'x' ],
		];
	}

	/**
	 * @dataProvider provideMatchAndRemove
	 */
	public function testMatchAndRemove(
		string $input,
		array $expectedMatches = [],
		?string $expectedText = null
	) {
		$array = new MagicWordArray( [ 'ID' ], $this->getFactory() );
		$text = $input;
		$this->assertSame( $expectedMatches, $array->matchAndRemove( $text ) );
		$this->assertSame( $expectedText ?? $input, $text );
	}

	public static function provideMatchAndRemove() {
		return [
			'identifier is not automatically valid syntax' => [ 'ID' ],
			'two matches' => [ 'xSyNoNyMxSyNoNyMx', [ 'ID' => false ], 'xxx' ],
			'this method does not support parameters' => [ 'xalt=x' ],
			'unexpected behavior when used with parameters' => [ 'xalt=$1x', [ 'ID' => false ], 'xx' ],
			'T321234' => [ "\x83", [] ],
		];
	}

	/**
	 * @dataProvider provideMatchAndRemoveMultiple
	 */
	public function testMatchAndRemoveMultiple(
		string $input,
		array $expectedMatches = [],
		?string $expectedText = null
	) {
		$array = new MagicWordArray( array_keys( self::MAGIC_WORDS ), $this->getFactory() );
		$text = $input;
		$this->assertSame( $expectedMatches, $array->matchAndRemove( $text ) );
		$this->assertSame( $expectedText ?? $input, $text );
	}

	public static function provideMatchAndRemoveMultiple() {
		return [
			[
				'x__NOTC__x__NOTOC__x__NOTITLECONVERT__x',
				[ 'notitleconvert' => false, 'notoc' => false ],
				'xxxx',
			],
			[
				'__NOTOC__NOTITLECONVERT__NOTOC____NOTOC__',
				[ 'notoc' => false ],
				'NOTITLECONVERT',
			],
			[
				'[[File:X.png|thumb|Alt]]',
				[ 'img_thumbnail' => false ],
				'[[File:X.png||Alt]]',
			],
			[
				'[[File:X.png|thumb|100px]]',
				[ 'img_thumbnail' => false ],
				'[[File:X.png||100px]]',
			],
			[
				'[[File:X.png|upright=1.2|thumbnail]]',
				[ 'img_upright' => false, 'img_thumbnail' => false ],
				// Note: The matchAndRemove method is obviously not designed for this, still we want
				// to document the status quo
				'[[File:X.png|=1.2|nail]]',
			],
		];
	}

	public function testRegexWithDuplicateGroupNames() {
		$array = new MagicWordArray( [ 'ID', 'ID' ], $this->getFactory() );
		$text = 'SYNONYM';
		$this->assertSame( [ 'ID', false ], $array->matchVariableStartToEnd( $text ) );
		$this->assertSame( 'ID', $array->matchStartAndRemove( $text ) );
		$text = 'SYNONYM';
		$this->assertSame( [ 'ID' => false ], $array->matchAndRemove( $text ) );
	}

	private function getFactory( ?bool $caseSensitive = null ): MagicWordFactory {
		$language = $this->createNoOpMock( Language::class, [ 'lc' ] );
		$language->method( 'lc' )->willReturnCallback( static fn ( $s ) => strtolower( $s ) );

		$factory = $this->createNoOpMock( MagicWordFactory::class, [ 'getContentLanguage', 'get' ] );
		$factory->method( 'getContentLanguage' )->willReturn( $language );
		$factory->method( 'get' )->willReturnCallback(
			static function ( $id ) use ( $caseSensitive, $language ) {
				$data = self::MAGIC_WORDS[$id] ?? [ 0, 'SYNONYM', 'alt=$1' ];
				$caseSensitive ??= (bool)$data[0];
				return new MagicWord( $id, array_slice( $data, 1 ), $caseSensitive, $language );
			}
		);
		return $factory;
	}

}
