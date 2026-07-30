<?php

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Specials\SpecialPageTestBase;
use MediaWiki\Title\Title;
use Wikimedia\Parsoid\Ext\DOMUtils;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Specials\SpecialMergeHistory
 * @covers \MediaWiki\Specials\Pager\MergeHistoryPager
 * @group Database
 */
class SpecialMergeHistoryTest extends SpecialPageTestBase {

	public function testExecuteWhenUserLacksRight(): void {
		$this->expectException( PermissionsError::class );
		$this->executeSpecialPage();
	}

	public function testExecuteForView(): void {
		[ $html ] = $this->executeSpecialPage(
			'',
			null,
			null,
			$this->getTestSysop()->getAuthority()
		);

		$this->assertStringContainsString( '(mergehistory-header)', $html );
		$this->assertStringContainsString( '(mergehistory-from)', $html );
		$this->assertStringContainsString( '(mergehistory-into)', $html );
		$this->assertStringContainsString( '(mergehistory-go)', $html );
	}

	public function testExecuteForEmptyTargetAndDest(): void {
		[ $html ] = $this->executeSpecialPage(
			'',
			new FauxRequest( [
				'submitted' => '1',
				'target' => '',
				'dest' => '',
			] ),
			null,
			$this->getTestSysop()->getAuthority()
		);

		$this->assertStringContainsString( '(mergehistory-invalid-source)', $html );
		$this->assertStringContainsString( '(mergehistory-invalid-destination)', $html );
	}

	public function testExecuteForNonExistingTargetAndDest(): void {
		[ $html ] = $this->executeSpecialPage(
			'',
			new FauxRequest( [
				'submitted' => '1',
				'target' => 'NonExistingTestPage1',
				'dest' => 'NonExistingTestPage2',
			] ),
			null,
			$this->getTestSysop()->getAuthority()
		);

		$this->assertStringContainsString( '(mergehistory-no-source: NonExistingTestPage1)', $html );
		$this->assertStringContainsString( '(mergehistory-no-destination: NonExistingTestPage2)', $html );
	}

	public function testExecuteForSubmittedPagesShowsMergeableHistory(): void {
		[ 'source' => $source, 'destination' => $destination ] = $this->createMergeablePages();

		RequestContext::getMain()->setLanguage( 'qqx' );
		[ $html ] = $this->executeSpecialPage(
			'',
			new FauxRequest( [
				'submitted' => '1',
				'target' => Title::newFromPageIdentity( $source )->getPrefixedText(),
				'dest' => Title::newFromPageIdentity( $destination )->getPrefixedText(),
			] ),
			null,
			$this->getTestSysop()->getAuthority()
		);
		$doc = DOMUtils::parseHTML( $html );

		$this->assertStringContainsString( '(mergehistory-list)', $html );
		$this->assertStringContainsString( '(mergehistory-submit)', $html );
		$this->assertStringContainsString( '(mergehistory-reason)', $html );

		$mergeHistoryList = $this->assertSelectorMatchesOneElementInNode( $doc, '#mw-mergehistory-list' );

		$revisionRow = $this->assertSelectorMatchesOneElementInNode( $mergeHistoryList, 'li' );
		$this->assertStringContainsString( '(mergehistory-revisionrow', DOMCompat::getInnerHTML( $revisionRow ) );

		$editSummaryCommentHtml = $this->assertSelectorMatchesOneElementInNode( $revisionRow, '.comment', true );
		$this->assertStringContainsString( 'Source page edit summary', $editSummaryCommentHtml );

		$this->assertStringContainsString( '(mergelog)', $html );
		$logEmptyMessage = $this->assertSelectorMatchesOneElementInNode( $doc, '.mw-warning-logempty', true );
		$this->assertStringContainsString( '(logempty)', $logEmptyMessage );
	}

	public function testExecuteForSubmitMergesHistory(): void {
		[ 'source' => $source, 'destination' => $destination ] = $this->createMergeablePages();

		[ $html ] = $this->executeSpecialPage(
			'',
			new FauxRequest( [
				'action' => 'submit',
				'targetID' => (string)$source->getId(),
				'destID' => (string)$destination->getId(),
				'target' => Title::newFromPageIdentity( $source )->getPrefixedText(),
				'dest' => Title::newFromPageIdentity( $destination )->getPrefixedText(),
				'wpComment' => 'Test merge',
			], true ),
			null,
			$this->getTestSysop()->getAuthority()
		);

		$this->assertStringContainsString( '(mergehistory-done:', $html );

		$revisionStore = $this->getServiceContainer()->getRevisionStore();
		$this->assertSame(
			2,
			$revisionStore->countRevisionsByPageId( $this->getDb(), $destination->getId() )
		);

		$redirect = $this->getServiceContainer()->getRedirectLookup()
			->getRedirectTarget( Title::newFromPageIdentity( $source ) );
		$this->assertNotNull( $redirect );
		$this->assertSame(
			Title::newFromPageIdentity( $destination )->getPrefixedText(),
			$redirect->getText()
		);
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'MergeHistory' );
	}

	/** @return PageIdentity[] */
	private function createMergeablePages(): array {
		ConvertibleTimestamp::setFakeTime( '20260102030405', 1 );

		$sourceEditStatus = $this->editPage(
			'SpecialMergeHistoryTest/Source',
			'Source page content',
			'Source page edit summary'
		);
		$this->assertStatusGood( $sourceEditStatus );
		$destinationEditStatus = $this->editPage(
			'SpecialMergeHistoryTest/Destination',
			'Destination page content',
			'Destination page edit summary'
		);
		$this->assertStatusGood( $destinationEditStatus );

		ConvertibleTimestamp::setFakeTime( false );

		return [
			'source' => $sourceEditStatus->getNewRevision()->getPage(),
			'destination' => $destinationEditStatus->getNewRevision()->getPage(),
		];
	}
}
