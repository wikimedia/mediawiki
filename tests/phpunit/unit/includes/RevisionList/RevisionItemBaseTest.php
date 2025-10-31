<?php

namespace MediaWiki\Tests\Unit\RevisionList;

use MediaWiki\RevisionList\RevisionItemBase;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\RevisionList\RevisionItemBase
 *
 * @author DannyS712
 */
class RevisionItemBaseTest extends MediaWikiUnitTestCase {

	public function testConcreteMethods() {
		// Test the concrete methods of the abstract RevisionItemBase class
		$revisionItemBase = $this->getMockBuilder( RevisionItemBase::class )
			->disableOriginalConstructor()
			->getMockForAbstractClass();

		$this->assertNull( $revisionItemBase->getIdField() );
		$this->assertFalse( $revisionItemBase->getTimestampField() );
		$this->assertFalse( $revisionItemBase->getAuthorIdField() );
		$this->assertFalse( $revisionItemBase->getAuthorNameField() );
		$this->assertFalse( $revisionItemBase->getAuthorActorField() );
	}

}
