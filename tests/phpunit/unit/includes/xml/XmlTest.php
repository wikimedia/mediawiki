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

namespace MediaWiki\Tests\Unit;

use MediaWikiUnitTestCase;
use Xml;

/**
 * Split from \XmlTest integration tests
 *
 * @group Xml
 */
class XmlTest extends MediaWikiUnitTestCase {

	/**
	 * @covers Xml::expandAttributes
	 */
	public function testExpandAttributes() {
		$this->assertNull(
			Xml::expandAttributes( null ),
			'Converting a null list of attributes'
		);
		$this->assertSame(
			'',
			Xml::expandAttributes( [] ),
			'Converting an empty list of attributes'
		);
	}

	/**
	 * @covers Xml::escapeTagsOnly
	 */
	public function testEscapeTagsOnly() {
		$this->assertEquals( '&quot;&gt;&lt;', Xml::escapeTagsOnly( '"><' ),
			'replace " > and < with their HTML entitites'
		);
	}

}
