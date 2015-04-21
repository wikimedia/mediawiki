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

/**
 * @author Bryan Davis <bd808@wikimedia.org>
 * @copyright © 2015 Bryan Davis and Wikimedia Foundation.
 */
class EventDispatcherTest extends PHPUnit_Framework_TestCase {
	public function testBasicFunction() {
		$fixture = new EventDispatcher();
		$captured = false;
		$callback = function ( $e ) use ( &$captured ) {
			$captured = $e;
		};
		$payload = array( 1, 2, 3 );

		$fixture->listen( __METHOD__, $callback );
		$this->assertTrue( $fixture->hasListeners( __METHOD__ ) );

		$fixture->fire( __METHOD__, $payload );
		$this->assertInternalType( 'array', $captured );
		$this->assertArrayHasKey( 'topic', $captured );
		$this->assertSame( __METHOD__, $captured['topic'] );
		$this->assertArrayHasKey( 'data', $captured );
		$this->assertSame( $payload, $captured['data'] );
	}

	public function testGetNamedInstance() {
		$a = EventDispatcher::getNamedInstance( __METHOD__ );
		$this->assertNotNull( $a );
		$this->assertSame( $a, EventDispatcher::getNamedInstance( __METHOD__ ) );
		$this->assertNotSame( $a, EventDispatcher::getNamedInstance( 'foo' ) );
	}
}
