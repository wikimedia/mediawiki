<?php

namespace MediaWiki\Tests\Maintenance;

use CreateAndPromote;
use MediaWiki\User\User;

/**
 * @covers \CreateAndPromote
 * @group Database
 * @author Dreamy Jazz
 */
class CreateAndPromoteTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return CreateAndPromote::class;
	}

	/** @dataProvider provideExecuteForExistingAccount */
	public function testExecuteForExistingAccount(
		$options, $expectedGroupsAfterCall, $expectedOutputRegex, $shouldCreateLogEntry
	) {
		$testUser = $this->getMutableTestUser()->getUser();
		// Set the user as the test user and use 'force' because we are not creating a user.
		$this->maintenance->setArg( 'username', $testUser );
		$this->maintenance->setOption( 'force', true );
		// Add the options from $options
		foreach ( $options as $option => $value ) {
			$this->maintenance->setOption( $option, $value );
		}
		$this->maintenance->execute();
		// Verify that the user is now in the expected groups
		$this->assertArrayEquals(
			$expectedGroupsAfterCall,
			$this->getServiceContainer()->getUserGroupManager()->getUserGroups( $testUser )
		);
		$this->expectOutputRegex( $expectedOutputRegex );
		// Verify that the log entry was created, if that is expected
		$queryBuilder = $this->newSelectQueryBuilder()
			->field( 'COUNT(*)' )
			->from( 'logging' )
			->where( [
				'log_actor' => $this->getServiceContainer()->getActorStore()
					->findActorIdByName( User::MAINTENANCE_SCRIPT_USER, $this->getDb() ),
				'log_title' => $testUser->getUserPage()->getDBkey(),
				'log_namespace' => NS_USER,
				'log_comment_id' => $this->getServiceContainer()->getCommentStore()
					->createComment( $this->getDb(), $options['reason'] ?? '' )->id,
			] );
		$this->assertSame( (int)$shouldCreateLogEntry, (int)$queryBuilder->fetchField() );
	}

	public static function provideExecuteForExistingAccount() {
		return [
			'Assigning no rights' => [ [], [], "/Account exists and nothing to do.\n/", false ],
			'Assigning sysop with reason provided' => [
				[ 'sysop' => 1, 'reason' => 'testing' ], [ 'sysop' ], "/Promoting .* into sysop...\ndone/", true
			],
			'Assigning bot' => [ [ 'bot' => 1 ], [ 'bot' ], "/Promoting .* into bot...\ndone/", true ],
			'Assigning suppress, bureaucrat, and interface-admin' => [
				[ 'custom-groups' => 'suppress', 'bureaucrat' => 1, 'interface-admin' => 1 ],
				[ 'suppress', 'bureaucrat', 'interface-admin' ],
				"/Promoting .* into bureaucrat, interface-admin, suppress...\ndone/",
				true,
			],
			'Assigning unrecognised group' => [
				[ 'custom-groups' => 'abctesting' ], [], "/Account exists and nothing to do.\n/", false,
			],
		];
	}
}
