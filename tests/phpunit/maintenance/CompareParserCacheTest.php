<?php

namespace MediaWiki\Tests\Maintenance;

use CompareParserCache;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Parser\ParserCache;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Title\Title;

/**
 * @covers \CompareParserCache
 * @group Database
 * @author Dreamy Jazz
 */
class CompareParserCacheTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return CompareParserCache::class;
	}

	public function testExecuteWhenNoPages() {
		$this->maintenance->setOption( 'namespace', 0 );
		$this->maintenance->setOption( 'maxpages', 100 );
		$this->maintenance->execute();
		$this->expectOutputRegex( '/^Checked 0 pages; 0 had prior cache entries/' );
	}

	public function testExecuteWhenNoPagesInRelevantNamespace() {
		// Create a page in any namespace other than NS_MAIN (0) to test namespace filter works as intended.
		$this->getExistingTestPage( Title::newFromText( 'Template:Test' ) );
		$this->testExecuteWhenNoPages();
	}

	private function setPageRandomForPage( PageIdentity $pageIdentity, int $pageRandomValue ) {
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'page' )
			->set( [ 'page_random' => $pageRandomValue ] )
			->where( [ 'page_title' => $pageIdentity->getDBkey(), 'page_namespace' => $pageIdentity->getNamespace() ] )
			->caller( __METHOD__ )
			->execute();
	}

	public function testExecuteWhenPageIsRedirect() {
		$title = Title::newFromText( 'Main Page' );
		$this->editPage( $title, '#REDIRECT [[Test]]' );
		$this->setPageRandomForPage( $title, 1 );

		$this->testExecuteWhenNoPages();
	}

	public function testExecuteWhenNoParserCacheEntryExists() {
		$page = $this->getExistingTestPage( Title::newFromText( 'Main Page' ) );
		$this->setPageRandomForPage( $page, 1 );

		// Add a hook that rejects all parser cache entries when they try to be fetched, so that our
		// test page has no parser cache entry. This simulates that no cache entry is found for the page.
		// It may not be called if no cache is created for the page.
		$this->setTemporaryHook( 'RejectParserCacheValue', static function () {
			return false;
		} );

		$this->maintenance->setOption( 'namespace', 0 );
		$this->maintenance->setOption( 'maxpages', 1 );
		$this->maintenance->execute();

		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( "No parser cache entry found for 'Main Page'", $actualOutput );
		$this->assertStringContainsString( 'Checked 1 pages; 0 had prior cache entries', $actualOutput );
	}

	public function testExecuteWhenParserCacheHasDifference() {
		$page = $this->getExistingTestPage( Title::newFromText( 'Main Page' ) );
		$this->editPage( $page, 'abcdef' );
		$this->setPageRandomForPage( $page, 1 );

		// Mock ParserCache::get to return something other than abcdef to have a difference between page content
		// and the parser cache content.
		$mockParserOutput = $this->createMock( ParserOutput::class );
		$mockParserOutput->method( 'getRawText' )
			->willReturn( 'abc' );
		$mockParserCache = $this->createMock( ParserCache::class );
		$mockParserCache->method( 'get' )
			->willReturnCallback( function ( $actualPage ) use ( $page, $mockParserOutput ) {
				$this->assertTrue( $page->isSamePageAs( $actualPage ) );
				return $mockParserOutput;
			} );
		$this->setService( 'ParserCache', $mockParserCache );

		$this->maintenance->setOption( 'namespace', 0 );
		$this->maintenance->setOption( 'maxpages', 1 );
		$this->maintenance->execute();

		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( "Found cache entry found for 'Main Page'...differences found:", $actualOutput );
		$this->assertStringContainsString( 'Checked 1 pages; 1 had prior cache entries', $actualOutput );
		$this->assertStringContainsString( 'Pages with differences found: 1', $actualOutput );
	}

	public function testExecuteWhenParserCacheIsSame() {
		$page = $this->getExistingTestPage( Title::newFromText( 'Main Page' ) );
		$this->editPage( $page, 'abcdef' );
		$this->setPageRandomForPage( $page, 1 );

		$this->maintenance->setOption( 'namespace', 0 );
		$this->maintenance->setOption( 'maxpages', 1 );
		$this->maintenance->execute();

		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( "Found cache entry found for 'Main Page'...No differences found", $actualOutput );
		$this->assertStringContainsString( 'Checked 1 pages; 1 had prior cache entries', $actualOutput );
	}
}
