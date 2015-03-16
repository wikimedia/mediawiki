<?php

class WrappedStringTest extends PHPUnit_Framework_TestCase {

	public static function provideCompact() {
		return array(
			array(
				'Merge consecutive wraps with the same before/after values',
				array(
					new WrappedString( '<foo>var x = [];', 'x.push( 0 );', '</foo>' ),
					new WrappedString( '<foo>var x = [];', 'x.push( 1 );', '</foo>' ),
					new WrappedString( '<foo>var x = [];', 'x.push( 2 );', '</foo>' ),
					new WrappedString( '<foo>', 'var x = [];x.push( 3 );', '</foo>' ),
				),
				'<foo>var x = [];x.push( 0 );x.push( 1 );x.push( 2 );</foo>' . "\n"
				. '<foo>var x = [];x.push( 3 );</foo>',
			),
			array(
				'Merge consecutive wraps with empty string prefixes',
				array(
					new WrappedString( '<foo>var x = [];', 'x.push( 0 );', '</foo>' ),
					new WrappedString( '', '<foo special=a></foo>' ),
					new WrappedString( '', '<foo special=b></foo>' ),
					new WrappedString( '<foo special=c></foo>' ),
					new WrappedString( '<foo>var x = [];', 'x.push( 1 );', '</foo>' ),
				),
				'<foo>var x = [];x.push( 0 );</foo>' . "\n"
				. '<foo special=a></foo><foo special=b></foo>' . "\n"
				. '<foo special=c></foo>' . "\n"
				. '<foo>var x = [];x.push( 1 );</foo>',
			),
			array(
				'No merges unless consecutive',
				array(
					new WrappedString( '<foo>var x = [];', 'x.push( 0 );', '</foo>' ),
					new WrappedString( '', '<foo special=a></foo>' ),
					new WrappedString( '<foo>var x = [];', 'x.push( 1 );', '</foo>' ),
					new WrappedString( '', '<foo special=b></foo>' ),
					new WrappedString( '<foo>var x = [];', 'x.push( 2 );', '</foo>' ),
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
	 * @covers WrappedString
	 * @dataProvider provideCompact
	 */
	public function testCompact( $msg, $wraps, $expected ) {
		$this->assertEquals(
			$expected,
			WrappedString::join( "\n", $wraps ),
			$msg
		);
	}
}
