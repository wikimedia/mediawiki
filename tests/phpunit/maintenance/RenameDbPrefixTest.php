<?php

namespace MediaWiki\Tests\Maintenance;

use RenameDbPrefix;

/**
 * @covers \RenameDbPrefix
 * @author Dreamy Jazz
 */
class RenameDbPrefixTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return RenameDbPrefix::class;
	}

	/** @dataProvider provideExecuteForFatalError */
	public function testExecuteForFatalError( $options, $expectedOutputRegex ) {
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->expectCallToFatalError();
		$this->expectOutputRegex( $expectedOutputRegex );
		$this->maintenance->execute();
	}

	public static function provideExecuteForFatalError() {
		return [
			'--old is invalid' => [ [ 'old' => '$£"!abc**;' ], '/Invalid prefix/' ],
			'--new is invalid' => [ [ 'new' => '$£"!abc**;' ], '/Invalid prefix/' ],
			'--new and --old are invalid' => [ [ 'new' => '$£"!abc**;', 'old' => '!"£$' ], '/Invalid prefix/' ],
		];
	}

	/** @dataProvider provideExecuteForOldAndNewTheSame */
	public function testExecuteWhenOldAndNewTheSame( $prefix ) {
		$this->maintenance->setOption( 'old', $prefix );
		$this->maintenance->setOption( 'new', $prefix );
		$this->expectOutputString( "Same prefix. Nothing to rename!\n" );
		$this->maintenance->execute();
	}

	public static function provideExecuteForOldAndNewTheSame() {
		return [
			'--old and --new are "abc"' => [ 'abc_', 'abc_' ],
			'--old and --new are empty' => [ null, null ],
		];
	}
}
