<?php
namespace MediaWiki\Tests\RecentChanges;

use MediaWiki\MainConfigNames;
use MediaWiki\RecentChanges\CategoryMembershipChangeJob;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\Title;
use MediaWiki\Utils\MWTimestamp;
use MediaWikiIntegrationTestCase;

/**
 * @group Database
 * @covers \MediaWiki\RecentChanges\CategoryMembershipChangeJob
 *
 * @license GPL-2.0-or-later
 * @author Addshore
 */
class CategoryMembershipChangeJobTest extends MediaWikiIntegrationTestCase {

	private const TITLE_STRING = 'UTCatChangeJobPage';

	private Title $title;

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
	 * @param string $text New page content
	 */
	private function editPageText( string $text ): ?int {
		$editResult = $this->editPage(
			$this->title,
			$text,
			__METHOD__,
			NS_MAIN,
			$this->getTestSysop()->getAuthority()
		);
		/** @var RevisionRecord $revisionRecord */
		$revisionRecord = $editResult->getNewRevision();
		$this->runJobs( [ 'minJobs' => 1 ], [ 'type' => 'categoryMembershipChange' ] );

		return $revisionRecord->getId();
	}

	private function getCategorizeRecentChangeForRevId( int $revId ): ?RecentChange {
		$rc = $this->getServiceContainer()->getRecentChangeStore()->getRecentChangeByConds(
			[
				'rc_source' => RecentChange::SRC_CATEGORIZE,
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
