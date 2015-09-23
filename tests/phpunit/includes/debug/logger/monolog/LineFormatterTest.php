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

namespace MediaWiki\Logger\Monolog;

use InvalidArgumentException;
use LengthException;
use LogicException;
use MediaWikiTestCase;
use TestingAccessWrapper;

class LineFormatterTest extends MediaWikiTestCase {

	protected function setUp() {
		if ( !class_exists( 'Monolog\Formatter\LineFormatter' ) ) {
			$this->markTestSkipped( 'This test requires monolog to be installed' );
		}
		parent::setUp();
	}

	/**
	 * @covers LineFormatter::normalizeException
	 */
	public function testNormalizeExceptionNoTrace() {
		$fixture = new LineFormatter();
		$fixture->includeStacktraces( false );
		$fixture = TestingAccessWrapper::newFromObject( $fixture );
		$boom = new InvalidArgumentException( 'boom', 0,
			new LengthException( 'too long', 0,
				new LogicException( 'Spock wuz here' )
			)
		);
		$out = $fixture->normalizeException( $boom );
		$this->assertContains( "\n[Exception InvalidArgumentException]", $out );
		$this->assertContains( "\nCaused by: [Exception LengthException]", $out );
		$this->assertContains( "\nCaused by: [Exception LogicException]", $out );
		$this->assertNotContains( "\n  #0", $out );
	}

	/**
	 * @covers LineFormatter::normalizeException
	 */
	public function testNormalizeExceptionTrace() {
		$fixture = new LineFormatter();
		$fixture->includeStacktraces( true );
		$fixture = TestingAccessWrapper::newFromObject( $fixture );
		$boom = new InvalidArgumentException( 'boom', 0,
			new LengthException( 'too long', 0,
				new LogicException( 'Spock wuz here' )
			)
		);
		$out = $fixture->normalizeException( $boom );
		$this->assertContains( "\n[Exception InvalidArgumentException]", $out );
		$this->assertContains( "\nCaused by: [Exception LengthException]", $out );
		$this->assertContains( "\nCaused by: [Exception LogicException]", $out );
		$this->assertContains( "\n  #0", $out );
	}
}
