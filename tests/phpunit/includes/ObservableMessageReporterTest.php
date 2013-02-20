<?php

/**
 * Tests for the ObservableMessageReporter class.
 *
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
 * @since 1.21
 *
 * @ingroup Test
 *
 * @group MessageReporter
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ObservableMessageReporterTest extends MessageReporterTest {

	/**
	 * @return MessageReporter[]
	 */
	public function getInstances() {
		$instances = array();

		$instances[] = new ObservableMessageReporter();

		$reporter = new ObservableMessageReporter();
		$reporter->registerMessageReporter( new ObservableMessageReporter() );
		$callback0 = function( $string ) {};
		$callback1 = function( $string ) {};
		$instances[] = $reporter;

		$reporter = clone $reporter;
		$reporter->registerReporterCallback( $callback0 );
		$reporter->registerReporterCallback( $callback1 );
		$instances[] = $reporter;

		return $instances;
	}

	/**
	 * @dataProvider reportMessageProvider
	 *
	 * @param string $message
	 */
	public function testCallbackInvocation( $message ) {
		$callCount = 0;
		$asserter = array( $this, 'assertEquals' );

		$callback0 = function( $actual ) use ( $message, &$callCount, $asserter ) {
			$callCount += 1;
			call_user_func( $asserter, $message, $actual );
		};

		$callback1 = function( $actual ) use ( $message, &$callCount, $asserter ) {
			$callCount += 1;
			call_user_func( $asserter, $message, $actual );
		};

		$reporter = new ObservableMessageReporter();
		$reporter->registerReporterCallback( $callback0 );
		$reporter->registerReporterCallback( $callback1 );

		$reporter->reportMessage( $message );

		$this->assertEquals( 2, $callCount );

		$reporter->reportMessage( $message );

		$this->assertEquals( 4, $callCount );
	}

	/**
	 * @dataProvider reportMessageProvider
	 *
	 * @param string $message
	 */
	public function testReporterInvocation( $message ) {
		$callCount = 0;
		$asserter = array( $this, 'assertEquals' );

		$callback0 = function( $actual ) use ( $message, &$callCount, $asserter ) {
			$callCount += 1;
			call_user_func( $asserter, $message, $actual );
		};

		$callback1 = function( $actual ) use ( $message, &$callCount, $asserter ) {
			$callCount += 1;
			call_user_func( $asserter, $message, $actual );
		};

		$reporter0 = new ObservableMessageReporter();
		$reporter0->registerReporterCallback( $callback0 );

		$reporter1 = new ObservableMessageReporter();
		$reporter1->registerReporterCallback( $callback1 );

		$reporter = new ObservableMessageReporter();
		$reporter->registerMessageReporter( $reporter0 );
		$reporter->registerMessageReporter( $reporter1 );

		$reporter->reportMessage( $message );

		$this->assertEquals( 2, $callCount );

		$reporter->reportMessage( $message );

		$this->assertEquals( 4, $callCount );
	}

}

/**
 * Tests for the MessageReporter interface.
 *
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
 * @since 1.21
 *
 * @ingroup Test
 *
 * @group MessageReporter
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class MessageReporterTest extends MediaWikiTestCase {

	/**
	 * @return MessageReporter[]
	 */
	public abstract function getInstances();

	/**
	 * Message provider, includes edge cases and random tests
	 *
	 * @return array
	 */
	public function reportMessageProvider() {
		$messages = array();

		$messages[] = '';
		$messages[] = '  ';

		foreach ( array_merge( range( 1, 100 ), array( 1000, 10000 ) ) as $length ) {
			$string = array();

			for ( $position = 0; $position < $length; $position++ ) {
				$string[] = chr( mt_rand( 32, 126 ) );
			}

			$messages[] = implode( '', $string );
		}

		return $this->arrayWrap( $messages );
	}

	/**
	 * @dataProvider reportMessageProvider
	 *
	 * @param string $message
	 */
	public function testReportMessage( $message ) {
		foreach ( $this->getInstances() as $reporter ) {
			$reporter->reportMessage( $message );
			$reporter->reportMessage( $message );
			$this->assertTrue( true );
		}
	}

}