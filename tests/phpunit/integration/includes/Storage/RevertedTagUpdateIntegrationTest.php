<?php

namespace MediaWiki\Tests\Storage;

use ChangeTags;
use DeferredUpdates;
use FormatJson;
use HashConfig;
use MediaWikiIntegrationTestCase;
use RecentChange;

/**
 * @covers \MediaWiki\Storage\RevertedTagUpdate
 * @covers \RevertedTagUpdateJob
 * @covers \MediaWiki\Storage\RevertedTagUpdateManager
 *
 * @group Database
 * @group medium
 * @see RevertedTagUpdateTest for non-DB tests
 */
class RevertedTagUpdateIntegrationTest extends MediaWikiIntegrationTestCase {
	protected function setUp(): void {
		parent::setUp();

		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[
				'page',
				'revision',
				'comment',
				'text',
				'content',
				'change_tag',
				'objectcache',
				'job'
			]
		);
	}

	/**
	 * This test ensures the update is not performed at the end of the web request, but
	 * enqueued as a job for later execution instead.
	 *
	 * The reverting user here has autopatrol rights, so the update should be enqueued
	 * immediately.
	 */
	public function testWithJobQueue() {
		$num = 5;

		$revisionIds = $this->setupEditsOnPage( $num );
		$pageTitle = $this->getExistingTestPage()->getTitle()->getDBkey();

		// Make a manual revert to revision with content '0'
		// The user HAS the 'autopatrol' right
		$revertRevId = $this->editPage(
			$pageTitle,
			'0',
			'',
			NS_MAIN,
			$this->getTestSysop()->getUser()
		)->value['revision-record']->getId();
		$revertedRevs = array_slice( $revisionIds, 1 );

		// ensure all deferred updates are ran / enqueued
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

		$revisionIds = $this->setupEditsOnPage( $num );
		$pageTitle = $this->getExistingTestPage()->getTitle()->getDBkey();

		// Make a manual revert to revision with content '0'
		// The user DOES NOT have the 'autopatrol' right
		$revertRevId = $this->editPage(
			$pageTitle,
			'0',
			'',
			NS_MAIN,
			$this->getTestUser()->getUser()
		)->value['revision-record']->getId();
		$revertedRevs = array_slice( $revisionIds, 1 );

		// ensure all deferred updates are ran / enqueued
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

		$revisionIds = $this->setupEditsOnPage( $num );
		$pageTitle = $this->getExistingTestPage()->getTitle()->getDBkey();

		// Make a manual revert to revision with content '0'
		// The user DOES NOT have the 'autopatrol' right
		$revertId1 = $this->editPage(
			$pageTitle,
			'0',
			'',
			NS_MAIN,
			$this->getTestUser()->getUser()
		)->value['revision-record']->getId();
		$revertedRevs = array_slice( $revisionIds, 1 );

		// ensure all deferred updates are ran and try to run the job
		DeferredUpdates::doUpdates();
		$this->runJobs( [ 'numJobs' => 0 ], [
			'type' => 'revertedTagUpdate'
		] );

		// the tags should not be present as the edit is pending approval
		$this->verifyNoRevertedTags( $revertedRevs );

		// now a sysop reverts the revert made by a regular user
		$revertId2 = $this->editPage(
			$pageTitle,
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
		$this->overrideMwServices( new HashConfig( [ 'UseRCPatrol' => false ] ) );

		$revisionIds = $this->setupEditsOnPage( $num );
		$pageTitle = $this->getExistingTestPage()->getTitle()->getDBkey();

		// Make a manual revert to revision with content '0'
		// The user DOES NOT have the 'autopatrol' right, but that should not matter here
		$revertRevId = $this->editPage(
			$pageTitle,
			'0',
			'',
			NS_MAIN,
			$this->getTestUser()->getUser()
		)->value['revision-record']->getId();
		$revertedRevs = array_slice( $revisionIds, 1 );

		// ensure all deferred updates are ran / enqueued
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

		$revisionIds = $this->setupEditsOnPage( $num );
		$pageTitle = $this->getExistingTestPage()->getTitle()->getDBkey();

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
			$pageTitle,
			'0',
			'',
			NS_MAIN,
			$this->getTestSysop()->getUser()
		)->value['revision-record']->getId();
		$revertedRevs = array_slice( $revisionIds, 1 );

		// ensure all deferred updates are ran / enqueued
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

		$revisionIds = $this->setupEditsOnPage( $num );
		$pageTitle = $this->getExistingTestPage()->getTitle()->getDBkey();

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
			$pageTitle,
			'0',
			'',
			NS_MAIN,
			$this->getTestUser()->getUser()
		)->value['revision-record']->getId();
		$revertedRevs = array_slice( $revisionIds, 1 );

		// ensure all deferred updates are ran / enqueued
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
	 * @param int $editCount
	 *
	 * @return array
	 */
	private function setupEditsOnPage( int $editCount ): array {
		$wikiPage = $this->getExistingTestPage();
		$pageTitle = $wikiPage->getTitle()->getDBkey();
		$revIds = [];
		for ( $i = 0; $i <= $editCount; $i++ ) {
			$revIds[] = $this->editPage( $pageTitle, strval( $i ) )
				->value['revision-record']->getId();
		}

		return $revIds;
	}

	/**
	 * Ensures that the reverted tag is not set for given revisions.
	 *
	 * @param array $revisionIds
	 */
	private function verifyNoRevertedTags( array $revisionIds ) {
		$dbw = wfGetDB( DB_PRIMARY );
		foreach ( $revisionIds as $revisionId ) {
			$this->assertNotContains(
				'mw-reverted',
				ChangeTags::getTags( $dbw, null, $revisionId ),
				'ChangeTags::getTags()'
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
		$dbw = wfGetDB( DB_PRIMARY );
		// for each reverted revision
		foreach ( $revisionIds as $revisionId ) {
			$this->assertContains(
				'mw-reverted',
				ChangeTags::getTags( $dbw, null, $revisionId ),
				'ChangeTags::getTags()'
			);

			// do basic checks for the ct_params field
			$extraParams = $dbw->selectField(
				[ 'change_tag', 'change_tag_def' ],
				'ct_params',
				[
					'ct_rev_id' => $revisionId,
					'ct_tag_id = ctd_id',
					'ctd_name' => 'mw-reverted'
				],
				__METHOD__
			);
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
