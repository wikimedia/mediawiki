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
		return array(
			array(
				'no-op',
				array(),
				'no-op',
			),
			array(
				'Hello {world}!',
				array(
					'world' => 'World',
				),
				'Hello World!',
			),
			array(
				'{greeting} {user}',
				array(
					'greeting' => 'Goodnight',
					'user' => 'Moon',
				),
				'Goodnight Moon',
			),
			array(
				'Oops {key_not_set}',
				array(),
				'Oops {key_not_set}',
			),
			array(
				'{ not interpolated }',
				array(
					'not interpolated' => 'This should NOT show up in the message',
				),
				'{ not interpolated }',
			),
		);
	}

	/**
	 * @covers LegacyLogger::shouldEmit
	 * @dataProvider provideShouldEmit
	 */
	public function testShouldEmit( $level, $config, $expected ) {
		$this->setMwGlobals( 'wgDebugLogGroups', array( 'fakechannel' => $config ) );
		$this->assertEquals(
			$expected,
			LegacyLogger::shouldEmit( 'fakechannel', 'some message', $level, array() )
		);
	}

	public static function provideShouldEmit() {
		$dest = array( 'destination' => 'foobar' );
		$tests = array(
			array(
				LogLevel::DEBUG,
				$dest,
				true
			),
			array(
				LogLevel::WARNING,
				$dest + array( 'level' => LogLevel::INFO ),
				true,
			),
			array(
				LogLevel::INFO,
				$dest + array( 'level' => LogLevel::CRITICAL ),
				false,
			),
		);

		if ( class_exists( '\Monolog\Logger' ) ) {
			$tests[] = array(
				\Monolog\Logger::INFO,
				$dest + array( 'level' => LogLevel::INFO ),
				true,
			);
			$tests[] = array(
				\Monolog\Logger::WARNING,
				$dest + array( 'level' => LogLevel::EMERGENCY ),
				false,
			);
		}

		return $tests;
	}

}
