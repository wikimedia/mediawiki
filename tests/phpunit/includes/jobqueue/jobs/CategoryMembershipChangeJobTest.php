<?php

/**
 * @covers CategoryMembershipChangeJob
 *
 * @group JobQueue
 * @group Database
 *
 * @licence GNU GPL v2+
 * @author Addshore
 */
class CategoryMembershipChangeJobTest extends MediaWikiTestCase {

	/**
	 * @var string[]
	 */
	private $titles = [
		'unused' => 'UTCatChangeJobPage-unused',
		'used' => 'UTCatChangeJobPage-used',
		'usage' => 'UTCatChangeJobPage-usage',
	];

	public function setUp() {
		parent::setUp();
		$this->setMwGlobals( 'wgRCWatchCategoryMembership', true );
		$this->setContentLang( 'qqx' );
	}

	public function addDBDataOnce() {
		parent::addDBDataOnce();

		$this->insertPage( $this->titles['used'], 'UT Content used' );
		$this->insertPage( $this->titles['unused'], 'UT Content unused' );
		$this->insertPage( $this->titles['usage'], '{{:' . $this->titles['used'] . '}}' );
	}

	private function runJobs() {
		JobQueueGroup::destroySingletons();
		$jobs = new RunJobs;
		$jobs->loadParamsAndArgs( null, [ 'quiet' => true ], null );
		$jobs->execute();
	}

	/**
	 * @param string $titleString title to edit
	 * @param string $text new page text
	 *
	 * @return int|null
	 */
	private function editPageText( $titleString, $text ) {
		$title = Title::newFromText( $titleString );
		$page = WikiPage::factory( $title );
		$editResult = $page->doEditContent(
			ContentHandler::makeContent( $text, $title ),
			__METHOD__
		);
		/** @var Revision $revision */
		$revision = $editResult->value['revision'];
		$this->runJobs();

		return $revision->getId();
	}

	/**
	 * @param int $revId
	 *
	 * @return RecentChange|null
	 */
	private function getCategorizeRecentChangeForRevId( $revId ) {
		return RecentChange::newFromConds(
			[
				'rc_type' => RC_CATEGORIZE,
				'rc_this_oldid' => $revId,
			],
			__METHOD__
		);
	}

	public function testRun_normalCategoryAddedAndRemovedOnUnusedPage() {
		$addedRevId = $this->editPageText( $this->titles['unused'], '[[Category:Normal]]' );
		$removedRevId = $this->editPageText( $this->titles['unused'], 'Blank' );

		$this->assertEquals(
			'(recentchanges-page-added-to-category: ' . $this->titles['unused'] . ', 0)',
			$this->getCategorizeRecentChangeForRevId( $addedRevId )->getAttribute( 'rc_comment' )
		);
		$this->assertEquals(
			'(recentchanges-page-removed-from-category: ' . $this->titles['unused'] . ', 0)',
			$this->getCategorizeRecentChangeForRevId( $removedRevId )->getAttribute( 'rc_comment' )
		);
	}

	public function testRun_includeOnlyCategoryAddedAndRemovedOnUnusedPage() {
		$addedRevId = $this->editPageText(
			$this->titles['unused'],
			'<includeonly>[[Category:IncludeOnly]]</includeonly>'
		);
		$removedRevId = $this->editPageText( $this->titles['unused'], 'Blank' );

		$this->assertNull( $this->getCategorizeRecentChangeForRevId( $addedRevId ) );
		$this->assertNull( $this->getCategorizeRecentChangeForRevId( $removedRevId ) );
	}

	public function testRun_noIncludeCategoryAddedAndRemovedOnUnusedPage() {
		$addedRevId = $this->editPageText(
			$this->titles['unused'],
			'<noinclude>[[Category:NoInclude]]</noinclude>'
		);
		$removedRevId = $this->editPageText( $this->titles['unused'], 'Blank' );

		$this->assertEquals(
			'[[:' . $this->titles['unused'] . ']] added to category',
			$this->getCategorizeRecentChangeForRevId( $addedRevId )->getAttribute( 'rc_comment' )
		);
		$this->assertEquals(
			'[[:' . $this->titles['unused']. ']] removed from category',
			$this->getCategorizeRecentChangeForRevId( $removedRevId )->getAttribute( 'rc_comment' )
		);
	}

	public function testRun_normalCategoryAddedAndRemovedOnUsedPage() {
		$addedRevId = $this->editPageText( $this->titles['used'], '[[Category:Normal]]' );
		$removedRevId = $this->editPageText( $this->titles['used'], 'Blank' );

		$this->assertEquals(
			'[[:' . $this->titles['used'] . ']] and one page added to category',
			$this->getCategorizeRecentChangeForRevId( $addedRevId )->getAttribute( 'rc_comment' )
		);
		$this->assertEquals(
			'[[:' . $this->titles['used'] . ']] and one page removed from category',
			$this->getCategorizeRecentChangeForRevId( $removedRevId )->getAttribute( 'rc_comment' )
		);
	}

	public function testRun_includeOnlyCategoryAddedAndRemovedOnUsedPage() {
		$addedRevId = $this->editPageText(
			$this->titles['used'],
			'<includeonly>[[Category:IncludeOnly]]</includeonly>'
		);
		$removedRevId = $this->editPageText( $this->titles['used'], 'Blank' );

		$this->assertEquals(
			'one page transcluding [[:' . $this->titles['used'] . ']] added to category',
			$this->getCategorizeRecentChangeForRevId( $addedRevId )->getAttribute( 'rc_comment' )
		);
		$this->assertEquals(
			'one page transcluding [[:' . $this->titles['used']. ']] removed from category',
			$this->getCategorizeRecentChangeForRevId( $removedRevId )->getAttribute( 'rc_comment' )
		);
	}

	public function testRun_noIncludeCategoryAddedAndRemovedOnUsedPage() {
		$addedRevId = $this->editPageText(
			$this->titles['used'],
			'<noinclude>[[Category:NoInclude]]</noinclude>'
		);
		$removedRevId = $this->editPageText( $this->titles['used'], 'Blank' );

		$this->assertEquals(
			'[[:' . $this->titles['used'] . ']] added to category',
			$this->getCategorizeRecentChangeForRevId( $addedRevId )->getAttribute( 'rc_comment' )
		);
		$this->assertEquals(
			'[[:' . $this->titles['used']. ']] removed from category',
			$this->getCategorizeRecentChangeForRevId( $removedRevId )->getAttribute( 'rc_comment' )
		);
	}

}
