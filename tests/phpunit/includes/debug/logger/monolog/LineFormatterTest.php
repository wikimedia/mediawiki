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
use MediaWikiTestCase;
use TestingAccessWrapper;

class LineFormatterTest extends MediaWikiTestCase {

	/**
	 * @covers LineFormatter::normalizeException
	 */
	public function testNormalizeExceptionNoTrace() {
		$fixture = new LineFormatter();
		$fixture->includeStacktraces( false );
		$fixture = TestingAccessWrapper::newFromObject( $fixture );
		$out = $fixture->normalizeException( new InvalidArgumentException );
		$this->assertContains( '[Exception InvalidArgumentException', $out );
		$this->assertNotContains( '[stacktrace]', $out );
	}

	/**
	 * @covers LineFormatter::normalizeException
	 */
	public function testNormalizeExceptionTrace() {
		$fixture = new LineFormatter();
		$fixture->includeStacktraces( true );
		$fixture = TestingAccessWrapper::newFromObject( $fixture );
		$out = $fixture->normalizeException( new InvalidArgumentException );
		$this->assertContains( '[Exception InvalidArgumentException', $out );
		$this->assertContains( '[stacktrace]', $out );
	}
}
