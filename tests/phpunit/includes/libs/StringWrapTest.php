<?php

class StringWrapTest extends PHPUnit_Framework_TestCase {

	public static function provideConsolidate() {
		return array(
			array(
				'Merge consecutive wraps with the same before/after values',
				array(
					new StringWrap( '<foo>var x = [];', 'x.push( 0 );', '</foo>' ),
					new StringWrap( '<foo>var x = [];', 'x.push( 1 );', '</foo>' ),
					new StringWrap( '<foo>var x = [];', 'x.push( 2 );', '</foo>' ),
					new StringWrap( '<foo>', 'var x = [];x.push( 3 );', '</foo>' ),
				),
				'<foo>var x = [];x.push( 0 );x.push( 1 );x.push( 2 );</foo>' . "\n"
				. '<foo>var x = [];x.push( 3 );</foo>',
			),
			array(
				'Merge consecutive wraps with empty string prefixes',
				array(
					new StringWrap( '<foo>var x = [];', 'x.push( 0 );', '</foo>' ),
					new StringWrap( '', '<foo special=a></foo>' ),
					new StringWrap( '', '<foo special=b></foo>' ),
					new StringWrap( '<foo special=c></foo>' ),
					new StringWrap( '<foo>var x = [];', 'x.push( 1 );', '</foo>' ),
				),
				'<foo>var x = [];x.push( 0 );</foo>' . "\n"
				. '<foo special=a></foo><foo special=b></foo>' . "\n"
				. '<foo special=c></foo>' . "\n"
				. '<foo>var x = [];x.push( 1 );</foo>',
			),
			array(
				'No merges unless consecutive',
				array(
					new StringWrap( '<foo>var x = [];', 'x.push( 0 );', '</foo>' ),
					new StringWrap( '', '<foo special=a></foo>' ),
					new StringWrap( '<foo>var x = [];', 'x.push( 1 );', '</foo>' ),
					new StringWrap( '', '<foo special=b></foo>' ),
					new StringWrap( '<foo>var x = [];', 'x.push( 2 );', '</foo>' ),
				),
				'<foo>var x = [];x.push( 0 );</foo>' . "\n"
				. '<foo special=a></foo>' . "\n"
				. '<foo>var x = [];x.push( 1 );</foo>' . "\n"
				. '<foo special=b></foo>' . "\n"
				. '<foo>var x = [];x.push( 2 );</foo>',
			),
		);
	}

	/**
	 * @covers StringWrap
	 * @dataProvider provideConsolidate
	 */
	public function testConsolidate( $msg, $wraps, $expected ) {
		$this->assertEquals(
			$expected,
			StringWrap::join( "\n", $wraps ),
			$msg
		);
	}
}
