<?php
/**
 * Created by PhpStorm.
 * User: daki
 * Date: 28.03.18
 * Time: 17:33
 */

namespace MediaWiki\Tests\Storage;

use MediaWiki\Storage\RevisionRecord;

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

}
