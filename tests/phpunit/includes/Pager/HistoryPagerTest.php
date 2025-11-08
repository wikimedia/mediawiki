<?php

use MediaWiki\Actions\HistoryAction;
use MediaWiki\Context\RequestContext;
use MediaWiki\Output\OutputPage;
use MediaWiki\Page\Article;
use MediaWiki\Page\WikiPage;
use MediaWiki\Pager\HistoryPager;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\TestingAccessWrapper;

/**
 * Test class for HistoryPager methods.
 *
 * @group Pager
 * @group Database
 */
class HistoryPagerTest extends MediaWikiIntegrationTestCase {
	/**
	 * @param array $results for passing to FakeResultWrapper and deriving
	 *  RevisionRecords and formatted comments.
	 * @return HistoryPager
	 */
	private function getHistoryPager( array $results ) {
		$wikiPageMock = $this->createMock( WikiPage::class );
		$contextMock = $this->getMockBuilder( RequestContext::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'getRequest', 'getWikiPage', 'getTitle' ] )
			->getMock();
		$contextMock->method( 'getRequest' )->willReturn(
			new FauxRequest( [ 'offset' => '20251105123000' ] )
		);
		$title = Title::makeTitle( NS_MAIN, 'HistoryPagerTest' );
		$contextMock->method( 'getTitle' )->willReturn( $title );

		$contextMock->method( 'getWikiPage' )->willReturn( $wikiPageMock );
		$articleMock = $this->getMockBuilder( Article::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'getContext' ] )
			->getMock();
		$articleMock->method( 'getContext' )->willReturn( $contextMock );

		$actionMock = $this->getMockBuilder( HistoryAction::class )
			->setConstructorArgs( [
				$articleMock,
				$contextMock
			] )
			->getMock();
		$actionMock->method( 'getArticle' )->willReturn( $articleMock );
		$actionMock->message = [
			'cur' => 'cur',
			'last' => 'last',
			'tooltip-cur' => '',
			'tooltip-last' => '',
		];

		$outputMock = $this->getMockBuilder( OutputPage::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'wrapWikiMsg' ] )
			->getMock();

		$pager = $this->getMockBuilder( HistoryPager::class )
			->onlyMethods( [ 'reallyDoQuery', 'doBatchLookups',
				'getOutput' ] )
			->setConstructorArgs( [
				$actionMock
			] )
			->getMock();

		$pager->method( 'getOutput' )->willReturn( $outputMock );
		$pager->method( 'reallyDoQuery' )->willReturn(
			new FakeResultWrapper( $results )
		);

		// make all the methods in our mock public
		$pager = TestingAccessWrapper::newFromObject( $pager );
		// and update the private properties...
		$pager->formattedComments = array_map( static function ( $result ) {
			return 'dummy comment';
		}, $results );

		$pager->revisions = array_map( static function ( $result ) {
			$title = Title::makeTitle( NS_MAIN, 'HistoryPagerTest' );
			$r = new MutableRevisionRecord( $title );
			$r->setId( $result[ 'rev_id' ] );
			return $r;
		}, $results );

		return $pager;
	}

	/**
	 * @covers \MediaWiki\Pager\HistoryPager::__construct
	 */
	public function testQueryOffset() {
		$queryOffset = $this->getHistoryPager( [] )->getOffset();
		$dbTimestamp = $this->getDb()->timestamp( $queryOffset );

		$this->assertSame( $queryOffset, $dbTimestamp );
	}

	/**
	 * @covers \MediaWiki\Pager\HistoryPager::getBody
	 */
	public function testGetBodyEmpty() {
		$pager = $this->getHistoryPager( [] );
		$html = $pager->getBody();
		$this->assertStringContainsString( 'No matching revisions were found.', $html );
		$this->assertStringNotContainsString( '<h4', $html );
	}

	/**
	 * @covers \MediaWiki\Pager\HistoryPager::getBody
	 */
	public function testGetBodyOneHeading() {
		$pager = $this->getHistoryPager(
			[
				[
					'rev_page' => 'title',
					'ts_tags' => '',
					'rev_deleted' => false,
					'rev_minor_edit' => false,
					'rev_parent_id' => 1,
					'user_name' => 'Jdlrobson',
					'rev_id' => 2,
					'rev_comment_data' => '{}',
					'rev_comment_cid' => '1',
					'rev_comment_text' => 'Created page',
					'rev_timestamp' => '20220101001122',
				]
			]
		);
		$html = $pager->getBody();
		$this->assertStringContainsString( '<h4', $html );
	}

	/**
	 * @covers \MediaWiki\Pager\HistoryPager::getBody
	 */
	public function testGetBodyTwoHeading() {
		$pagerData = [
			'rev_page' => 'title',
			'rev_deleted' => false,
			'rev_minor_edit' => false,
			'ts_tags' => '',
			'rev_parent_id' => 1,
			'user_name' => 'Jdlrobson',
			'rev_comment_data' => '{}',
			'rev_comment_cid' => '1',
			'rev_comment_text' => 'Fixed typo',
		];
		$pager = $this->getHistoryPager(
			[
				$pagerData + [
					'rev_id' => 3,
					'rev_timestamp' => '20220301001122',
				],
				$pagerData + [
					'rev_id' => 2,
					'rev_timestamp' => '20220101001122',
				],
			]
		);
		$html = preg_replace( "/\n+/", '', $pager->getBody() );
		$firstHeader = '<h4 class="mw-index-pager-list-header-first mw-index-pager-list-header">1 March 2022</h4>'
			. '<ul class="mw-contributions-list">'
			. '<li data-mw-revid="3">';
		$secondHeader = '<h4 class="mw-index-pager-list-header">1 January 2022</h4>'
			. '<ul class="mw-contributions-list">'
			. '<li data-mw-revid="2">';

		// Check that the undo links are correct in the topmost displayed row (for rev_id=3).
		// This is tricky because the other rev number (in this example, '2') is magically
		// pulled from the next row, before we've processed that row.
		$this->assertStringContainsString( '&amp;undoafter=2&amp;undo=3', $html );

		$this->assertStringContainsString( $firstHeader, $html );
		$this->assertStringContainsString( $secondHeader, $html );
		$this->assertStringContainsString( '<section id="pagehistory"', $html );
	}

	/**
	 * @covers \MediaWiki\Pager\HistoryPager::getBody
	 */
	public function testGetBodyLastItem() {
		$pagerData = [
			'rev_page' => 'title',
			'rev_deleted' => false,
			'rev_minor_edit' => false,
			'ts_tags' => '',
			'rev_parent_id' => 1,
			'user_name' => 'Jdlrobson',
			'rev_comment_data' => '{}',
			'rev_comment_cid' => '1',
			'rev_comment_text' => 'Fixed typo',
		];
		$pager = $this->getHistoryPager(
			[
				$pagerData + [
					'rev_id' => 2,
					'rev_timestamp' => '20220301001111',
				],
				$pagerData + [
					'rev_id' => 3,
					'rev_timestamp' => '20220301001122',
				],
			]
		);
		$html = preg_replace( "/\n+/", '', $pager->getBody() );
		$this->assertSame( 1, substr_count( $html, '<h4' ),
			'There is exactly one header if the date is the same for all edits' );
		$this->assertSame( 1, substr_count( $html, '<ul' ),
			'There is exactly one list if the date is the same for all edits' );
	}
}
