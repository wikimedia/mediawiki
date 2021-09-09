<?php
namespace MediaWiki\Tests\Page;

use Exception;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Page\PageSelectQueryBuilder;
use MediaWiki\Page\PageStore;
use MediaWikiIntegrationTestCase;

/**
 * @group Database
 */
class PageSelectQueryBuilderTest extends MediaWikiIntegrationTestCase {

	public function addDBDataOnce() {
		$this->getExistingTestPage( 'AA' );
		$this->getExistingTestPage( 'AB' );
		$this->getExistingTestPage( 'BB' );

		$this->getExistingTestPage( 'Talk:AA' );
		$this->getExistingTestPage( 'User:AB' );
	}

	/**
	 * @return PageStore
	 * @throws Exception
	 */
	private function getPageStore() {
		$services = $this->getServiceContainer();

		$serviceOptions = new ServiceOptions(
			PageStore::CONSTRUCTOR_OPTIONS,
			[
				'LanguageCode' => $services->getContentLanguage()->getCode(),
				'PageLanguageUseDB' => true
			]
		);

		return new PageStore(
			$serviceOptions,
			$services->getDBLoadBalancer(),
			$services->getNamespaceInfo(),
			$services->getTitleParser(),
			$services->getLinkCache(),
			$services->getStatsdDataFactory()
		);
	}

	/**
	 * @covers \MediaWiki\Page\PageSelectQueryBuilder::wherePageIds
	 * @covers \MediaWiki\Page\PageSelectQueryBuilder::fetchPageRecordArray
	 */
	public function testFetchBatchOfPagesById() {
		$pageStore = $this->getPageStore();

		$recAA = $pageStore->getPageByName( NS_MAIN, 'AA' );
		$recAB = $pageStore->getPageByName( NS_MAIN, 'AB' );

		$recs = $pageStore->newSelectQueryBuilder()
			->wherePageIds( [] )
			->fetchPageRecordArray();

		$this->assertCount( 0, $recs );

		$recs = $pageStore->newSelectQueryBuilder()
			->wherePageIds( [ $recAA->getId(), $recAB->getId() ] )
			->fetchPageRecordArray();

		$this->assertCount( 2, $recs );
		$this->assertSame( 'AA', $recs[ $recAA->getId() ]->getDBkey() );
		$this->assertSame( 'AB', $recs[ $recAB->getId() ]->getDBkey() );
	}

	/**
	 * @covers \MediaWiki\Page\PageSelectQueryBuilder::wherePageIds
	 * @covers \MediaWiki\Page\PageSelectQueryBuilder::fetchPageRecord
	 */
	public function testFetchSinglePageById() {
		$pageStore = $this->getPageStore();

		$recAB = $pageStore->getPageByName( NS_MAIN, 'AB' );

		$rec = $pageStore->newSelectQueryBuilder()
			->wherePageIds( $recAB->getId() )
			->fetchPageRecord();

		$this->assertTrue( $recAB->isSamePageAs( $rec ) );

		$rec = $pageStore->newSelectQueryBuilder()
			->wherePageIds( 348529043 )
			->fetchPageRecord();

		$this->assertNull( $rec );
	}

	/**
	 * @covers \MediaWiki\Page\PageSelectQueryBuilder::whereTitles
	 * @covers \MediaWiki\Page\PageSelectQueryBuilder::fetchPageRecordArray
	 * @covers \MediaWiki\Page\PageSelectQueryBuilder::orderByPageId
	 */
	public function testFindBatchOfPageIdsByTitle() {
		$pageStore = $this->getPageStore();

		$recAA = $pageStore->getPageByName( NS_MAIN, 'AA' );
		$recAB = $pageStore->getPageByName( NS_MAIN, 'AB' );
		$recAC = $pageStore->getPageByName( NS_MAIN, 'BB' );

		$recs = $pageStore->newSelectQueryBuilder()
			->whereTitles( NS_FILE, [ 'AA', 'AB', 'BB' ] )
			->fetchPageIds();

		$this->assertCount( 0, $recs );

		$recs = $pageStore->newSelectQueryBuilder()
			->whereTitles( NS_MAIN, [ 'AA', 'AB', 'BB' ] )
			->orderByPageId( PageSelectQueryBuilder::SORT_DESC )
			->fetchPageIds();

		$expectedIds = [ $recAA->getId(), $recAB->getId(), $recAC->getId() ];
		sort( $expectedIds );
		$expectedIds = array_reverse( $expectedIds );

		$this->assertSame( $expectedIds, $recs );
	}

	/**
	 * @covers \MediaWiki\Page\PageSelectQueryBuilder::whereTitles
	 * @covers \MediaWiki\Page\PageSelectQueryBuilder::fetchPageRecord
	 */
	public function testFetchSinglePageByTitle() {
		$pageStore = $this->getPageStore();

		$recAB = $pageStore->getPageByName( NS_MAIN, 'AB' );

		$rec = $pageStore->newSelectQueryBuilder()
			->whereTitles( NS_MAIN, 'AB' )
			->fetchPageRecord();

		$this->assertTrue( $recAB->isSamePageAs( $rec ) );

		// The page should have ended up in the LinkCache
		$linkCache = $this->getServiceContainer()->getLinkCache();
		$this->assertSame( $rec->getId(), $linkCache->getGoodLinkID( $rec ) );

		$rec = $pageStore->newSelectQueryBuilder()
			->whereTitles( NS_TALK, 'AB' )
			->fetchPageRecord();

		$this->assertNull( $rec );
	}

	/**
	 * @covers \MediaWiki\Page\PageSelectQueryBuilder::whereNamespace
	 * @covers \MediaWiki\Page\PageSelectQueryBuilder::fetchPageRecordArray
	 */
	public function testFilterByNamespace() {
		$pageStore = $this->getPageStore();

		$recAA = $pageStore->getPageByName( NS_TALK, 'AA' );

		$recs = $pageStore->newSelectQueryBuilder()
			->whereNamespace( NS_TALK )
			->fetchPageRecordArray();

		$this->assertCount( 1, $recs );
		$this->assertSame( 'AA', $recs[ $recAA->getId() ]->getDBkey() );
	}

	/**
	 * @covers \MediaWiki\Page\PageSelectQueryBuilder::whereTitlePrefix
	 * @covers \MediaWiki\Page\PageSelectQueryBuilder::fetchPageRecords
	 * @covers \MediaWiki\Page\PageSelectQueryBuilder::orderByTitle
	 */
	public function testListPagesByPrefix() {
		$pageStore = $this->getPageStore();

		$recs = $pageStore->newSelectQueryBuilder()
			->whereTitlePrefix( NS_MAIN, 'A' )
			->orderByTitle( PageSelectQueryBuilder::SORT_DESC )
			->fetchPageRecords();

		$recs = iterator_to_array( $recs );

		$this->assertCount( 2, $recs );

		// descending order
		$this->assertSame( 'AB', $recs[0]->getDBkey() );
		$this->assertSame( 'AA', $recs[1]->getDBkey() );

		$recs = $pageStore->newSelectQueryBuilder()
			->whereTitlePrefix( NS_TALK, 'A' )
			->fetchPageRecords();

		$this->assertCount( 1, $recs );

		$recs = $pageStore->newSelectQueryBuilder()
			->whereTitlePrefix( NS_MAIN, 'XX' )
			->fetchPageRecords();

		$this->assertCount( 0, $recs );
	}

}
