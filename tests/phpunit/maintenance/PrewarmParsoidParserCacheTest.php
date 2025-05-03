<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\Page\PageRecord;
use MediaWiki\Page\PageStore;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\Parsoid\Config\SiteConfig;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use PrewarmParsoidParserCache;

/**
 * @covers \PrewarmParsoidParserCache
 * @group Database
 * @author Dreamy Jazz
 */
class PrewarmParsoidParserCacheTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return PrewarmParsoidParserCache::class;
	}

	public function testWhenStartFromIsBeyondLargestPageId() {
		$mockParserOutputAccess = $this->createMock( ParserOutputAccess::class );
		$mockParserOutputAccess->expects( $this->never() )
			->method( 'getParserOutput' );
		$this->setService( 'ParserOutputAccess', $mockParserOutputAccess );

		$this->maintenance->setOption( 'start-from', 12345 );
		$this->maintenance->execute();
		$this->expectOutputString(
			"\nWarming parsoid parser cache with Parsoid output...\n\n" .
			"\nDone pre-warming parsoid parser cache...\n"
		);
	}

	public function testWhenAllPagesHaveNoEntryInPageLookupService() {
		$mockParserOutputAccess = $this->createMock( ParserOutputAccess::class );
		$mockParserOutputAccess->expects( $this->never() )
			->method( 'getParserOutput' );
		$this->setService( 'ParserOutputAccess', $mockParserOutputAccess );

		$mockPageLookup = $this->createMock( PageStore::class );
		$mockPageLookup->method( 'getPageById' )
			->willReturn( null );
		$this->setService( 'PageStore', $mockPageLookup );

		$this->maintenance->execute();

		$actualOutput = $this->getActualOutputForAssertion();
		foreach ( range( 1, 4 ) as $pageId ) {
			$this->assertStringContainsString( "[Skipped] Page ID: $pageId not found", $actualOutput );
		}
	}

	public function testWhenAllPagesDoNotHaveSupportedContentModel() {
		$mockParserOutputAccess = $this->createMock( ParserOutputAccess::class );
		$mockParserOutputAccess->expects( $this->never() )
			->method( 'getParserOutput' );
		$this->setService( 'ParserOutputAccess', $mockParserOutputAccess );

		$mockParsoidSiteConfig = $this->createMock( SiteConfig::class );
		$mockParsoidSiteConfig->method( 'supportsContentModel' )
			->willReturn( false );
		$this->setService( 'ParsoidSiteConfig', $mockParsoidSiteConfig );

		$this->maintenance->execute();

		$actualOutput = $this->getActualOutputForAssertion();
		foreach ( range( 1, 4 ) as $pageId ) {
			$this->assertStringContainsString(
				"[Skipped] Content model \"" . CONTENT_MODEL_WIKITEXT . "\" not supported for page ID: $pageId",
				$actualOutput
			);
		}
	}

	public function testWhenOnlyFilteringForTemplateNamespaceWhichFailsToParse() {
		$mockParserOutputAccess = $this->createMock( ParserOutputAccess::class );
		$mockParserOutputAccess->expects( $this->once() )
			->method( 'getParserOutput' )
			->willReturnCallback( function ( PageRecord $page, $parserOptions, $revision, int $options ) {
				$this->assertSame( NS_TEMPLATE, $page->getNamespace() );
				$this->assertSame( 'Test', $page->getDBkey() );
				$this->assertSame( ParserOutputAccess::OPT_FORCE_PARSE, $options );
				return Status::newFatal( 'test' );
			} );
		$this->setService( 'ParserOutputAccess', $mockParserOutputAccess );

		$this->maintenance->setOption( 'namespace', 'template' );
		$this->maintenance->setOption( 'force', 1 );
		$this->maintenance->execute();

		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringNotContainsString(
			': 1', $actualOutput, 'First test page should have been excluded by namespace filter'
		);
		$this->assertStringContainsString( 'Error parsing page ID: 2 or writing to parser cache', $actualOutput );
		$this->assertStringNotContainsString( '[Done] Page ID', $actualOutput );
	}

	public function testWhenRunningForAllPages() {
		// Drop any existing cached parsoid parser cache entries so that we can see they are added by the script.
		foreach ( range( 1, 4 ) as $pageId ) {
			$wikiPage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromID( $pageId ) );
			$this->getServiceContainer()->getParserCache()
				->deleteOptionsKey( $wikiPage );

			$popts = ParserOptions::newFromAnon();
			$popts->setUseParsoid();
			$this->assertNull( $this->getServiceContainer()->getParserOutputAccess()->getCachedParserOutput(
				$wikiPage, $popts, $wikiPage->getRevisionRecord()
			) );
		}

		$this->maintenance->execute();

		// Check that the script created parsoid cache entries.
		$actualOutput = $this->getActualOutputForAssertion();
		foreach ( range( 1, 4 ) as $pageId ) {
			$this->assertStringContainsString( "[Done] Page ID: $pageId", $actualOutput );

			$popts = ParserOptions::newFromAnon();
			$popts->setUseParsoid();
			$wikiPage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromID( $pageId ) );
			$this->assertNotNull( $this->getServiceContainer()->getParserOutputAccess()->getCachedParserOutput(
				$wikiPage, $popts, $wikiPage->getRevisionRecord()
			) );
		}
	}

	public function addDBDataOnce() {
		// Add some content to some test pages which will be used to prewarm the cache
		$this->editPage( Title::newFromText( 'Test' ), 'testingabc' );
		$this->editPage( Title::newFromText( 'Template:Test' ), 'testingabc' );
		$this->editPage( Title::newFromText( 'Testing' ), 'testingabc{{test}}' );
		$this->editPage( Title::newFromText( 'Testingabcef' ), 'testingabc<span>Test!</span>' );
	}
}
