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
 * @license GPL 2+
 * @author Daniel Kinzler
 */

/**
 * @covers TitleValue
 *
 * @group Title
 */
class TitleValueTest extends MediaWikiTestCase {

	public function testConstruction() {
		$title = new TitleValue( TitleValue::DBKEY_FORM, NS_USER, 'TestThis', 'stuff' );

		$this->assertEquals( TitleValue::DBKEY_FORM, $title->getForm() );
		$this->assertEquals( NS_USER, $title->getNamespace() );
		$this->assertEquals( 'TestThis', $title->getText() );
		$this->assertEquals( 'stuff', $title->getSection() );
	}

	public function badConstructorProvider() {
		return array(
			array( TitleValue::DBKEY_FORM, 'foo', 'title', 'section' ),
			array( TitleValue::DBKEY_FORM, null, 'title', 'section' ),
			array( TitleValue::DBKEY_FORM, 2.3, 'title', 'section' ),

			array( TitleValue::TITLE_FORM, NS_MAIN, 5, 'section' ),
			array( TitleValue::TITLE_FORM, NS_MAIN, null, 'section' ),
			array( TitleValue::TITLE_FORM, NS_MAIN, '', 'section' ),

			array( TitleValue::UNKNOWN_FORM, NS_MAIN, 'title', 5 ),
			array( TitleValue::UNKNOWN_FORM, NS_MAIN, 'title', null ),
			array( TitleValue::UNKNOWN_FORM, NS_MAIN, 'title', array() ),

			array( 7, NS_MAIN, 'title', '' ),
			array( null, NS_MAIN, 'title', '' ),
			array( array(), NS_MAIN, 'title', '' ),
		);
	}

	/**
	 * @dataProvider badConstructorProvider
	 */
	public function testConstructionErrors( $form, $ns, $text, $section ) {
		$this->setExpectedException( 'InvalidArgumentException' );
		new TitleValue( $form, $ns, $text, $section );
	}

	public function sectionTitleProvider() {
		return array(
			array( new TitleValue( TitleValue::DBKEY_FORM, NS_MAIN, 'Test' ), 'foo' ),
			array( new TitleValue( TitleValue::TITLE_FORM, NS_TALK, 'Test', 'foo' ), '' ),
			array( new TitleValue( TitleValue::UNKNOWN_FORM, NS_CATEGORY, 'Test', 'foo' ), 'bar' ),
		);
	}

	/**
	 * @dataProvider sectionTitleProvider
	 */
	public function testCreateSectionTitle( TitleValue $title, $section ) {
		$sectionTitle = $title->createSectionTitle( $section );

		$this->assertEquals( $title->getForm(), $sectionTitle->getForm() );
		$this->assertEquals( $title->getNamespace(), $sectionTitle->getNamespace() );
		$this->assertEquals( $title->getText(), $sectionTitle->getText() );
		$this->assertEquals( $section, $sectionTitle->getSection() );
	}

	public function checkFormProvider() {
		return array(
			array( TitleValue::DBKEY_FORM, TitleValue::DBKEY_FORM, true ),
			array( TitleValue::TITLE_FORM, TitleValue::TITLE_FORM, true ),

			array( TitleValue::TITLE_FORM, TitleValue::DBKEY_FORM, false ),
			array( TitleValue::DBKEY_FORM, TitleValue::UNKNOWN_FORM, false ),

			array( TitleValue::DBKEY_FORM, '', false ),
			array( TitleValue::TITLE_FORM, 'alkdsjfldsa', false ),
		);
	}

	/**
	 * @dataProvider checkFormProvider
	 */
	public function testCheckForm( $wanted, $form, $ok ) {
		if ( !$ok ) {
			$this->setExpectedException( 'MWException' );
		}

		$title = new TitleValue( $form, NS_MAIN, 'Foo' );
		$title->checkForm( $wanted );

		$this->assertEquals( $wanted, $title->getForm() );
	}
}
