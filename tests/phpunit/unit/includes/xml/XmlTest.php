<?php

/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit;

use MediaWiki\Xml\Xml;
use MediaWikiUnitTestCase;

/**
 * Split from \XmlTest integration tests
 *
 * @group Xml
 * @covers \MediaWiki\Xml\Xml
 */
class XmlTest extends MediaWikiUnitTestCase {

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

	public function testEscapeTagsOnly() {
		$this->assertEquals( '&quot;&gt;&lt;', Xml::escapeTagsOnly( '"><' ),
			'replace " > and < with their HTML entitites'
		);
	}
}
