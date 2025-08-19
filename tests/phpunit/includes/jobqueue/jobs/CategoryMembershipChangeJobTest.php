<?php

use MediaWiki\JobQueue\Jobs\CategoryMembershipChangeJob;
use MediaWiki\MainConfigNames;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\Title;
use MediaWiki\Utils\MWTimestamp;

/**
 * @covers \MediaWiki\JobQueue\Jobs\CategoryMembershipChangeJob
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
		$editResult = $this->editPage(
			$this->title,
			$text,
			__METHOD__,
			NS_MAIN,
			$this->getTestSysop()->getAuthority()
		);
		/** @var RevisionRecord $revisionRecord */
		$revisionRecord = $editResult->getNewRevision();
		$this->runJobs();

		return $revisionRecord->getId();
	}

	/**
	 * @param int $revId
	 *
	 * @return RecentChange|null
	 */
	private function getCategorizeRecentChangeForRevId( $revId ) {
		$rc = $this->getServiceContainer()->getRecentChangeStore()->getRecentChangeByConds(
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
		$jobSpec = CategoryMembershipChangeJob::newSpec( $this->title, MWTimestamp::now(), false );
		$job = new CategoryMembershipChangeJob(
			$this->title,
			$jobSpec->getParams(),
			$this->getServiceContainer()->getRecentChangeStore()
		);
		$this->assertTrue( $job->ignoreDuplicates() );
		$this->assertTrue( $jobSpec->ignoreDuplicates() );
		$this->assertEquals( $job->getDeduplicationInfo(), $jobSpec->getDeduplicationInfo() );
	}

	public function testJobSpecDeduplicationIgnoresRevTimestamp() {
		$jobSpec1 = CategoryMembershipChangeJob::newSpec( $this->title, '20191008204617', false );
		$jobSpec2 = CategoryMembershipChangeJob::newSpec( $this->title, '20201008204617', false );
		$this->assertArrayEquals( $jobSpec1->getDeduplicationInfo(), $jobSpec2->getDeduplicationInfo() );
	}
}
