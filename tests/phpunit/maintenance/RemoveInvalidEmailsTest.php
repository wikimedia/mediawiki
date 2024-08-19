<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\User\UserIdentity;
use RemoveInvalidEmails;

/**
 * @covers \RemoveInvalidEmails
 * @group Database
 * @author Dreamy Jazz
 */
class RemoveInvalidEmailsTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return RemoveInvalidEmails::class;
	}

	public function testExecuteWhenNoInvalidEmails() {
		$testUser1 = $this->getTestUser()->getUser();
		$testUser1EmailBeforeExecution = $testUser1->getEmail();
		$this->maintenance->execute();
		$testUser1->clearInstanceCache( 'name' );
		$this->assertSame( $testUser1EmailBeforeExecution, $testUser1->getEmail() );
		$this->expectOutputString( "Done.\n" );
	}

	/**
	 * @param string $email The custom email to be used
	 * @param string|null $authenticationTimestamp The authentication timestamp of the email, or null if not
	 *   authenticated
	 * @return UserIdentity
	 */
	private function getMutableTestUserWithCustomEmail( string $email, ?string $authenticationTimestamp ): UserIdentity {
		$testUser = $this->getMutableTestUser()->getUserIdentity();
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'user' )
			->set( [
				'user_email' => $email,
				'user_email_authenticated' => $this->getDb()->timestampOrNull( $authenticationTimestamp )
			] )
			->where( [ 'user_id' => $testUser->getId() ] )
			->execute();
		return $testUser;
	}

	/**
	 * @param int $id The user's ID
	 * @param string $email The email that the user should have
	 * @param bool $authenticationTimestamp The expected authentication timestamp for the email, or null if the email
	 *   is expected to not be authenticated
	 * @return void
	 */
	private function checkUserHasEmail( int $id, string $email, ?string $authenticationTimestamp ) {
		$this->newSelectQueryBuilder()
			->select( [ 'user_email', 'user_email_authenticated' ] )
			->from( 'user' )
			->where( [ 'user_id' => $id ] )
			->assertRowValue( [ $email, $this->getDb()->timestampOrNull( $authenticationTimestamp ) ] );
	}

	/** @dataProvider provideCommitValues */
	public function testExecuteWhenSomeInvalidEmails( $commit, $expectedOutputRegex ) {
		// Get a test users, one with a valid email, one with an invalid email, and one with an
		// invalid email but is marked as authenticated.
		$testUser1 = $this->getMutableTestUserWithCustomEmail( 'test@test.com', null );
		$testUser2 = $this->getMutableTestUserWithCustomEmail( 'invalid', null );
		$testUser3 = $this->getMutableTestUserWithCustomEmail( 'invalid2', '20240506070809' );
		// Run the maintenance script, optionally with the --commit option
		if ( $commit ) {
			$this->maintenance->setOption( 'commit', 1 );
		}
		$this->maintenance->execute();
		// All users, except the second user when --commit is set, should be untouched by the script
		// so assert that this is the case
		$this->checkUserHasEmail( $testUser1->getId(), 'test@test.com', null );
		$this->checkUserHasEmail( $testUser2->getId(), $commit ? '' : 'invalid', null );
		$this->checkUserHasEmail( $testUser3->getId(), 'invalid2', '20240506070809' );
		$this->expectOutputRegex( $expectedOutputRegex );
	}

	public static function provideCommitValues() {
		return [
			'--commit provided' => [ true, '/Removing 1 emails from the database[\s\S]*Done/' ],
			'--commit not provided' => [ false, '/Would have removed 1 emails from the database.[\s\S]*Done/' ],
		];
	}
}
