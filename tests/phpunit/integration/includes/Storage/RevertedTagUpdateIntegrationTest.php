<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Json\FormatJson;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\WikiPage;
use MediaWiki\RecentChanges\RecentChange;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Storage\RevertedTagUpdate
 * @covers \MediaWiki\JobQueue\Jobs\RevertedTagUpdateJob
 * @covers \MediaWiki\Storage\RevertedTagUpdateManager
 *
 * @group Database
 * @group medium
 * @see RevertedTagUpdateTest for non-DB tests
 */
class RevertedTagUpdateIntegrationTest extends MediaWikiIntegrationTestCase {

	/**
	 * This test ensures the update is not performed at the end of the web request, but
	 * enqueued as a job for later execution instead.
	 *
	 * The reverting user here has autopatrol rights, so the update should be enqueued
	 * immediately.
	 */
	public function testWithJobQueue() {
		$num = 5;

		$page = $this->getExistingTestPage();
		$revisionIds = $this->setupEditsOnPage( $page, $num );

		// Make a manual revert to revision with content '0'
		// The user HAS the 'autopatrol' right
		$revertRevId = $this->editPage(
			$page,
			'0',
			'',
			NS_MAIN,
			$this->getTestSysop()->getUser()
		)->value['revision-record']->getId();
		$revertedRevs = array_slice( $revisionIds, 1 );

		DeferredUpdates::doUpdates();

		// the tags should not have been populated yet
		$this->verifyNoRevertedTags( $revertedRevs );

		// run the job
		$this->runJobs( [], [
			'type' => 'revertedTagUpdate'
		] );

		// now the tags should be populated
		$this->verifyRevertedTags( $revertedRevs, $revertRevId );
	}

	/**
	 * In this scenario, only the patrol mechanism is used for delaying the execution of
	 * the RevertedTagUpdate.
	 */
	public function testDelayedJobExecutionWithPatrol() {
		$num = 5;

		$page = $this->getExistingTestPage();
		$revisionIds = $this->setupEditsOnPage( $page, $num );

		// Make a manual revert to revision with content '0'
		// The user DOES NOT have the 'autopatrol' right
		$revertRevId = $this->editPage(
			$page,
			'0',
			'',
			NS_MAIN,
			$this->getTestUser()->getUser()
		)->value['revision-record']->getId();
		$revertedRevs = array_slice( $revisionIds, 1 );

		DeferredUpdates::doUpdates();

		// the tags should not have been populated yet
		$this->verifyNoRevertedTags( $revertedRevs );

		// try to run the job
		$this->runJobs( [ 'numJobs' => 0 ], [
			'type' => 'revertedTagUpdate'
		] );

		// the tags still should not be present as the edit is pending approval
		$this->verifyNoRevertedTags( $revertedRevs );

		// approve the edit – this should enqueue the job
		$rc = RecentChange::newFromConds( [ 'rc_this_oldid' => $revertRevId ] );
		$rc->reallyMarkPatrolled();

		// run the job
		$this->runJobs( [ 'numJobs' => 1 ], [
			'type' => 'revertedTagUpdate'
		] );

		// now the tags should be populated
		$this->verifyRevertedTags( $revertedRevs, $revertRevId );
	}

	/**
	 * Ensure the update is not performed even after the edit is approved if it
	 * was reverted in the meantime.
	 */
	public function testNoJobExecutionWhenRevertIsReverted() {
		$num = 5;

		$page = $this->getExistingTestPage();
		$revisionIds = $this->setupEditsOnPage( $page, $num );

		// Make a manual revert to revision with content '0'
		// The user DOES NOT have the 'autopatrol' right
		$revertId1 = $this->editPage(
			$page,
			'0',
			'',
			NS_MAIN,
			$this->getTestUser()->getUser()
		)->value['revision-record']->getId();
		$revertedRevs = array_slice( $revisionIds, 1 );

		DeferredUpdates::doUpdates();
		$this->runJobs( [ 'numJobs' => 0 ], [
			'type' => 'revertedTagUpdate'
		] );

		// the tags should not be present as the edit is pending approval
		$this->verifyNoRevertedTags( $revertedRevs );

		// now a sysop reverts the revert made by a regular user
		$revertId2 = $this->editPage(
			$page,
			'5',
			'',
			NS_MAIN,
			$this->getTestSysop()->getUser()
		)->value['revision-record']->getId();
		DeferredUpdates::doUpdates();
		$this->runJobs( [], [
			'type' => 'revertedTagUpdate'
		] );
		$this->verifyRevertedTags( [ $revertId1 ], $revertId2 );

		// approve the edit – this should enqueue the job
		$rc = RecentChange::newFromConds( [ 'rc_this_oldid' => $revertId1 ] );
		$rc->reallyMarkPatrolled();

		// Run the job.
		// The job should notice that the revert is reverted and refuse to perform
		// the update.
		$this->runJobs( [], [
			'type' => 'revertedTagUpdate'
		] );

		// the tags should not be populated
		$this->verifyNoRevertedTags( $revertedRevs );
	}

	/**
	 * Ensure the patrolling-related job delay mechanism is not used when patrolling
	 * is disabled.
	 */
	public function testNoDelayedJobExecutionWhenPatrollingIsDisabled() {
		$num = 5;

		// disable patrolling
		$this->overrideConfigValues( [ MainConfigNames::UseRCPatrol => false ] );

		$page = $this->getExistingTestPage();
		$revisionIds = $this->setupEditsOnPage( $page, $num );

		// Make a manual revert to revision with content '0'
		// The user DOES NOT have the 'autopatrol' right, but that should not matter here
		$revertRevId = $this->editPage(
			$page,
			'0',
			'',
			NS_MAIN,
			$this->getTestUser()->getUser()
		)->value['revision-record']->getId();
		$revertedRevs = array_slice( $revisionIds, 1 );

		DeferredUpdates::doUpdates();

		// the tags should not have been populated yet
		$this->verifyNoRevertedTags( $revertedRevs );

		// run the job
		$this->runJobs( [], [
			'type' => 'revertedTagUpdate'
		] );

		// now the tags should be populated
		$this->verifyRevertedTags( $revertedRevs, $revertRevId );
	}

	/**
	 * In this scenario an extension hook prevents the update from executing.
	 * We also check if the hook is able to override the decision made by the patrol
	 * subsystem.
	 * The update is then re-enqueued when the edit is approved.
	 */
	public function testDelayedJobExecutionWithHook() {
		$num = 5;

		$page = $this->getExistingTestPage();
		$revisionIds = $this->setupEditsOnPage( $page, $num );

		$this->setTemporaryHook(
			'BeforeRevertedTagUpdate',
			function (
				$wikiPage,
				$user,
				$summary,
				$flags,
				$revisionRecord,
				$editResult,
				&$approved
			) {
				$this->assertTrue(
					$approved,
					'$approved parameter of BeforeRevertedTagUpdate'
				);
				$approved = false;
			}
		);

		// Make a manual revert to revision with content '0'
		// The user HAS the 'autopatrol' right, but that should be vetoed by the hook
		$revertRevId = $this->editPage(
			$page,
			'0',
			'',
			NS_MAIN,
			$this->getTestSysop()->getUser()
		)->value['revision-record']->getId();
		$revertedRevs = array_slice( $revisionIds, 1 );

		DeferredUpdates::doUpdates();

		// the tags should not have been populated yet
		$this->verifyNoRevertedTags( $revertedRevs );

		// try to run the job
		$this->runJobs( [ 'numJobs' => 0 ], [
			'type' => 'revertedTagUpdate'
		] );

		// the tags still should not be present as the edit is pending approval
		$this->verifyNoRevertedTags( $revertedRevs );

		// simulate the approval of the edit
		$manager = $this->getServiceContainer()->getRevertedTagUpdateManager();
		$manager->approveRevertedTagForRevision( $revertRevId );

		// run the job
		$this->runJobs( [], [
			'type' => 'revertedTagUpdate'
		] );

		// now the tags should be populated
		$this->verifyRevertedTags( $revertedRevs, $revertRevId );
	}

	/**
	 * Here the patrol subsystem says the edit is not approved, but an extension hook
	 * decides to run the update immediately anyway.
	 */
	public function testNoDelayedJobExecutionWithHook() {
		$num = 5;

		$page = $this->getExistingTestPage();
		$revisionIds = $this->setupEditsOnPage( $page, $num );

		$this->setTemporaryHook(
			'BeforeRevertedTagUpdate',
			function (
				$wikiPage,
				$user,
				$summary,
				$flags,
				$revisionRecord,
				$editResult,
				&$approved
			) {
				$this->assertFalse(
					$approved,
					'$approved parameter of BeforeRevertedTagUpdate'
				);
				$approved = true;
			}
		);

		// Make a manual revert to revision with content '0'
		// The user DOES NOT have the 'autopatrol' right, but that should be
		// overridden by the hook.
		$revertRevId = $this->editPage(
			$page,
			'0',
			'',
			NS_MAIN,
			$this->getTestUser()->getUser()
		)->value['revision-record']->getId();
		$revertedRevs = array_slice( $revisionIds, 1 );

		DeferredUpdates::doUpdates();

		// the tags should not have been populated yet
		$this->verifyNoRevertedTags( $revertedRevs );

		// run the job
		$this->runJobs( [], [
			'type' => 'revertedTagUpdate'
		] );

		// now the tags should be populated
		$this->verifyRevertedTags( $revertedRevs, $revertRevId );
	}

	/**
	 * Sets up a set number of edits on a page.
	 *
	 * @param WikiPage $page the page to set up
	 * @param int $editCount
	 *
	 * @return array
	 */
	private function setupEditsOnPage( WikiPage $page, int $editCount ): array {
		$revIds = [];
		for ( $i = 0; $i <= $editCount; $i++ ) {
			$revIds[] = $this->editPage( $page, strval( $i ) )
				->value['revision-record']->getId();
		}

		return $revIds;
	}

	/**
	 * Ensures that the reverted tag is not set for given revisions.
	 */
	private function verifyNoRevertedTags( array $revisionIds ) {
		$dbw = $this->getDb();
		foreach ( $revisionIds as $revisionId ) {
			$this->assertNotContains(
				'mw-reverted',
				$this->getServiceContainer()->getChangeTagsStore()->getTags( $dbw, null, $revisionId ),
				'ChangeTagsStore->getTags()'
			);
		}
	}

	/**
	 * Checks if the provided revisions have their reverted tag set properly.
	 *
	 * @param array $revisionIds
	 * @param int $revertRevId
	 */
	private function verifyRevertedTags(
		array $revisionIds,
		int $revertRevId
	) {
		$dbw = $this->getDb();
		// for each reverted revision
		foreach ( $revisionIds as $revisionId ) {
			$this->assertContains(
				'mw-reverted',
				$this->getServiceContainer()->getChangeTagsStore()->getTags( $dbw, null, $revisionId ),
				'ChangeTagsStore->getTags()'
			);

			// do basic checks for the ct_params field
			$extraParams = $dbw->newSelectQueryBuilder()
				->select( 'ct_params' )
				->from( 'change_tag' )
				->join( 'change_tag_def', null, 'ct_tag_id = ctd_id' )
				->where( [ 'ct_rev_id' => $revisionId, 'ctd_name' => 'mw-reverted' ] )
				->caller( __METHOD__ )->fetchField();
			$this->assertNotEmpty( $extraParams, 'change_tag.ct_params' );
			$this->assertJson( $extraParams, 'change_tag.ct_params' );
			$parsedParams = FormatJson::decode( $extraParams, true );
			$this->assertArraySubmapSame(
				[ 'revertId' => $revertRevId ],
				$parsedParams,
				'change_tag.ct_params'
			);
		}
	}
}
