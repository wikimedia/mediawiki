<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Shell\Command;
use MediaWiki\Shell\Shell;

/**
 * @covers \MediaWiki\Shell\Shell
 * @group Shell
 */
class ShellTest extends MediaWikiIntegrationTestCase {

	public function testIsDisabled() {
		$this->assertIsBool( Shell::isDisabled() );
	}

	/**
	 * @dataProvider provideEscape
	 */
	public function testEscape( $args, $expected ) {
		if ( wfIsWindows() ) {
			$this->markTestSkipped( 'This test requires a POSIX environment.' );
		}
		$this->assertSame( $expected, Shell::escape( ...$args ) );
	}

	public static function provideEscape() {
		return [
			'simple' => [ [ 'true' ], "'true'" ],
			'with args' => [ [ 'convert', '-font', 'font name' ], "'convert' '-font' 'font name'" ],
			'array' => [ [ [ 'convert', '-font', 'font name' ] ], "'convert' '-font' 'font name'" ],
			'skip nulls' => [ [ 'ls', null ], "'ls'" ],
		];
	}

	/**
	 * @covers \MediaWiki\Shell\Shell::makeScriptCommand
	 * @dataProvider provideMakeScriptCommand
	 *
	 * @param string $expected expected in POSIX
	 * @param string $expectedWin expected in Windows
	 * @param string $script
	 * @param string[] $parameters
	 * @param string[] $options
	 * @param callable|null $hook
	 */
	public function testMakeScriptCommand(
		$expected,
		$expectedWin,
		$script,
		$parameters,
		$options = [],
		$hook = null
	) {
		// Running tests under Vagrant involves MWMultiVersion that uses the below hook
		$this->overrideConfigValue( MainConfigNames::Hooks, [] );

		if ( $hook ) {
			$this->setTemporaryHook( 'wfShellWikiCmd', $hook );
		}

		$command = Shell::makeScriptCommand( $script, $parameters, $options );
		$command->params( 'safe' )
			->unsafeParams( 'unsafe' );

		$this->assertInstanceOf( Command::class, $command );

		if ( wfIsWindows() ) {
			$this->assertEquals( $expectedWin, $command->getCommandString() );
		} else {
			$this->assertEquals( $expected, $command->getCommandString() );
		}
		$this->assertSame( [], $command->getDisallowedPaths() );
	}

	public static function provideMakeScriptCommand() {
		global $wgPhpCli;

		$IP = MW_INSTALL_PATH;

		return [
			'no option' => [
				"'$wgPhpCli' '$IP/maintenance/run.php' '$IP/maintenance/foobar.php' 'bar'\\''\"baz' 'safe' unsafe",
				"\"$wgPhpCli\" \"$IP/maintenance/run.php\" \"$IP/maintenance/foobar.php\" \"bar'\\\"baz\" \"safe\" unsafe",
				"$IP/maintenance/foobar.php",
				[ 'bar\'"baz' ],
			],
			'hook' => [
				"'$wgPhpCli' '$IP/maintenance/run.php' 'changed.php' '--wiki=somewiki' 'bar'\\''\"baz' 'safe' unsafe",
				"\"$wgPhpCli\" \"$IP/maintenance/run.php\" \"changed.php\" \"--wiki=somewiki\" \"bar'\\\"baz\" \"safe\" unsafe",
				'maintenance/foobar.php',
				[ 'bar\'"baz' ],
				[],
				static function ( &$script, array &$parameters ) {
					$script = 'changed.php';
					array_unshift( $parameters, '--wiki=somewiki' );
				}
			],
			'php option' => [
				"'/bin/perl' '$IP/maintenance/run.php' 'maintenance/foobar.php'  'safe' unsafe",
				"\"/bin/perl\" \"$IP/maintenance/run.php\" \"maintenance/foobar.php\"  \"safe\" unsafe",
				"maintenance/foobar.php",
				[],
				[ 'php' => '/bin/perl' ],
			],
			'wrapper option' => [
				"'$wgPhpCli' 'foobinize' 'maintenance/foobar.php'  'safe' unsafe",
				"\"$wgPhpCli\" \"foobinize\" \"maintenance/foobar.php\"  \"safe\" unsafe",
				"maintenance/foobar.php",
				[],
				[ 'wrapper' => 'foobinize' ],
			],
		];
	}

}
