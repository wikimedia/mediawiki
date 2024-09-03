<?php

namespace MediaWiki\Tests\Maintenance;

use MakeTestEdits;

/**
 * @covers MakeTestEdits
 * @group Database
 * @author Dreamy Jazz
 */
class MakeTestEditsTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return MakeTestEdits::class;
	}

	public function testExecuteForUnregisteredUser() {
		$this->maintenance->setOption( 'user', 'NonExistingTestUser1' );
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/No such user exists/' );
		$this->maintenance->execute();
	}

	public function testExecute() {
		// Run the maintenance script to create 5 edits.
		$testUser = $this->getTestUser()->getUserIdentity();
		$this->maintenance->setOption( 'user', $testUser->getName() );
		$this->maintenance->setOption( 'count', 5 );
		$this->maintenance->setBatchSize( 2 );
		$this->maintenance->execute();
		// Assert that the revision table now has 5 revisions in the mainspace for the test user we used
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'revision' )
			->join( 'actor', null, 'actor_id=rev_actor' )
			->join( 'page', null, 'page_id=rev_page' )
			->where( [
				'actor_name' => $testUser->getName(),
				'page_namespace' => NS_MAIN,
			] )
			->assertFieldValue( 5 );
		// Assert that no other unexpected revisions were created
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'revision' )
			->assertFieldValue( 5 );
	}
}
