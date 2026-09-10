<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\Page\Event\PageHistoryVisibilityChangedEvent;
use MediaWiki\Page\PageReference;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\RevisionDelete\RevisionDeleter;
use MediaWiki\Tests\ExpectCallbackTrait;
use MediaWiki\User\UserIdentity;
use PHPUnit\Framework\Assert;

/**
 * @covers \MediaWiki\RevisionDelete\RevDelRevisionList
 * @covers \MediaWiki\RevisionDelete\RevDelList
 * @group Database
 */
class RevDelRevisionListTest extends MediaWikiIntegrationTestCase {
	use ExpectCallbackTrait;

	private static function sort( array $array ): array {
		sort( $array );
		return $array;
	}

	public function testSetVisibility() {
		$context = RequestContext::getMain();
		$context->setUser( $this->getTestSysop()->getUser() );

		$page = $this->getExistingTestPage();
		$rev1 = $page->getLatest();
		$secondRevUser = $this->getMutableTestUser()->getUser();
		$rev2 = $this->editPage( $page, 'new content', '', NS_MAIN, $secondRevUser )->getNewRevision()->getId();
		$thirdRevUser = $this->getMutableTestUser()->getUser();
		$rev3 = $this->editPage( $page, 'newer content', '', NS_MAIN, $thirdRevUser )->getNewRevision()->getId();

		// flush
		$this->runDeferredUpdates();

		$listenerCall = 1;

		$this->expectHook( 'ArticleRevisionVisibilitySet', 2 );
		$this->expectDomainEvent(
			PageHistoryVisibilityChangedEvent::TYPE, 2,
			static function ( PageHistoryVisibilityChangedEvent $event )
			use ( &$listenerCall, $page, $rev1, $rev2, $rev3 ) {
				Assert::assertSame( $page->getId(), $event->getPageId() );
				Assert::assertTrue( $page->isSamePageAs( $event->getPage() ) );

				$delNone = 0;
				$delText = RevisionRecord::DELETED_TEXT;
				$delUser = RevisionRecord::DELETED_USER | RevisionRecord::DELETED_RESTRICTED;

				switch ( $listenerCall ) {
					case 1: // for updating rev 1 and 2 /////////////
						Assert::assertSame( 'test 1', $event->getReason() );

						Assert::assertFalse( $event->isSuppressed(), 'isSuppressed' );

						Assert::assertFalse(
							$event->wasCurrentRevisionAffected(),
							'wasCurrentRevisionAffected'
						);
						$affectedRevisionIds = $event->getAffectedRevisionIDs();
						Assert::assertEquals(
							[ $rev1, $rev2, ],
							self::sort( $affectedRevisionIds ),
							'getAffectedRevisionIDs'
						);

						Assert::assertNotContains(
							$event->getLatestRevisionId(),
							$affectedRevisionIds,
							"Current revision should not be in affected rev ids " .
							"as visibility of current revision has not been changed."
						);

						Assert::assertSame( $delText, $event->getBitsSet() );
						Assert::assertSame( $delNone, $event->getBitsUnset() );

						Assert::assertSame( $delNone, $event->getVisibilityBefore( $rev1 ) );
						Assert::assertSame( $delNone, $event->getVisibilityBefore( $rev2 ) );

						Assert::assertSame( $delText, $event->getVisibilityAfter( $rev1 ) );
						Assert::assertSame( $delText, $event->getVisibilityAfter( $rev2 ) );

						break;

					case 2: // for updating rev 2 and 3 /////////////
						Assert::assertSame( 'test 2', $event->getReason() );

						Assert::assertTrue( $event->isSuppressed(), 'isSuppressed' );

						Assert::assertTrue(
							$event->wasCurrentRevisionAffected(),
							'wasCurrentRevisionAffected'
						);
						Assert::assertSame(
							$delNone,
							$event->getCurrentRevisionVisibilityBefore(),
							'getCurrentRevisionVisibilityBefore'
						);
						Assert::assertSame(
							$delUser,
							$event->getCurrentRevisionVisibilityAfter(),
							'getCurrentRevisionVisibilityAfter'
						);

						$affectedRevisionIds = $event->getAffectedRevisionIDs();
						Assert::assertEquals(
							[ $rev2, $rev3, ],
							self::sort( $affectedRevisionIds ),
							'getAffectedRevisionIDs'
						);

						Assert::assertContains(
							$event->getLatestRevisionId(),
							$affectedRevisionIds,
							"Current revision should be in affected rev ids " .
							"as visibility of current revision has been changed."
						);

						Assert::assertSame( $delUser, $event->getBitsSet() );
						Assert::assertSame( $delText, $event->getBitsUnset() );

						Assert::assertSame( $delText, $event->getVisibilityBefore( $rev2 ) );
						Assert::assertSame( $delNone, $event->getVisibilityBefore( $rev3 ) );

						Assert::assertSame( $delUser, $event->getVisibilityAfter( $rev2 ) );
						Assert::assertSame( $delUser, $event->getVisibilityAfter( $rev3 ) );

						break;
				}

				$listenerCall++;
			}
		);

		// Suppress text of revisions 1 and 2 /////////////////////////////////
		$visibility = [ RevisionRecord::DELETED_TEXT => 1 ];
		$ids = [ $rev1, $rev2 ];

		$deleter = RevisionDeleter::createList( 'revision', $context, $page, $ids );
		$params = [
			'value' => $visibility,
			'comment' => 'test 1',
			'tags' => [ 'test' ]
		];
		$status = $deleter->setVisibility( $params );
		$this->assertStatusOK( $status );
		$this->runDeferredUpdates();
		$this->assertOneLogWithTypeExists(
			'delete',
			$page,
			'test 1',
			$context->getUser(),
			$ids,
			0,
			RevisionRecord::DELETED_TEXT,
			// Creating a page using ::getExistingTestPage uses the default sysop user
			[ $this->getTestSysop()->getUser()->getActorId(), $secondRevUser->getActorId() ]
		);

		$this->newSelectQueryBuilder()
			->select( [ 'rev_id', 'rev_deleted' ] )
			->from( 'revision' )
			->where( [ 'rev_id' => [ $rev1, $rev2, $rev3 ] ] )
			->caller( __METHOD__ )
			->assertResultSet( [
				[ $rev1, RevisionRecord::DELETED_TEXT ],
				[ $rev2, RevisionRecord::DELETED_TEXT ],
				[ $rev3, 0 ],
			] );

		// Suppress text of revisions 2 and 3 /////////////////////////////////
		$visibility = [
			// 1 = se, 0 = unset, -1 = keep
			RevisionRecord::DELETED_TEXT => 0,
			RevisionRecord::DELETED_USER => 1,
			RevisionRecord::DELETED_RESTRICTED => 1,
		];

		$ids = [ $rev2, $rev3 ];

		$deleter = RevisionDeleter::createList( 'revision', $context, $page, $ids );

		$params = [
			'value' => $visibility,
			'comment' => 'test 2',
			'tags' => [ 'test' ]
		];
		$status = $deleter->setVisibility( $params );
		$this->assertStatusOK( $status );
		$this->runDeferredUpdates();
		$this->assertOneLogWithTypeExists(
			'suppress',
			$page,
			'test 2',
			$context->getUser(),
			$ids,
			RevisionRecord::DELETED_TEXT,
			RevisionRecord::DELETED_USER | RevisionRecord::DELETED_RESTRICTED,
			[ $secondRevUser->getActorId(), $thirdRevUser->getActorId() ]
		);

		$this->newSelectQueryBuilder()
			->select( [ 'rev_id', 'rev_deleted' ] )
			->from( 'revision' )
			->where( [ 'rev_id' => [ $rev1, $rev2, $rev3 ] ] )
			->caller( __METHOD__ )
			->assertResultSet( [
				[ $rev1, RevisionRecord::DELETED_TEXT ],
				[ $rev2, RevisionRecord::DELETED_USER | RevisionRecord::DELETED_RESTRICTED ],
				[ $rev3, RevisionRecord::DELETED_USER | RevisionRecord::DELETED_RESTRICTED ],
			] );
	}

	private function assertOneLogWithTypeExists(
		string $expectedLogType,
		PageReference $expectedTitle,
		string $expectedComment,
		UserIdentity $expectedPerformer,
		array $expectedRevIds,
		int $oldVisibilityBits,
		int $newVisibilityBits,
		array $expectedAuthorActors
	): void {
		$actualLogIds = $this->newSelectQueryBuilder()
			->select( 'log_id' )
			->from( 'logging' )
			->where( [ 'log_type' => $expectedLogType, 'log_action' => 'revision' ] )
			->caller( __METHOD__ )
			->fetchFieldValues();
		$this->assertCount(
			1,
			$actualLogIds,
			"Expected one log with the type $expectedLogType and action revision"
		);
		$actualLogId = $actualLogIds[0];

		$this->newSelectQueryBuilder()
			->select( [ 'log_title', 'log_namespace', 'comment_text', 'actor_name' ] )
			->from( 'logging' )
			->join( 'comment', null, 'comment_id = log_comment_id' )
			->join( 'actor', null, 'actor_id = log_actor' )
			->where( [ 'log_id' => $actualLogId ] )
			->caller( __METHOD__ )
			->assertRowValue( [
				$expectedTitle->getDBkey(),
				$expectedTitle->getNamespace(),
				$expectedComment,
				$expectedPerformer->getName()
			] );

		$actualLogParams = $this->newSelectQueryBuilder()
			->select( 'log_params' )
			->from( 'logging' )
			->where( [ 'log_id' => $actualLogId ] )
			->caller( __METHOD__ )
			->fetchField();
		$actualLogParams = LogEntryBase::extractParams( $actualLogParams );

		// Assert on the revision IDs first, as the array keys of the IDs in the parameter do not
		// matter, but the array keys of other items in the log params do matter
		$this->assertArrayHasKey( '5::ids', $actualLogParams );
		$this->assertArrayEquals(
			$expectedRevIds,
			$actualLogParams['5::ids'],
			false,
			false,
			'Log params should contain the expected revision IDs'
		);

		$actualLogParamsWithoutIds = $actualLogParams;
		unset( $actualLogParamsWithoutIds['5::ids'] );

		$this->assertArrayEquals(
			[
				'4::type' => 'revision',
				'6::ofield' => $oldVisibilityBits,
				'7::nfield' => $newVisibilityBits,
			],
			$actualLogParamsWithoutIds,
			false,
			true,
			'Log params are as expected'
		);

		$this->newSelectQueryBuilder()
			->select( 'ls_value' )
			->from( 'log_search' )
			->where( [ 'ls_field' => 'rev_id', 'ls_log_id' => $actualLogId ] )
			->caller( __METHOD__ )
			->assertFieldValues( array_map( 'strval', $expectedRevIds ) );

		$this->newSelectQueryBuilder()
			->select( 'ls_value' )
			->from( 'log_search' )
			->where( [ 'ls_field' => 'target_author_actor', 'ls_log_id' => $actualLogId ] )
			->caller( __METHOD__ )
			->assertFieldValues( array_map( 'strval', $expectedAuthorActors ) );
	}
}
