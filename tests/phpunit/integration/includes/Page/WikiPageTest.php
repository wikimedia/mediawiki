<?php

namespace MediaWiki\Tests\Page;

use MediaWiki\Content\Content;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Page\WikiPage;
use MediaWiki\Revision\RevisionStoreCacheRecord;
use MediaWiki\Revision\RevisionStoreRecord;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * @covers \MediaWiki\Page\WikiPage
 * @group Database
 * @note Page command related tests are in different files
 */
class WikiPageTest extends MediaWikiIntegrationTestCase {

	private const PAGE_TEXT = "[[Puss in Boots]]\n" .
		"{{Multiple issues}}\n" .
		"https://www.example.com/\n" .
		"[[Category:Felis catus]]";

	/**
	 * @param string $titleText
	 * @param string|Content $content
	 * @return WikiPage
	 */
	private function createPage( string $titleText, $content ): WikiPage {
		if ( is_string( $content ) ) {
			$content = new WikitextContent( $content );
		}

		$ns = $this->getDefaultWikitextNS();
		$title = Title::newFromText( $titleText, $ns );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		$this->editPage( $page, $content );

		return $page;
	}

	public function testLoadPageData() {
		$page = $this->createPage( 'Puss in Boots', self::PAGE_TEXT );
		$revId = $page->getLatest();

		// Simulate initial page row load, revision not in cache, then access revision
		$page->clear();
		$page->loadPageData( IDBAccessObject::READ_NORMAL );
		$rev = $page->getRevisionRecord();
		$this->assertSame( $revId, $rev->getId() );
		$this->assertInstanceOf( RevisionStoreRecord::class, $rev );
		$this->assertNotInstanceOf( RevisionStoreCacheRecord::class, $rev, 'Revision from DB' );
		// Request reload to the same READ_* level (no-op)
		$page->loadPageData( IDBAccessObject::READ_NORMAL );
		$this->assertSame( $rev, $page->getRevisionRecord(), 'Revision reused' );

		// Simulate initial page row load, revision in cache, then access revision
		$page->clear();
		$page->loadPageData( IDBAccessObject::READ_NORMAL );
		$rev = $page->getRevisionRecord();
		$this->assertSame( $revId, $rev->getId() );
		$this->assertInstanceOf( RevisionStoreCacheRecord::class, $rev, 'Revision from cache' );
		$page->loadPageData( IDBAccessObject::READ_NORMAL );
		$this->assertSame( $rev, $page->getRevisionRecord(), 'Revision reused' );
		// Reload the page row by requesting a higher READ_* level, then access revision
		$page->loadPageData( IDBAccessObject::READ_LATEST );
		$lrev = $page->getRevisionRecord();
		$this->assertSame( $revId, $lrev->getId() );
		$this->assertInstanceOf( RevisionStoreRecord::class, $lrev );
		$this->assertNotInstanceOf( RevisionStoreCacheRecord::class, $lrev, 'Revision from DB' );
		$this->assertNotSame( $rev, $lrev, 'Revision not reused' );

		// Simulate initial page row load (for write), revision in cache, then access revision
		$page->clear();
		$page->loadPageData( IDBAccessObject::READ_LATEST );
		$rev = $page->getRevisionRecord();
		$this->assertSame( $revId, $rev->getId() );
		$this->assertInstanceOf( RevisionStoreRecord::class, $rev );
		$this->assertNotInstanceOf( RevisionStoreCacheRecord::class, $rev, 'Revision from DB' );
	}
}
