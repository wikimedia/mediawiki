<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\Page\Event\PageHistoryVisibilityChangedEvent;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Tests\ExpectCallbackTrait;
use PHPUnit\Framework\Assert;

/**
 * @covers \RevDelRevisionList
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
		$rev2 = $this->editPage( $page, 'new content' )->getNewRevision()->getId();
		$rev3 = $this->editPage( $page, 'newer content' )->getNewRevision()->getId();

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
							$event->getCurrentRevisionId(),
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
							$event->getCurrentRevisionId(),
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
	}
}
