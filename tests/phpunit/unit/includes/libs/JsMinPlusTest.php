<?php

/**
 * @covers JsMinPlus
 */
class JsMinPlusTest extends PHPUnit\Framework\TestCase {

	public static function provideValidInputs() {
		// If an implementation matches inputs using a regex with runaway backtracking,
		// then inputs with more than ~3072 repetitions are likely to fail (T299537).

		$input = '"' . str_repeat( 'x', 10000 ) . '";';
		yield 'double quote string 10K' => [ $input ];

		$input = '\'' . str_repeat( 'x', 10000 ) . '\';';
		yield 'single quote string 10K' => [ $input ];

		$input = '"' . str_repeat( '\u0021', 100 ) . '";';
		yield 'escaping string 100' => [ $input ];

		$input = '"' . str_repeat( '\u0021', 10000 ) . '";';
		yield 'escaping string 10K' => [ $input ];

		$input = '/' . str_repeat( 'x', 1000 ) . '/;';
		yield 'regex 1K' => [ $input ];

		$input = '/' . str_repeat( 'x', 10000 ) . '/;';
		yield 'regex 10K' => [ $input ];

		$input = '/' . str_repeat( '\u0021', 100 ) . '/;';
		yield 'escaping regex 100' => [ $input ];

		$input = '/' . str_repeat( '\u0021', 10000 ) . '/;';
		yield 'escaping regex 10K' => [ $input ];
	}

	/**
	 * @dataProvider provideValidInputs
	 * @doesNotPerformAssertions
	 */
	public function testLongStrings( string $input ) {
		// Ensure no parse error thrown
		JSMinPlus::minify( $input );
	}
}
