<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use ReassignEdits;

/**
 * @covers \ReassignEdits
 * @group Database
 * @group Maintenance
 */
class ReassignEditsTest extends MaintenanceBaseTestCase {
	use TempUserTestTrait;

	public function getMaintenanceClass() {
		return ReassignEdits::class;
	}

	/**
	 * Verifies the output of the maintenance script given various information about the test data and
	 * users provided to the maintenance script.
	 *
	 * @param int $revisionCount
	 * @param int $deletedCount
	 * @param int|null $recentChangesCount Null if the script is set to not search through recentchanges.
	 * @param bool $fromIsIP
	 * @param bool $isDryRun
	 * @return void
	 */
	private function commonVerifyOutput(
		int $revisionCount, int $deletedCount, ?int $recentChangesCount, bool $fromIsIP, bool $isDryRun
	) {
		$actualOutput = $this->getActualOutputForAssertion();
		$total = $revisionCount + $deletedCount;

		// Verify that the counting part of the script had the correct output
		$this->assertStringContainsString( "Checking current edits...found $revisionCount.", $actualOutput );
		$this->assertStringContainsString( "Checking deleted edits...found $deletedCount", $actualOutput );
		if ( $recentChangesCount === null ) {
			$this->assertStringNotContainsString( "Checking recent changes", $actualOutput );
		} else {
			$total += $recentChangesCount;
			$this->assertStringContainsString( "Checking recent changes...found $recentChangesCount", $actualOutput );
		}
		$this->assertStringContainsString( "Total entries to change: $total", $actualOutput );

		// Verify that if it's not a dry-run, the script starts updating the DB
		if ( $isDryRun ) {
			$this->assertStringContainsString( 'Run the script again without --report to update', $actualOutput );
			$this->assertStringNotContainsString( 'Reassigning current edits', $actualOutput );
			$this->assertStringNotContainsString( 'Reassigning deleted edits', $actualOutput );
			$this->assertStringNotContainsString( 'Reassigning recent changes edits', $actualOutput );
			$this->assertStringNotContainsString( 'Deleting ip_changes', $actualOutput );

			return;
		}

		if ( $revisionCount ) {
			$this->assertStringContainsString( 'Reassigning current edits...done', $actualOutput );
		}
		if ( $deletedCount ) {
			$this->assertStringContainsString( 'Reassigning deleted edits...done', $actualOutput );
		}
		if ( $deletedCount ) {
			$this->assertStringContainsString( 'Updating recent changes...done', $actualOutput );
		}
		if ( $fromIsIP && $total ) {
			$this->assertStringContainsString( 'Deleting ip_changes...done', $actualOutput );
		}
	}

	public function testExecuteToIP() {
		$fromIP = $this->getServiceContainer()->getUserFactory()->newAnonymous( '1.2.3.4' );
		$toIP = $this->getServiceContainer()->getUserFactory()->newAnonymous( '1.2.3.5' );

		$this->maintenance->setArg( 'from', $fromIP->getName() );
		$this->maintenance->setArg( 'to', $toIP->getName() );

		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Script does not support re-assigning to another IP/' );
		$this->maintenance->execute();
	}

	public function testExecuteWhenUsernameInvalid() {
		$this->maintenance->setArg( 'from', 'User:::abc#test' );
		$this->maintenance->setArg( 'to', 'abc#test' );

		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Invalid username/' );
		$this->maintenance->execute();
	}

	public function testExecuteWhenFromAndToAreSameUser() {
		$testUser = $this->getTestUser()->getUserIdentity();
		$this->maintenance->setArg( 'from', $testUser->getName() );
		$this->maintenance->setArg( 'to', $testUser->getName() );
		$this->maintenance->setName( 'reassignEdits.php' );

		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/The from and to user cannot be the same/' );
		$this->maintenance->execute();
	}

	public function testExecuteFromIPToUserWhenNoActionsToMove() {
		$fromIP = $this->getServiceContainer()->getUserFactory()->newAnonymous( '1.2.3.4' );
		$toUser = $this->getTestUser()->getUserIdentity();

		$this->maintenance->setArg( 'from', $fromIP->getName() );
		$this->maintenance->setArg( 'to', $toUser->getName() );
		$this->maintenance->execute();

		$this->commonVerifyOutput( 0, 0, 0, true, false );
	}

	public function testExecuteFromIPToUserWhenActionsToMoveButReportOnlyMode() {
		$this->disableAutoCreateTempUser();
		$fromIP = $this->getServiceContainer()->getUserFactory()->newAnonymous( '1.2.3.4' );
		$toUser = $this->getTestUser()->getUserIdentity();
		$this->editPage(
			$this->getNonexistingTestPage(), 'Testing', '', NS_MAIN, $fromIP
		);

		$this->maintenance->setArg( 'from', $fromIP->getName() );
		$this->maintenance->setArg( 'to', $toUser->getName() );
		$this->maintenance->setOption( 'report', 1 );
		$this->maintenance->execute();

		$this->commonVerifyOutput( 1, 0, 1, true, true );
	}

	public function testExecuteFromIPToUserWhenActionsToMove() {
		$this->disableAutoCreateTempUser();
		$fromIP = $this->getServiceContainer()->getUserFactory()->newAnonymous( '1.2.3.4' );
		$toUser = $this->getTestUser()->getUserIdentity();
		$this->editPage(
			$this->getNonexistingTestPage(), 'Testing', '', NS_MAIN, $fromIP
		);

		$this->maintenance->setArg( 'from', $fromIP->getName() );
		$this->maintenance->setArg( 'to', $toUser->getName() );
		$this->maintenance->setArg( 'force', 1 );
		$this->maintenance->execute();

		$this->commonVerifyOutput( 1, 0, 1, true, false );
	}

	public function testExecuteFromUserToUserWhenActionsToMove() {
		$this->disableAutoCreateTempUser();
		$fromUser = $this->getTestUser()->getUser();
		$toUser = $this->getTestSysop()->getUserIdentity();
		$pageToDelete = $this->getNonexistingTestPage( Title::newFromText( 'Testing' ) );
		$this->editPage( $pageToDelete, 'Testing', '', NS_MAIN, $fromUser );
		$this->deletePage( $pageToDelete );

		$this->editPage(
			$this->getNonexistingTestPage( Title::newFromText( 'Testingabc' ) ),
			'Testingabc', '', NS_MAIN, $fromUser
		);

		$this->maintenance->setArg( 'from', $fromUser->getName() );
		$this->maintenance->setArg( 'to', $toUser->getName() );
		$this->maintenance->execute();

		$this->commonVerifyOutput( 1, 1, 1, false, false );
	}
}
