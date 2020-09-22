<?php

use MediaWiki\Shell\Command;
use MediaWiki\Shell\Shell;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Shell\Shell
 * @group Shell
 */
class ShellTest extends MediaWikiIntegrationTestCase {

	public function testIsDisabled() {
		$this->assertIsBool( Shell::isDisabled() ); // sanity
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

	public function provideEscape() {
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
	 * @param string $expected    expected in POSIX
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
		$this->setMwGlobals( 'wgHooks', [] );

		if ( $hook ) {
			$this->setTemporaryHook( 'wfShellWikiCmd', $hook );
		}

		$command = Shell::makeScriptCommand( $script, $parameters, $options );
		$command->params( 'safe' )
			->unsafeParams( 'unsafe' );

		$this->assertInstanceOf( Command::class, $command );

		$wrapper = TestingAccessWrapper::newFromObject( $command );

		if ( wfIsWindows() ) {
			$this->assertEquals( $expectedWin, $wrapper->command );
		} else {
			$this->assertEquals( $expected, $wrapper->command );
		}
		$this->assertSame( 0, $wrapper->restrictions & Shell::NO_LOCALSETTINGS );
	}

	public function provideMakeScriptCommand() {
		global $wgPhpCli;

		return [
			[
				"'$wgPhpCli' 'maintenance/foobar.php' 'bar'\\''\"baz' 'safe' unsafe",
				'"' . $wgPhpCli . '" "maintenance/foobar.php" "bar\'\\"baz" "safe" unsafe',
				'maintenance/foobar.php',
				[ 'bar\'"baz' ],
			],
			[
				"'$wgPhpCli' 'changed.php' '--wiki=somewiki' 'bar'\\''\"baz' 'safe' unsafe",
				'"' . $wgPhpCli . '" "changed.php" "--wiki=somewiki" "bar\'\\"baz" "safe" unsafe',
				'maintenance/foobar.php',
				[ 'bar\'"baz' ],
				[],
				function ( &$script, array &$parameters ) {
					$script = 'changed.php';
					array_unshift( $parameters, '--wiki=somewiki' );
				}
			],
			[
				"'/bin/perl' 'maintenance/foobar.php' 'bar'\\''\"baz' 'safe' unsafe",
				'"/bin/perl" "maintenance/foobar.php" "bar\'\\"baz" "safe" unsafe',
				'maintenance/foobar.php',
				[ 'bar\'"baz' ],
				[ 'php' => '/bin/perl' ],
			],
			[
				"'$wgPhpCli' 'foobinize' 'maintenance/foobar.php' 'bar'\\''\"baz' 'safe' unsafe",
				'"' . $wgPhpCli . '" "foobinize" "maintenance/foobar.php" "bar\'\\"baz" "safe" unsafe',
				'maintenance/foobar.php',
				[ 'bar\'"baz' ],
				[ 'wrapper' => 'foobinize' ],
			],
		];
	}
}
