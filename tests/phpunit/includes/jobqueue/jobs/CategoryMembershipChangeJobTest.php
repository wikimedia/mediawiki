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

	const TITLE_STRING = 'UTCatChangeJobPage';

	/**
	 * @var Title
	 */
	private $title;

	public function setUp() {
		parent::setUp();
		$this->setMwGlobals( 'wgRCWatchCategoryMembership', true );
		$this->setContentLang( 'qqx' );
	}

	public function addDBDataOnce() {
		parent::addDBDataOnce();
		$insertResult = $this->insertPage( self::TITLE_STRING, 'UT Content' );
		$this->title = $insertResult['title'];
	}

	private function runJobs() {
		JobQueueGroup::destroySingletons();
		$jobs = new RunJobs;
		$jobs->loadParamsAndArgs( null, [ 'quiet' => true ], null );
		$jobs->execute();
	}

	/**
	 * @param string $text new page text
	 *
	 * @return int|null
	 */
	private function editPageText( $text ) {
		$page = WikiPage::factory( $this->title );
		$editResult = $page->doEditContent(
			ContentHandler::makeContent( $text, $this->title ),
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

	public function testRun_normalCategoryAddedAndRemoved() {
		$addedRevId = $this->editPageText( '[[Category:Normal]]' );
		$removedRevId = $this->editPageText( 'Blank' );

		$this->assertEquals(
			'(recentchanges-page-added-to-category: ' . self::TITLE_STRING . ')',
			$this->getCategorizeRecentChangeForRevId( $addedRevId )->getAttribute( 'rc_comment' )
		);
		$this->assertEquals(
			'(recentchanges-page-removed-from-category: ' . self::TITLE_STRING . ')',
			$this->getCategorizeRecentChangeForRevId( $removedRevId )->getAttribute( 'rc_comment' )
		);
	}

}
