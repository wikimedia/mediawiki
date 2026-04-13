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
			$performer, $alice, $alice->getName() . ' renamed', 'first rename'
		);
		$this->assertStatusGood( $firstRename->renameLocal() );

		$charlie = $this->getMutableTestUser()->getUser();
		$secondRename = $renameUserFactory->newRenameUser(
			$performer, $charlie, $alice->getName(), 'renaming to previously used name'
		);
		$this->assertStatusError( 'username-previously-renamed-account', $secondRename->renameLocal() );
	}

	/**
	 * Sanity check: renaming to a fresh, never-used name should succeed
	 * regardless of any prior renames in the system.
	 */
	public function testRenamingToFreshNameSucceedsAfterUnrelatedRename() {
		$performer = $this->getTestSysop()->getUser();
		$renameUserFactory = $this->getServiceContainer()->getRenameUserFactory();

		$alice = $this->getMutableTestUser()->getUser();
		$firstRename = $renameUserFactory->newRenameUser(
			$performer, $alice, $alice->getName() . ' renamed', 'first rename'
		);
		$this->assertStatusGood( $firstRename->renameLocal() );

		$charlie = $this->getMutableTestUser()->getUser();
		$freshName = 'NeverUsedName_' . wfRandomString( 8 );
		$secondRename = $renameUserFactory->newRenameUser(
			$performer, $charlie, $freshName, 'rename to fresh name'
		);
		$this->assertStatusGood( $secondRename->renameLocal() );
	}
}
