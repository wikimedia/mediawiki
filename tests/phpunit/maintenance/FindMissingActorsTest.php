<?php

namespace MediaWiki\Tests\Maintenance;

use FindMissingActors;
use LogicException;
use MediaWiki\Exception\CannotCreateActorException;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\User\ActorNormalization;
use MediaWiki\User\UserIdentity;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \FindMissingActors
 * @group Database
 * @author Dreamy Jazz
 */
class FindMissingActorsTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return FindMissingActors::class;
	}

	/**
	 * Returns the instance to test with. This returns a mocked instance where the
	 * ::readconsole method is mocked such that it does not attempt to read anything from
	 * STDIN or other user-input. Instead it expects that the test cases set the return value.
	 *
	 * @return \MediaWiki\Maintenance\Maintenance|TestingAccessWrapper
	 */
	protected function createMaintenance() {
		// Because ::readconsole is a static method, we cannot mock it using PHPUnit.
		// We need to mock it as it causes tests to hang if called as it waits for real user input.
		// Therefore, we need to extend the class we are testing to implement a fake ::readconsole method
		// that we can return fake data from.
		$obj = new class () extends FindMissingActors {
			private static ?string $readConsoleReturnValue = null;

			/** @inheritDoc */
			public static function readconsole( $prompt = '> ' ) {
				if ( static::$readConsoleReturnValue === null ) {
					throw new LogicException( 'Did not expect a call to ::readconsole.' );
				}

				if ( $prompt !== 'Type "yes" to continue: ' ) {
					throw new LogicException( 'Provided prompt was not as expected.' );
				}

				return static::$readConsoleReturnValue;
			}

			/**
			 * Makes a call to ::readconsole be expected.
			 *
			 * @param string|false $returnValue The value to return when the method is called.
			 * @return void
			 */
			public static function expectCallToReadConsole( $returnValue ) {
				static::$readConsoleReturnValue = $returnValue;
			}
		};
		return TestingAccessWrapper::newFromObject( $obj );
	}

	/** @dataProvider provideExecuteForFatalError */
	public function testExecuteForFatalError( $options, $expectedOutputRegex ) {
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->expectCallToFatalError();
		$this->expectOutputRegex( $expectedOutputRegex );
		$this->maintenance->execute();
	}

	public static function provideExecuteForFatalError() {
		return [
			'Provided field is not recognised' => [ [ 'field' => 'abc' ], '/Unknown field: abc/' ],
			'Provided type is not recognised' => [ [ 'field' => 'rc_actor', 'type' => 'abc' ], '/Unknown type: abc/' ],
			'--overwrite-with username is not valid' => [
				[ 'overwrite-with' => 'User:::abc#test', 'field' => 'rc_actor' ],
				'/Not a valid user name: \'' . preg_quote( 'User:::abc#test' ) . '\'/',
			],
			'--overwrite-with username does not exist' => [
				[ 'overwrite-with' => 'Non-existing-test-user', 'field' => 'rc_actor' ],
				'/Unknown user: \'Non-existing-test-user\'/',
			],
		];
	}

	public function testExecuteWhenFailsToGetActorIdForUser() {
		// Mock that ActorNormalization cannot acquire an actor ID for the user.
		$mockActorNormalization = $this->createMock( ActorNormalization::class );
		$mockActorNormalization->method( 'acquireActorId' )
			->willThrowException( new CannotCreateActorException( 'Test' ) );
		$this->setService( 'ActorNormalization', $mockActorNormalization );

		$testUsername = $this->getTestUser()->getUserIdentity()->getName();
		$this->testExecuteForFatalError(
			[
				'overwrite-with' => $testUsername,
				'field' => 'rc_actor',
			],
			"/Failed to acquire an actor ID for user '$testUsername'/"
		);
	}

	public function testExecuteWhenOverwriteAborted() {
		// Add a bad row to recentchanges with an invalid actor ID by making an edit that causes a
		// recentchanges row and then breaking that row.
		$pageUpdateStatus = $this->editPage( $this->getExistingTestPage(), 'test' );
		$this->assertStatusGood( $pageUpdateStatus );
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'recentchanges' )
			->set( [ 'rc_actor' => 12344332 ] )
			->where( [ 'rc_this_oldid' => $pageUpdateStatus->getNewRevision()->getId() ] )
			->caller( __METHOD__ )
			->execute();

		// Test that when running the script to overwrite the user, the script asks for a confirmation and when the
		// user says "no" the script exits without doing anything.
		$this->maintenance->expectCallToReadConsole( 'no' );
		$overwriteWithUsername = $this->getTestUser()->getUserIdentity()->getName();
		$this->testExecuteForFatalError(
			[
				'overwrite-with' => $overwriteWithUsername,
				'field' => 'rc_actor',
			],
			"/Using existing user: '$overwriteWithUsername'[\s\S]*" .
			'Do you want to OVERWRITE the listed actor IDs[\s\S]*Aborted\.[\s]*$/'
		);

		// Check that the DB has not been touched
		$this->newSelectQueryBuilder()
			->select( 'rc_actor' )
			->from( 'recentchanges' )
			->where( [ 'rc_this_oldid' => $pageUpdateStatus->getNewRevision()->getId() ] )
			->caller( __METHOD__ )
			->assertFieldValue( 12344332 );
	}

	/**
	 * Creates a log entry for testing.
	 *
	 * @return int The ID for the created log entry
	 */
	public function newLogEntry( UserIdentity $performer ): int {
		$logEntry = new ManualLogEntry( 'phpunit', 'test' );
		$logEntry->setPerformer( $performer );
		$logEntry->setTarget( $this->getExistingTestPage()->getTitle() );
		$logEntry->setComment( 'A very good reason' );
		return $logEntry->insert();
	}

	public function testExecuteWhenNoMissingActorIdsInSpecifiedTable() {
		// Insert an entry to the logging table
		$logPerformer = $this->getTestUser()->getUserIdentity();
		$logId = $this->newLogEntry( $logPerformer );

		// Insert a entry to the revision table with a missing actor ID
		$pageUpdateStatus = $this->editPage( $this->getExistingTestPage(), 'test' );
		$this->assertStatusGood( $pageUpdateStatus );
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'revision' )
			->set( [ 'rev_actor' => 12344332 ] )
			->where( [ 'rev_id' => $pageUpdateStatus->getNewRevision()->getId() ] )
			->caller( __METHOD__ )
			->execute();

		// Run the maintenance script with the field as log_actor, which should find no missing actor IDs
		// and therefore leave the revision row alone.
		$overwriteWithUser = $this->getServiceContainer()->getUserFactory()->newFromName( 'Unknown user' );
		$this->maintenance->setOption( 'field', 'log_actor' );
		$this->maintenance->setOption( 'overwrite-with', $overwriteWithUser->getName() );
		$this->maintenance->execute();

		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( "Using system user: '{$overwriteWithUser->getName()}'", $actualOutput );
		$this->assertStringContainsString( 'Found 0 invalid actor IDs', $actualOutput );
		$this->assertStringNotContainsString( 'Do you want to OVERWRITE the listed actor IDs?', $actualOutput );

		// Check that the DB has not been touched
		$this->newSelectQueryBuilder()
			->select( 'rev_actor' )
			->from( 'revision' )
			->where( [ 'rev_id' => $pageUpdateStatus->getNewRevision()->getId() ] )
			->caller( __METHOD__ )
			->assertFieldValue( 12344332 );
		$this->newSelectQueryBuilder()
			->select( 'log_actor' )
			->from( 'logging' )
			->where( [ 'log_id' => $logId ] )
			->caller( __METHOD__ )
			->assertFieldValue(
				$this->getServiceContainer()->getActorStore()
					->findActorId( $logPerformer, $this->getDb() )
			);
	}

	public function testExecuteWhenMissingActorIdsInTableWithoutOverwriteWithSet() {
		// Insert a entry to the revision table that has a missing actor ID
		$page = $this->getExistingTestPage();
		$revisionPerformer = $this->getTestUser()->getAuthority();
		$firstPageUpdateStatus = $this->editPage( $page, 'test', '', NS_MAIN, $revisionPerformer );
		$firstRevId = $firstPageUpdateStatus->getNewRevision()->getId();
		$this->assertStatusGood( $firstPageUpdateStatus );
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'revision' )
			->set( [ 'rev_actor' => 12344332 ] )
			->where( [ 'rev_id' => $firstPageUpdateStatus->getNewRevision()->getId() ] )
			->caller( __METHOD__ )
			->execute();

		// Insert a good entry to the revision table
		$secondPageUpdateStatus = $this->editPage( $page, 'testing', '', NS_MAIN, $revisionPerformer );
		$this->assertStatusGood( $secondPageUpdateStatus );

		// Run the maintenance script which should find the missing actor IDs but not do anything with them.
		$this->maintenance->setOption( 'field', 'rev_actor' );
		$this->maintenance->execute();

		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( 'Found 1 invalid actor IDs', $actualOutput );
		$this->assertStringContainsString( "\t\tID\tACTOR\n\t\t$firstRevId\t12344332", $actualOutput );
		$this->assertStringNotContainsString( 'Do you want to OVERWRITE the listed actor IDs?', $actualOutput );

		// Check that the DB has not been touched
		$secondRevId = $secondPageUpdateStatus->getNewRevision()->getId();
		$this->newSelectQueryBuilder()
			->select( [ 'rev_id', 'rev_actor' ] )
			->from( 'revision' )
			->where( [ 'rev_id' => [ $firstRevId, $secondRevId ] ] )
			->caller( __METHOD__ )
			->assertResultSet( [
				[ $firstRevId, 12344332 ],
				[
					$secondRevId,
					$this->getServiceContainer()->getActorStore()
						->findActorId( $revisionPerformer->getUser(), $this->getDb() ),
				]
			] );
	}

	public function testExecuteWhenMissingActorIdsInTableWithSomeSkipped() {
		// Insert two entries to the logging table, which use different missing actor IDs.
		$logPerformer = $this->getTestUser()->getUserIdentity();
		$firstLogId = $this->newLogEntry( $logPerformer );
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'logging' )
			->set( [ 'log_actor' => 12345 ] )
			->where( [ 'log_id' => $firstLogId ] )
			->caller( __METHOD__ )
			->execute();

		$secondLogId = $this->newLogEntry( $logPerformer );
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'logging' )
			->set( [ 'log_actor' => 123456 ] )
			->where( [ 'log_id' => $secondLogId ] )
			->caller( __METHOD__ )
			->execute();

		// Run the maintenance script with the field as log_actor and overwrite-with set, so that the script
		// actually performs the updates. We also skip one of the IDs to test that behaviour.
		$overwriteWithUser = $this->getTestUser()->getUserIdentity();
		$overwriteWithActorId = $this->getServiceContainer()->getActorStore()
			->findActorId( $overwriteWithUser, $this->getDb() );
		$this->maintenance->setOption( 'overwrite-with', $overwriteWithUser->getName() );
		$this->maintenance->setOption( 'field', 'log_actor' );
		$this->maintenance->setOption( 'skip', 123456 );
		$this->maintenance->expectCallToReadConsole( 'yes' );
		$this->maintenance->execute();

		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( 'Found 1 invalid actor IDs', $actualOutput );
		$this->assertStringContainsString( "\t\tID\tACTOR\n\t\t$firstLogId\t12345", $actualOutput );
		$this->assertStringContainsString( 'Do you want to OVERWRITE the listed actor IDs?', $actualOutput );
		$this->assertStringContainsString(
			"OVERWRITING 1 actor IDs in logging.log_actor with $overwriteWithActorId...", $actualOutput
		);
		$this->assertStringContainsString( 'Updated 1 rows', $actualOutput );

		// Check that only the logging row with actor_id 12345 has been updated.
		$this->newSelectQueryBuilder()
			->select( [ 'log_id', 'log_actor' ] )
			->from( 'logging' )
			->where( [ 'log_id' => [ $firstLogId, $secondLogId ] ] )
			->caller( __METHOD__ )
			->assertResultSet( [
				[
					$firstLogId,
					$overwriteWithActorId,
				],
				[ $secondLogId, 123456 ],
			] );
	}

	public function testExecuteWhenMissingActorIdsInTableWithSomeSkippedDueToBatchSize() {
		// Insert two entries to the logging table, which use different missing actor IDs.
		$logPerformer = $this->getTestUser()->getUserIdentity();
		$firstLogId = $this->newLogEntry( $logPerformer );
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'logging' )
			->set( [ 'log_actor' => 12345 ] )
			->where( [ 'log_id' => $firstLogId ] )
			->caller( __METHOD__ )
			->execute();

		$secondLogId = $this->newLogEntry( $logPerformer );
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'logging' )
			->set( [ 'log_actor' => 123456 ] )
			->where( [ 'log_id' => $secondLogId ] )
			->caller( __METHOD__ )
			->execute();

		// Run the maintenance script with the field as log_actor and overwrite-with set, so that the script
		// actually performs the updates. The batch size is set to 1 to simulate what happens if too many users
		// need an update.
		$overwriteWithUser = $this->getTestUser()->getUserIdentity();
		$overwriteWithActorId = $this->getServiceContainer()->getActorStore()
			->findActorId( $overwriteWithUser, $this->getDb() );

		// To be able to call ::setBatchSize without causing the IDE to show an error for accessing a protected method,
		// we make variable that is documented as just being of the TestingAccessWrapper to access ::setBatchSize.
		/** @var TestingAccessWrapper $maintenance */
		$maintenance = $this->maintenance;
		$maintenance->setOption( 'overwrite-with', $overwriteWithUser->getName() );
		$maintenance->setOption( 'field', 'log_actor' );
		$maintenance->setBatchSize( 1 );
		$maintenance->expectCallToReadConsole( 'yes' );
		$maintenance->execute();

		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( 'Found 1 invalid actor IDs', $actualOutput );
		$this->assertStringContainsString( "\t\tID\tACTOR\n\t\t$firstLogId\t12345", $actualOutput );
		$this->assertStringContainsString(
			'Batch size reached, run again after fixing the current batch', $actualOutput
		);
		$this->assertStringContainsString( 'Do you want to OVERWRITE the listed actor IDs?', $actualOutput );
		$this->assertStringContainsString(
			"OVERWRITING 1 actor IDs in logging.log_actor with $overwriteWithActorId...", $actualOutput
		);
		$this->assertStringContainsString( 'Updated 1 rows', $actualOutput );

		// Check that only the logging row with actor_id 12345 has been updated.
		$this->newSelectQueryBuilder()
			->select( [ 'log_id', 'log_actor' ] )
			->from( 'logging' )
			->where( [ 'log_id' => [ $firstLogId, $secondLogId ] ] )
			->caller( __METHOD__ )
			->assertResultSet( [
				[
					$firstLogId,
					$overwriteWithActorId,
				],
				[ $secondLogId, 123456 ],
			] );
	}

	public function testExecuteWhenBrokenActorIdsArePresent() {
		// Insert an entry to the logging table that uses a broken actor ID.
		$logPerformer = $this->getTestUser()->getUserIdentity();
		$logId = $this->newLogEntry( $logPerformer );
		$logPerformerActorId = $this->getServiceContainer()->getActorStore()
			->findActorId( $logPerformer, $this->getDb() );
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'actor' )
			->set( [ 'actor_name' => '' ] )
			->where( [ 'actor_id' => $logPerformerActorId ] )
			->caller( __METHOD__ )
			->execute();

		// Run the maintenance script with the field as log_actor and overwrite-with set, so that the script
		// actually performs the updates.
		$overwriteWithUser = $this->getMutableTestUser()->getUserIdentity();
		$overwriteWithActorId = $this->getServiceContainer()->getActorStore()
			->findActorId( $overwriteWithUser, $this->getDb() );
		$this->maintenance->setOption( 'overwrite-with', $overwriteWithUser->getName() );
		$this->maintenance->setOption( 'type', 'broken' );
		$this->maintenance->setOption( 'field', 'log_actor' );
		$this->maintenance->expectCallToReadConsole( 'yes' );
		$this->maintenance->execute();

		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( 'Found 1 invalid actor IDs', $actualOutput );
		$this->assertStringContainsString( "\t\tID\tACTOR\n\t\t$logId\t$logPerformerActorId", $actualOutput );
		$this->assertStringContainsString( 'Do you want to OVERWRITE the listed actor IDs?', $actualOutput );
		$this->assertStringContainsString(
			"OVERWRITING 1 actor IDs in logging.log_actor with $overwriteWithActorId...", $actualOutput
		);
		$this->assertStringContainsString( 'Updated 1 rows', $actualOutput );

		// Check that the logging row has had it's actor ID updated to use the overwrite-with user's actor ID
		$this->newSelectQueryBuilder()
			->select( 'log_actor' )
			->from( 'logging' )
			->where( [ 'log_id' => $logId ] )
			->caller( __METHOD__ )
			->assertFieldValue( $overwriteWithActorId );
	}
}
