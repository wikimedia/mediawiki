<?php

class WrappedStringTest extends PHPUnit_Framework_TestCase {

	public static function provideCompact() {
		return array(
			array(
				'Merge consecutive wraps with the same before/after values',
				array(
					new WrappedString( '<foo>var q = q || [];', 'q.push( 0 );', '</foo>' ),
					new WrappedString( '<foo>var q = q || [];', 'q.push( 1 );', '</foo>' ),
					new WrappedString( '<foo>var q = q || [];', 'q.push( 2 );', '</foo>' ),
					new WrappedString( '<foo>', 'var q = q || [];q.push( 3 );', '</foo>' ),
				),
				'<foo>var q = q || [];q.push( 0 );q.push( 1 );q.push( 2 );</foo>' . "\n" .
				'<foo>var q = q || [];q.push( 3 );</foo>',
			),
			array(
				'Merge consecutive wraps with empty string prefiqes',
				array(
					new WrappedString( '<foo>var q = q || [];', 'q.push( 0 );', '</foo>' ),
					new WrappedString( '', '<foo special=a></foo>' ),
					new WrappedString( '', '<foo special=b></foo>' ),
					new WrappedString( '<foo special=c></foo>' ),
					new WrappedString( '<foo>var q = q || [];', 'q.push( 1 );', '</foo>' ),
				),
				'<foo>var q = q || [];q.push( 0 );</foo>' . "\n" .
				'<foo special=a></foo><foo special=b></foo>' . "\n" .
				'<foo special=c></foo>' . "\n" .
				'<foo>var q = q || [];q.push( 1 );</foo>',
			),
			array(
				'No merges unless consecutive',
				array(
					new WrappedString( '<foo>var q = q || [];', 'q.push( 0 );', '</foo>' ),
					new WrappedString( '', '<foo special=a></foo>' ),
					new WrappedString( '<foo>var q = q || [];', 'q.push( 1 );', '</foo>' ),
					new WrappedString( '', '<foo special=b></foo>' ),
					new WrappedString( '<foo>var q = q || [];', 'q.push( 2 );', '</foo>' ),
				),
				'<foo>var q = q || [];q.push( 0 );</foo>' . "\n" .
				'<foo special=a></foo>' . "\n" .
				'<foo>var q = q || [];q.push( 1 );</foo>' . "\n" .
				'<foo special=b></foo>' . "\n" .
				'<foo>var q = q || [];q.push( 2 );</foo>',
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
