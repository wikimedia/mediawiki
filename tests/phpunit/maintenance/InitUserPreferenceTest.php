<?php

namespace MediaWiki\Tests\Maintenance;

use InitUserPreference;

/**
 * @covers \InitUserPreference
 * @group Database
 * @author Dreamy Jazz
 */
class InitUserPreferenceTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return InitUserPreference::class;
	}

	public function testExecute() {
		// Insert some testing data
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'user_properties' )
			->rows( [
				[ 'up_user' => 1, 'up_property' => 'preference-one', 'up_value' => 'first-value' ],
				[ 'up_user' => 2, 'up_property' => 'preference-one', 'up_value' => 'second-value' ],
				[ 'up_user' => 3, 'up_property' => 'preference-one', 'up_value' => '1' ],
				[ 'up_user' => 6, 'up_property' => 'preference-one', 'up_value' => '0' ],
				[ 'up_user' => 10, 'up_property' => 'preference-one', 'up_value' => null ],
				[ 'up_user' => 5, 'up_property' => 'preference-two', 'up_value' => 'ignored' ],
			] )
			->caller( __METHOD__ )
			->execute();
		// Run the maintenance script to copy the values from preference-one to preference-one-new
		$this->maintenance->setOption( 'target', 'preference-one-new' );
		$this->maintenance->setOption( 'source', 'preference-one' );
		$this->maintenance->execute();
		// Check that the maintenance script executed as intended by asserting that the user_properties table is
		// as expected.
		$this->expectOutputString(
			"Initializing 'preference-one-new' based on the value of 'preference-one'\n" .
			"Processed 3 user(s)\nFinished!\n"
		);
		$this->newSelectQueryBuilder()
			->select( [ 'up_property', 'up_user', 'up_value' ] )
			->from( 'user_properties' )
			->caller( __METHOD__ )
			->assertResultSet( [
				[ 'preference-one', 1, 'first-value' ],
				[ 'preference-one', 2, 'second-value' ],
				[ 'preference-one', 3, '1' ],
				[ 'preference-one', 6, '0' ],
				[ 'preference-one', 10, null ],
				[ 'preference-one-new', 1, 'first-value' ],
				[ 'preference-one-new', 2, 'second-value' ],
				[ 'preference-one-new', 3, '1' ],
				[ 'preference-two', 5, 'ignored' ],
			] );
	}
}
