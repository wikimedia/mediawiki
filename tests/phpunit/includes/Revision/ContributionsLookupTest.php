<?php

namespace MediaWiki\Tests\Revision;

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\ContributionsLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWikiIntegrationTestCase;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Revision\ContributionsLookup
 *
 * @group Database
 */
class ContributionsLookupTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers \MediaWiki\Revision\ContributionsLookup::getRevisionsByUser()
	 */
	public function testGetListOfRevisionsByUserIdentity() {
		$clock = (int)ConvertibleTimestamp::now( TS_UNIX );

		ConvertibleTimestamp::setFakeTime( function () use ( &$clock ) {
			return ++$clock;
		} );

		$user = $this->getTestUser()->getUser();
		$revisionStore = MediaWikiServices::getInstance()->getRevisionStore();
		$contributionsLookup = new ContributionsLookup( $revisionStore );

		// Sanity check
		$contribs = $contributionsLookup->getRevisionsByUser( $user, 10 )->getRevisions();
		$this->assertCount( 0, $contribs );
		/** @var RevisionRecord $rev1 */
		/** @var RevisionRecord $rev2 */
		$rev0 = $this->editPage( __METHOD__, 'Lorem Ipsum 0', 'test', NS_TALK, $user )->getValue()['revision-record'];
		$rev1 = $this->editPage( __METHOD__, 'Lorem Ipsum 1', 'test', NS_MAIN, $user )->getValue()['revision-record'];
		$rev2 = $this->editPage( __METHOD__, 'Lorem Ipsum 2', 'test', NS_TALK, $user )->getValue()['revision-record'];

		$contribs = $contributionsLookup->getRevisionsByUser( $user, 2 )->getRevisions();

		$this->assertCount( 2, $contribs );
		$this->assertSame( $rev2->getId(), $contribs[0]->getId() );
		$this->assertSame( $rev1->getId(), $contribs[1]->getId() );
		$this->assertInstanceOf( RevisionRecord::class, $contribs[0] );
		$this->assertEquals( $user->getName(), $contribs[0]->getUser()->getName() );
		$this->assertEquals(
			$rev2->getPageAsLinkTarget()->getPrefixedDBkey(),
			$contribs[0]->getPageAsLinkTarget()->getPrefixedDBkey()
		);
		$this->assertEquals(
			$rev1->getPageAsLinkTarget()->getPrefixedDBkey(),
			$contribs[1]->getPageAsLinkTarget()->getPrefixedDBkey()
		);
		// Desc order comes back from db query
	}

}
