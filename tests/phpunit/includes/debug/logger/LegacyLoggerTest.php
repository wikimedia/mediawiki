<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Logger;

use MediaWikiTestCase;
use Psr\Log\LogLevel;

class LegacyLoggerTest extends MediaWikiTestCase {

	/**
	 * @covers LegacyLogger::interpolate
	 * @dataProvider provideInterpolate
	 */
	public function testInterpolate( $message, $context, $expect ) {
		$this->assertEquals(
			$expect, LegacyLogger::interpolate( $message, $context ) );
	}

	public function provideInterpolate() {
		$e = new \Exception( 'boom!' );
		$d = new \DateTime();
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
		];
	}

	/**
	 * @covers LegacyLogger::shouldEmit
	 * @dataProvider provideShouldEmit
	 */
	public function testShouldEmit( $level, $config, $expected ) {
		$this->setMwGlobals( 'wgDebugLogGroups', [ 'fakechannel' => $config ] );
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
		];

		if ( class_exists( '\Monolog\Logger' ) ) {
			$tests[] = [
				\Monolog\Logger::INFO,
				$dest + [ 'level' => LogLevel::INFO ],
				true,
			];
			$tests[] = [
				\Monolog\Logger::WARNING,
				$dest + [ 'level' => LogLevel::EMERGENCY ],
				false,
			];
		}

		return $tests;
	}

}
