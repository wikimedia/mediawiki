<?php

use MediaWiki\Html\FormOptions;

/**
 * Copyright © 2011, Antoine Musso
 *
 * @author Antoine Musso
 * @covers \MediaWiki\Html\FormOptions
 */
class FormOptionsTest extends MediaWikiUnitTestCase {

	/**
	 * @dataProvider provideTypeDetection
	 */
	public function testGuessTypeDetection( $expectedType, $data ) {
		$this->assertEquals(
			$expectedType,
			FormOptions::guessType( $data )
		);
	}

	public static function provideTypeDetection() {
		yield [ FormOptions::BOOL, true ];
		yield [ FormOptions::BOOL, false ];
		yield [ FormOptions::INT, 0 ];
		yield [ FormOptions::INT, -5 ];
		yield [ FormOptions::INT, 5 ];
		yield [ FormOptions::INT, 0x0F ];
		yield [ FormOptions::FLOAT, 0.0 ];
		yield [ FormOptions::FLOAT, 1.5 ];
		yield [ FormOptions::FLOAT, 1e3 ];
		yield [ FormOptions::STRING, 'true' ];
		yield [ FormOptions::STRING, 'false' ];
		yield [ FormOptions::STRING, '5' ];
		yield [ FormOptions::STRING, '0' ];
		yield [ FormOptions::STRING, '1.5' ];
		yield [ FormOptions::ARR, [ 'foo' ] ];
	}

	public function testGuessTypeOnNullThrowException() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Unsupported datatype' );
		FormOptions::guessType( null );
	}
}
