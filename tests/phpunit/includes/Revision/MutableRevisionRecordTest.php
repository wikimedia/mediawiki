<?php

namespace MediaWiki\Tests\Revision;

use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWikiIntegrationTestCase;
use MockTitleTrait;
use Title;
use TitleValue;
use Wikimedia\Assert\PreconditionException;

/**
 * @covers \MediaWiki\Revision\MutableRevisionRecord
 * @covers \MediaWiki\Revision\RevisionRecord
 */
class MutableRevisionRecordTest extends MediaWikiIntegrationTestCase {
	use MockTitleTrait;

	public function provideConstructor() {
		$title = Title::newFromText( 'Dummy' );
		$title->resetArticleID( 17 );
		yield 'local wiki, with title' => [ $title, PageIdentity::LOCAL ];
		yield 'local wiki' => [
			new PageIdentityValue( 17, NS_MAIN, 'Dummy', PageIdentity::LOCAL ),
			PageIdentity::LOCAL,
		];
		yield 'foreign wiki' => [
			new PageIdentityValue( 17, NS_MAIN, 'Dummy', 'acmewiki' ),
			'acmewiki',
			PreconditionException::class
		];
	}

	/**
	 * @dataProvider provideConstructor
	 *
	 * @param PageIdentity $page
	 * @param bool $wikiId
	 * @param string|null $expectedException
	 */
	public function testConstructorAndGetters(
		PageIdentity $page,
		$wikiId = RevisionRecord::LOCAL,
		string $expectedException = null
	) {
		$rec = new MutableRevisionRecord( $page, $wikiId );

		$this->assertTrue( $page->isSamePageAs( $rec->getPage() ), 'getPage' );
		$this->assertSame( $wikiId, $rec->getWikiId(), 'getWikiId' );

		if ( $expectedException ) {
			$this->expectException( $expectedException );
			$rec->getPageAsLinkTarget();
		} else {
			$this->assertTrue(
				TitleValue::newFromPage( $page )->isSameLinkAs( $rec->getPageAsLinkTarget() ),
				'getPageAsLinkTarget'
			);
		}
	}
}
