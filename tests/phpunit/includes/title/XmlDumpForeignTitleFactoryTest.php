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
 * @author This, that and the other
 */

/**
 * @covers XmlDumpForeignTitleFactory
 *
 * @group Title
 */
class XmlDumpForeignTitleFactoryTest extends MediaWikiTestCase {

	public function basicProvider() {
		return array(
			array(
				'MainNamespaceArticle', 0,
				new ForeignTitle( 0, '', 'MainNamespaceArticle' ),
			),
			array(
				'MainNamespaceArticle', null,
				new ForeignTitle( null, '', 'MainNamespaceArticle' ),
			),
			array(
				'Talk:Nice_talk', 1,
				new ForeignTitle( 1, 'Talk', 'Nice_talk' ),
			),
			array(
				'Bogus:Nice_talk', 0,
				new ForeignTitle( 0, '', 'Bogus:Nice_talk' ),
			),
			array(
				'Bogus:Nice_talk', 9000, // non-existent local namespace ID
				new ForeignTitle( 9000, 'Bogus', 'Nice_talk' ),
			),
			array(
				'Bogus:Nice_talk', 4, // existing local namespace ID
				new ForeignTitle( 4, 'Bogus', 'Nice_talk' ),
			),
		);
	}

	/**
	 * @dataProvider basicProvider
	 */
	public function testBasic( $title, $ns, ForeignTitle $foreignTitle ) {
		$factory = new XmlDumpForeignTitleFactory( null );
		$testTitle = $factory->createForeignTitle( $title, $ns );

		$this->assertEquals( $testTitle->isNamespaceIdKnown(),
			$foreignTitle->isNamespaceIdKnown() );

		if (
			$testTitle->isNamespaceIdKnown() &&
			$foreignTitle->isNamespaceIdKnown()
		) {
			$this->assertEquals( $testTitle->getNamespaceId(),
				$foreignTitle->getNamespaceId() );
		}

		$this->assertEquals( $testTitle->getNamespaceName(),
			$foreignTitle->getNamespaceName() );
		$this->assertEquals( $testTitle->getText(), $foreignTitle->getText() );
	}

	public function foreignNamespacesProvider() {
		return array(
			array(
				'MainNamespaceArticle', 0,
				new ForeignTitle( 0, '', 'MainNamespaceArticle' ),
			),
			array(
				'MainNamespaceArticle', null,
				new ForeignTitle( 0, '', 'MainNamespaceArticle' ),
			),
			array(
				'Talk:Nice_talk', 1,
				new ForeignTitle( 1, 'Talk', 'Nice_talk' ),
			),
			array(
				'Bogus:Nice_talk', 0,
				new ForeignTitle( 0, '', 'Bogus:Nice_talk' ),
			),
			array(
				'Bogus:Nice_talk', null,
				new ForeignTitle( 9000, 'Bogus', 'Nice_talk' ),
			),
			array(
				'Bogus:Nice_talk', 4,
				new ForeignTitle( 4, 'Bogus', 'Nice_talk' ),
			),
			array(
				'Bogus:Nice_talk', 1,
				new ForeignTitle( 1, 'Talk', 'Nice_talk' ),
			),
		);
	}

	/**
	 * @dataProvider foreignNamespacesProvider
	 */
	public function testForeignNamespaces( $title, $ns,
		ForeignTitle $foreignTitle ) {

		$foreignNamespaces = array(
			0 => '', 1 => 'Talk', 100 => 'Portal', 9000 => 'Bogus'
		);

		$factory = new XmlDumpForeignTitleFactory( $foreignNamespaces );
		$testTitle = $factory->createForeignTitle( $title, $ns );

		$this->assertEquals( $testTitle->isNamespaceIdKnown(),
			$foreignTitle->isNamespaceIdKnown() );

		if (
			$testTitle->isNamespaceIdKnown() &&
			$foreignTitle->isNamespaceIdKnown()
		) {
			$this->assertEquals( $testTitle->getNamespaceId(),
				$foreignTitle->getNamespaceId() );
		}

		$this->assertEquals( $testTitle->getNamespaceName(),
			$foreignTitle->getNamespaceName() );
		$this->assertEquals( $testTitle->getText(), $foreignTitle->getText() );
	}
}
