<?php

use MediaWiki\Shell\Shell;

/**
 * @group Shell
 */
class ShellTest extends PHPUnit_Framework_TestCase {
	public function testIsDisabled() {
		$this->assertInternalType( 'bool', Shell::isDisabled() ); // sanity
	}

	/**
	 * @dataProvider provideEscape
	 */
	public function testEscape( $args, $expected ) {
		if ( wfIsWindows() ) {
			$this->markTestSkipped( 'This test requires a POSIX environment.' );
		}
		$this->assertSame( $expected, call_user_func_array( [ Shell::class, 'escape' ], $args ) );
	}

	public function provideEscape() {
		return [
			'simple' => [ [ 'true' ], "'true'" ],
			'with args' => [ [ 'convert', '-font', 'font name' ], "'convert' '-font' 'font name'" ],
			'array' => [ [ [ 'convert', '-font', 'font name' ] ], "'convert' '-font' 'font name'" ],
			'skip nulls' => [ [ 'ls', null ], "'ls'" ],
		];
	}
}
