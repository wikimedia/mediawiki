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
use Title;

/**
 * @covers Title
 *
 * @author DannyS712
 */
class TitleTest extends MediaWikiUnitTestCase {

	public function testGetters() {
		// The only way to create a title without needing any services is ::makeTitle
		// link to `w:Project:About Wikipedia#Introduction'
		$title = Title::makeTitle(
			NS_PROJECT,
			'About Wikipedia',
			'Introduction',
			'w'
		);
		$this->assertTrue( $title->isExternal() );
		$this->assertSame( 'w', $title->getInterwiki() );
		$this->assertSame( 'About Wikipedia', $title->getText() );
		$this->assertSame( wfUrlencode( 'About_Wikipedia' ), $title->getPartialURL() );
		$this->assertSame( 'About_Wikipedia', $title->getDBkey() );
		$this->assertSame( NS_PROJECT, $title->getNamespace() );
		$this->assertFalse( $title->isSpecialPage() );
		$this->assertFalse( $title->isConversionTable() );
		$this->assertSame( 'Introduction', $title->getFragment() );
		$this->assertTrue( $title->hasFragment() );
	}

	public function testIsSpecial() {
		// Already checked false above, try with true now
		$title = Title::makeTitle( NS_SPECIAL, 'SpecialPages' );
		$this->assertTrue( $title->isSpecialPage() );
	}

	public function testIsConversionTable() {
		// Already checked false above, try with true now
		$title = Title::makeTitle( NS_MEDIAWIKI, 'Conversiontable/foo' );
		$this->assertTrue( $title->isConversionTable() );
	}

}
