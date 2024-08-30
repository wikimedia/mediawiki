<?php

namespace MediaWiki\Tests\Maintenance;

use FixUserRegistration;
use MediaWiki\Title\Title;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \FixUserRegistration
 * @group Database
 * @author Dreamy Jazz
 */
class FixUserRegistrationTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return FixUserRegistration::class;
	}

	public function testExecute() {
		$userWithValidRegistration = $this->getMutableTestUser()->getUser();
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'user' )
			->set( [ 'user_registration' => $this->getDb()->timestamp( '20220405060708' ) ] )
			->where( [ 'user_id' => $userWithValidRegistration->getId() ] )
			->execute();
		ConvertibleTimestamp::setFakeTime( '20230405060708' );
		$userWithNullRegistrationButNoEdits = $this->getMutableTestUser()->getUser();
		$userWithNullRegistrationAndEdits = $this->getMutableTestUser()->getUser();
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'user' )
			->set( [ 'user_registration' => null ] )
			->where( [ 'user_id' => [
				$userWithNullRegistrationButNoEdits->getId(), $userWithNullRegistrationAndEdits->getId()
			] ] )
			->execute();
		// Make a testing edit for the $userWithNullRegistrationAndEdits
		ConvertibleTimestamp::setFakeTime( '20230505060708' );
		$this->editPage(
			Title::newFromText( 'Test' ), "testcontent", '',
			NS_MAIN, $userWithNullRegistrationAndEdits
		);
		ConvertibleTimestamp::setFakeTime( false );
		// Verify that the user_registration column is set up correctly for the test.
		$expectedRows = [
			[ $userWithValidRegistration->getId(), $this->getDb()->timestamp( '20220405060708' ) ],
			[ $userWithNullRegistrationButNoEdits->getId(), null ],
			[ $userWithNullRegistrationAndEdits->getId(), null ],
		];
		$this->newSelectQueryBuilder()
			->select( [ 'user_id', 'user_registration' ] )
			->from( 'user' )
			->orderBy( 'user_id' )
			->assertResultSet( $expectedRows );
		// Run the maintenance script
		$this->maintenance->execute();
		$expectedOutputRegex = '/Could not find registration for #2[\s\S]*Set registration for #3 to ' .
			preg_quote( $this->getDb()->timestamp( '20230505060708' ), '/' ) . '/';
		$this->expectOutputRegex( $expectedOutputRegex );
		$expectedRows = [
			[ $userWithValidRegistration->getId(), $this->getDb()->timestamp( '20220405060708' ) ],
			[ $userWithNullRegistrationButNoEdits->getId(), null ],
			[ $userWithNullRegistrationAndEdits->getId(), $this->getDb()->timestamp( '20230505060708' ) ],
		];
		$this->newSelectQueryBuilder()
			->select( [ 'user_id', 'user_registration' ] )
			->from( 'user' )
			->orderBy( 'user_id' )
			->assertResultSet( $expectedRows );
	}
}
