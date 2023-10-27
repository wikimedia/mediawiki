<?php

/**
 * @covers \MagicWordArray
 */
class MagicWordArrayTest extends MediaWikiUnitTestCase {

	public function testConstructor() {
		$array = new MagicWordArray( [ 'ID' ], $this->getFactory() );
		$this->assertSame( [ 'ID' ], $array->getNames() );
		$this->assertSame( [ '(?i:(?P<a_ID>SYNONYM)|(?P<b_ID>alt\=\$1))', '(?!)' ],
			$array->getBaseRegex() );
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

	public function provideMatchStartToEndCaseSensitive() {
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

	public function provideMatchVariableStartToEnd() {
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
	 * @dataProvider provideMatchStartAndRemove
	 */
	public function testMatchStartAndRemove(
		string $input,
		$expectedMatches,
		string $expectedText = null
	) {
		$array = new MagicWordArray( [ 'ID' ], $this->getFactory() );
		$text = $input;
		$this->assertSame( $expectedMatches, $array->matchStartAndRemove( $text ) );
		$this->assertSame( $expectedText ?? $input, $text );
	}

	public function provideMatchStartAndRemove() {
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
		string $expectedText = null
	) {
		$array = new MagicWordArray( [ 'ID' ], $this->getFactory() );
		$text = $input;
		$this->assertSame( $expectedMatches, $array->matchAndRemove( $text ) );
		$this->assertSame( $expectedText ?? $input, $text );
	}

	public function provideMatchAndRemove() {
		return [
			'identifier is not automatically valid syntax' => [ 'ID' ],
			'two matches' => [ 'xSyNoNyMxSyNoNyMx', [ 'ID' => false ], 'xxx' ],
			'this method does not support parameters' => [ 'xalt=x' ],
			'unexpected behavior when used with parameters' => [ 'xalt=$1x', [ 'ID' => false ], 'xx' ],
			'T321234' => [ "\x83", [] ],
		];
	}

	private function getFactory( bool $caseSensitive = false ): MagicWordFactory {
		$language = $this->createNoOpMock( Language::class, [ 'lc', '__debugInfo' ] );
		$language->method( 'lc' )->willReturnCallback( static fn ( $s ) => strtolower( $s ) );

		$factory = $this->createNoOpMock( MagicWordFactory::class, [ 'getContentLanguage', 'get' ] );
		$factory->method( 'getContentLanguage' )->willReturn( $language );
		$factory->method( 'get' )->willReturnCallback(
			static fn ( $id ) => new MagicWord( $id, [ 'SYNONYM', 'alt=$1' ], $caseSensitive, $language )
		);
		return $factory;
	}

}
