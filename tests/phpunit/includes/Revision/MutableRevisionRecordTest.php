<?php

namespace MediaWiki\Tests\Revision;

use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MediaWikiIntegrationTestCase;
use MockTitleTrait;

/**
 * @covers \MediaWiki\Revision\MutableRevisionRecord
 * @covers \MediaWiki\Revision\RevisionRecord
 */
class MutableRevisionRecordTest extends MediaWikiIntegrationTestCase {
	use MockTitleTrait;

	public static function provideConstructor() {
		$title = Title::makeTitle( NS_MAIN, 'Dummy' );
		$title->resetArticleID( 17 );
		yield 'local wiki, with title' => [ $title, PageIdentity::LOCAL ];
		yield 'local wiki' => [
			PageIdentityValue::localIdentity( 17, NS_MAIN, 'Dummy' ),
			PageIdentity::LOCAL,
		];
		yield 'foreign wiki' => [
			new PageIdentityValue( 17, NS_MAIN, 'Dummy', 'acmewiki' ),
			'acmewiki',
		];
	}

	/**
	 * @dataProvider provideConstructor
	 *
	 * @param PageIdentity $page
	 * @param string|false $wikiId
	 */
	public function testConstructorAndGetters(
		PageIdentity $page,
		$wikiId = RevisionRecord::LOCAL
	) {
		$rec = new MutableRevisionRecord( $page, $wikiId );

		$this->assertTrue( $page->isSamePageAs( $rec->getPage() ), 'getPage' );
		$this->assertSame( $wikiId, $rec->getWikiId(), 'getWikiId' );

		$this->assertTrue(
			TitleValue::newFromPage( $page )->isSameLinkAs( $rec->getPageAsLinkTarget() ),
			'getPageAsLinkTarget'
		);
	}
}
