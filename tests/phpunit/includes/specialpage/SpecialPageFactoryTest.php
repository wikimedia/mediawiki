<?php
/**
 * Factory for handling the special page list and generating SpecialPage objects.
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
 * @covers SpecialPageFactory
 * @group SpecialPage
 */
class SpecialPageFactoryTest extends MediaWikiTestCase {

	public function newSpecialAllPages() {
		return new SpecialAllPages();
	}

	public function specialPageProvider() {
		return array(
			'class name' => array( 'SpecialAllPages', false ),
			'closure' => array( function() {
				return new SpecialAllPages();
			}, false ),
			'function' => array( array( $this, 'newSpecialAllPages' ), false  ),
		);
	}

	/**
	 * @dataProvider specialPageProvider
	 */
	public function testGetPage( $spec, $shouldReuseInstance ) {
		$this->mergeMwGlobalArrayValue( 'wgSpecialPages', array( 'testdummy' => $spec ) );

		SpecialPageFactory::resetList();

		$page = SpecialPageFactory::getPage( 'testdummy' );
		$this->assertInstanceOf( 'SpecialPage', $page );

		$page2 = SpecialPageFactory::getPage( 'testdummy' );
		$this->assertEquals( $shouldReuseInstance, $page2 === $page, "Should re-use instance:" );

		SpecialPageFactory::resetList();
	}

	public function testGetNames() {
		$this->mergeMwGlobalArrayValue( 'wgSpecialPages', array( 'testdummy' => 'SpecialAllPages' ) );

		SpecialPageFactory::resetList();
		$names = SpecialPageFactory::getNames();
		$this->assertInternalType( 'array', $names );
		$this->assertContains( 'testdummy', $names );
		SpecialPageFactory::resetList();
	}

	public function testResolveAlias() {
		$this->setMwGlobals( 'wgContLang', Language::factory( 'de' ) );

		SpecialPageFactory::resetList();

		list( $name, $param ) = SpecialPageFactory::resolveAlias( 'Spezialseiten/Foo' );
		$this->assertEquals( 'Specialpages', $name );
		$this->assertEquals( 'Foo', $param );

		SpecialPageFactory::resetList();
	}

	public function testGetLocalNameFor() {
		$this->setMwGlobals( 'wgContLang', Language::factory( 'de' ) );

		SpecialPageFactory::resetList();

		$name = SpecialPageFactory::getLocalNameFor( 'Specialpages', 'Foo' );
		$this->assertEquals( 'Spezialseiten/Foo', $name );

		SpecialPageFactory::resetList();
	}

	public function testGetTitleForAlias() {
		$this->setMwGlobals( 'wgContLang', Language::factory( 'de' ) );

		SpecialPageFactory::resetList();

		$title = SpecialPageFactory::getTitleForAlias( 'Specialpages/Foo' );
		$this->assertEquals( 'Spezialseiten/Foo', $title->getText() );
		$this->assertEquals( NS_SPECIAL, $title->getNamespace() );

		SpecialPageFactory::resetList();
	}

}
