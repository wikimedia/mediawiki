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
 * @author This, that and the other
 */

/**
 * @covers NamespaceAwareForeignTitleFactory
 *
 * @group Title
 */
class NamespaceAwareForeignTitleFactoryTest extends MediaWikiTestCase {

	public function basicProvider() {
		return [
			[
				'MainNamespaceArticle', 0,
				new ForeignTitle( 0, '', 'MainNamespaceArticle' ),
			],
			[
				'MainNamespaceArticle', null,
				new ForeignTitle( 0, '', 'MainNamespaceArticle' ),
			],
			[
				'Magic:_The_Gathering', 0,
				new ForeignTitle( 0, '', 'Magic:_The_Gathering' ),
			],
			[
				'Talk:Nice_talk', 1,
				new ForeignTitle( 1, 'Talk', 'Nice_talk' ),
			],
			[
				'Talk:Magic:_The_Gathering', 1,
				new ForeignTitle( 1, 'Talk', 'Magic:_The_Gathering' ),
			],
			[
				'Bogus:Nice_talk', 0,
				new ForeignTitle( 0, '', 'Bogus:Nice_talk' ),
			],
			[
				'Bogus:Nice_talk', null,
				new ForeignTitle( 9000, 'Bogus', 'Nice_talk' ),
			],
			[
				'Bogus:Nice_talk', 4,
				new ForeignTitle( 4, 'Bogus', 'Nice_talk' ),
			],
			[
				'Bogus:Nice_talk', 1,
				new ForeignTitle( 1, 'Talk', 'Nice_talk' ),
			],
			// Misconfigured wiki with unregistered namespace (T114115)
			[
				'Nice_talk', 1234,
				new ForeignTitle( 1234, 'Ns1234', 'Nice_talk' ),
			],
		];
	}

	/**
	 * @dataProvider basicProvider
	 */
	public function testBasic( $title, $ns, ForeignTitle $foreignTitle ) {

		$foreignNamespaces = [
			0 => '', 1 => 'Talk', 100 => 'Portal', 9000 => 'Bogus'
		];

		$factory = new NamespaceAwareForeignTitleFactory( $foreignNamespaces );
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
