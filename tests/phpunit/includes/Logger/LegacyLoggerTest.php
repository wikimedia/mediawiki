<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Logger;

use MediaWiki\Logger\LegacyLogger;
use MediaWiki\MainConfigNames;
use MediaWikiIntegrationTestCase;
use Psr\Log\LogLevel;

/**
 * @covers \MediaWiki\Logger\LegacyLogger
 */
class LegacyLoggerTest extends MediaWikiIntegrationTestCase {

	/**
	 * @dataProvider provideInterpolate
	 */
	public function testInterpolate( $message, $context, $expect ) {
		$this->assertEquals(
			$expect, LegacyLogger::interpolate( $message, $context ) );
	}

	public static function provideInterpolate() {
		$e = new \Exception( 'boom!' );
		$d = new \DateTime();
		$err = new \Error( 'Test error' );
		return [
			[
				'no-op',
				[],
				'no-op',
			],
			[
				'Hello {world}!',
				[
					'world' => 'World',
				],
				'Hello World!',
			],
			[
				'{greeting} {user}',
				[
					'greeting' => 'Goodnight',
					'user' => 'Moon',
				],
				'Goodnight Moon',
			],
			[
				'Oops {key_not_set}',
				[],
				'Oops {key_not_set}',
			],
			[
				'{ not interpolated }',
				[
					'not interpolated' => 'This should NOT show up in the message',
				],
				'{ not interpolated }',
			],
			[
				'{null}',
				[
					'null' => null,
				],
				'[Null]',
			],
			[
				'{bool}',
				[
					'bool' => true,
				],
				'true',
			],
			[
				'{float}',
				[
					'float' => 1.23,
				],
				'1.23',
			],
			[
				'{array}',
				[
					'array' => [ 1, 2, 3 ],
				],
				'[Array(3)]',
			],
			[
				'{exception}',
				[
					'exception' => $e,
				],
				'[Exception ' . get_class( $e ) . '( ' .
				$e->getFile() . ':' . $e->getLine() . ') ' .
				$e->getMessage() . ']',
			],
			[
				'{datetime}',
				[
					'datetime' => $d,
				],
				$d->format( 'c' ),
			],
			[
				'{object}',
				[
					'object' => new \stdClass,
				],
				'[Object stdClass]',
			],
			[
				'{exception}',
				[
					'exception' => $err,
				],
				'[Error ' . get_class( $err ) . '( ' .
					$err->getFile() . ':' . $err->getLine() . ') ' .
					$err->getMessage() . ']',
			],
		];
	}

	/**
	 * @dataProvider provideShouldEmit
	 */
	public function testShouldEmit( $level, $config, $expected ) {
		$this->overrideConfigValue( MainConfigNames::DebugLogGroups, [ 'fakechannel' => $config ] );
		$this->assertEquals(
			$expected,
			LegacyLogger::shouldEmit( 'fakechannel', 'some message', $level, [] )
		);
	}

	public static function provideShouldEmit() {
		$dest = [ 'destination' => 'foobar' ];
		$tests = [
			[
				LogLevel::DEBUG,
				$dest,
				true
			],
			[
				LogLevel::WARNING,
				$dest + [ 'level' => LogLevel::INFO ],
				true,
			],
			[
				LogLevel::INFO,
				$dest + [ 'level' => LogLevel::CRITICAL ],
				false,
			],
			[
				\Monolog\Logger::INFO,
				$dest + [ 'level' => LogLevel::INFO ],
				true,
			],
			[
				\Monolog\Logger::WARNING,
				$dest + [ 'level' => LogLevel::EMERGENCY ],
				false,
			]
		];

		return $tests;
	}

}
