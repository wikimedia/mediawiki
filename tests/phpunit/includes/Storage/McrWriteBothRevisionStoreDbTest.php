<?php
namespace MediaWiki\Tests\Storage;

use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\RevisionRecord;

/**
 * Tests RevisionStore against the intermediate MCR DB schema for use during schema migration.
 *
 * @covers MediaWiki\Storage\RevisionStore
 *
 * @group RevisionStore
 * @group Storage
 * @group Database
 * @group medium
 */
class McrWriteBothRevisionStoreDbTest extends RevisionStoreDbTestBase {

	use McrWriteBothSchemaOverride;

	protected function assertRevisionExistsInDatabase( RevisionRecord $rev ) {
		parent::assertRevisionExistsInDatabase( $rev );

		$this->assertSelect(
			'slots', [ 'count(*)' ], [ 'slot_revision_id' => $rev->getId() ], [ [ '1' ] ]
		);
		$this->assertSelect(
			'content',
			[ 'count(*)' ],
			[ 'content_address' => $rev->getSlot( 'main' )->getAddress() ],
			[ [ '1' ] ]
		);
	}

	public function provideBooleans() {
		return [
			[ true ],
			[ false ],
		];
	}

	/**
	 * @dataProvider provideBooleans
	 * @param bool $dbAlreadyHasMainSlotId
	 */
	public function testGetQueryInfo_SlotDataJoin( $dbAlreadyHasMainSlotId ) {
		if ( $dbAlreadyHasMainSlotId ) {
			$slotRoleStore = MediaWikiServices::getInstance()->getSlotRoleStore();
			$dbAlreadyHasMainSlotId = $slotRoleStore->acquireId( 'main' );
		}

		$store = MediaWikiServices::getInstance()->getRevisionStore();

		$queryInfo = $store->getQueryInfo();

		$this->assertTrue( array_key_exists( 'a_slot_data', $queryInfo['tables'] ) );
		$this->assertTrue( array_key_exists( 'a_slot_data', $queryInfo['joins'] ) );

		$joinClause = $queryInfo['joins']['a_slot_data'][1];
		if ( $dbAlreadyHasMainSlotId ) {
			$this->assertContains( 'slot_role_id =', $joinClause );
			$this->assertContains( 'AND', $joinClause );
		} else {
			$this->assertNotContains( 'slot_role_id =', $joinClause );
			$this->assertNotContains( 'AND', $joinClause );
		}
	}

}
