<?php
/**
 * @section LICENSE
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

class MWLoggerLegacyLoggerTest extends MediaWikiTestCase {

	/**
	 * @covers MWLoggerLegacyLogger::interpolate
	 * @dataProvider provideInterpolate
	 */
	public function testInterpolate( $message, $context, $expect ) {
		$this->assertEquals(
			$expect, MWLoggerLegacyLogger::interpolate( $message, $context ) );
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

}
