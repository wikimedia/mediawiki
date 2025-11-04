<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author This, that and the other
 */

use MediaWiki\Title\ForeignTitle;
use MediaWiki\Title\NamespaceAwareForeignTitleFactory;

/**
 * @covers \MediaWiki\Title\NamespaceAwareForeignTitleFactory
 *
 * @group Title
 */
class NamespaceAwareForeignTitleFactoryTest extends \MediaWikiUnitTestCase {

	public static function basicProvider() {
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
