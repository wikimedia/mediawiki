<?php

namespace MediaWiki\Tests\Integration\RenameUser;

use MediaWikiIntegrationTestCase;

/**
 * @group Database
 * @covers \MediaWiki\RenameUser\RenameUser
 */
class RenameUserTest extends MediaWikiIntegrationTestCase {

	public function testRenamingToPreviouslyUsedNameIsPrevented() {
		$performer = $this->getTestSysop()->getUser();
		$renameUserFactory = $this->getServiceContainer()->getRenameUserFactory();

		$alice = $this->getMutableTestUser()->getUser();
		$firstRename = $renameUserFactory->newRenameUser(
			$performer, $alice, $alice->getName() . ' renamed', ''
		);
		$this->assertStatusGood( $firstRename->renameLocal() );
	}

}
