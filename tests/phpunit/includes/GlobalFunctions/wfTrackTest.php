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

class wfTrackTest extends PHPUnit_Framework_TestCase {
	public function testBasicFunction() {
		$captured = false;
		$callback = function ( $topic, $data ) use ( &$captured ) {
			$captured = array(
				'topic' => $topic,
				'data' => $data,
			);
		};
		$payload = array( 1, 2, 3 );

		Hooks::register( 'TrackEvent', $callback );
		wfTrack( __METHOD__, $payload );

		$this->assertInternalType( 'array', $captured );
		$this->assertSame(
			array( 'topic' => __METHOD__, 'data' => $payload ),
			$captured
		);
	}
}
