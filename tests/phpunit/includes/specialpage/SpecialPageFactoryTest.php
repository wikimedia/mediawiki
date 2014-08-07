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
			'class name' => array( 'SpecialAllPages' ),
			'closure' => array( function() {
				return new SpecialAllPages();
			} ),
			'function' => array( array( $this, 'newSpecialAllPages' ) ),
		);
	}

	/**
	 * @dataProvider specialPageProvider
	 */
	public function testGetPage( $spec ) {
		$this->mergeMwGlobalArrayValue( 'wgSpecialPages', array( 'testdummy' => $spec ) );

		SpecialPageFactory::resetList();
		$this->assertInstanceOf( 'SpecialPage', SpecialPageFactory::getPage( 'testdummy' ) );
		SpecialPageFactory::resetList();
	}

	/**
	 * @dataProvider specialPageProvider
	 */
	public function testGetNames() {
		$this->mergeMwGlobalArrayValue( 'wgSpecialPages', array( 'testdummy' => 'SpecialAllPages' ) );

		SpecialPageFactory::resetList();
		$names = SpecialPageFactory::getNames();
		$this->assertInternalType( 'array', $names );
		$this->assertContains( 'testdummy', $names );
		SpecialPageFactory::resetList();
	}

}
