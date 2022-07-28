<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Revision\RevisionRecord;

/**
 * @covers CategoryMembershipChangeJob
 *
 * @group JobQueue
 * @group Database
 *
 * @license GPL-2.0-or-later
 * @author Addshore
 */
class CategoryMembershipChangeJobTest extends MediaWikiIntegrationTestCase {

	private const TITLE_STRING = 'UTCatChangeJobPage';

	/**
	 * @var Title
	 */
	private $title;

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValue( MainConfigNames::RCWatchCategoryMembership, true );
		$this->setContentLang( 'qqx' );
	}

	public function addDBData() {
		parent::addDBData();
		$insertResult = $this->insertPage( self::TITLE_STRING, 'UT Content' );
		$this->title = $insertResult['title'];
	}

	/**
	 * @param string $text new page text
	 *
	 * @return int|null
	 */
	private function editPageText( $text ) {
		$page = WikiPage::factory( $this->title );
		$editResult = $page->doUserEditContent(
			ContentHandler::makeContent( $text, $this->title ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		);
		/** @var RevisionRecord $revisionRecord */
		$revisionRecord = $editResult->value['revision-record'];
		$this->runJobs();

		return $revisionRecord->getId();
	}

	/**
	 * @param int $revId
	 *
	 * @return RecentChange|null
	 */
	private function getCategorizeRecentChangeForRevId( $revId ) {
		$rc = RecentChange::newFromConds(
			[
				'rc_type' => RC_CATEGORIZE,
				'rc_this_oldid' => $revId,
			],
			__METHOD__
		);

		$this->assertNotNull( $rc, 'rev_id = ' . $revId );
		return $rc;
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

	public function testJobSpecRemovesDuplicates() {
		$jobSpec = CategoryMembershipChangeJob::newSpec( $this->title, MWTimestamp::now() );
		$job = new CategoryMembershipChangeJob(
			$this->title,
			$jobSpec->getParams()
		);
		$this->assertTrue( $job->ignoreDuplicates() );
		$this->assertTrue( $jobSpec->ignoreDuplicates() );
		$this->assertEquals( $job->getDeduplicationInfo(), $jobSpec->getDeduplicationInfo() );
	}

	public function testJobSpecDeduplicationIgnoresRevTimestamp() {
		$jobSpec1 = CategoryMembershipChangeJob::newSpec( $this->title, '20191008204617' );
		$jobSpec2 = CategoryMembershipChangeJob::newSpec( $this->title, '20201008204617' );
		$this->assertArrayEquals( $jobSpec1->getDeduplicationInfo(), $jobSpec2->getDeduplicationInfo() );
	}
}
