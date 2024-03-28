<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use PopulateUserIsTemp;

/**
 * @covers \PopulateUserIsTemp
 * @group Database
 */
class PopulateUserIsTempTest extends MaintenanceBaseTestCase {
	use TempUserTestTrait;

	protected function getMaintenanceClass() {
		return PopulateUserIsTemp::class;
	}

	public function testDoDBUpdates() {
		$this->enableAutoCreateTempUser( [
			'matchPattern' => [ '*$1', '~$1' ],
		] );
		$this->maintenance->setOption( 'batch-size', 2 );
		$this->assertSame(
			2,
			(int)$this->getDb()->newSelectQueryBuilder()
				->select( 'COUNT(*)' )
				->from( 'user' )
				->where( [ 'user_is_temp' => 1 ] )
				->fetchField(),
			'The database should have 2 users with user_is_temp set to 1 before the execute method is called.'
		);
		$this->assertTrue(
			$this->maintenance->execute(),
			'The execute method did not return true as expected.'
		);
		$this->assertSame(
			5,
			(int)$this->getDb()->newSelectQueryBuilder()
				->select( 'COUNT(*)' )
				->from( 'user' )
				->where( [ 'user_is_temp' => 1 ] )
				->fetchField(),
			'The number of users with user_is_temp set to 1 is not as expected.'
		);
	}

	public function addDBData() {
		parent::addDBData();

		// Create some temporary users and then set the user table to have user_is_temp as 0 for some of them.
		$this->enableAutoCreateTempUser( [
			'matchPattern' => [ '*$1', '~$1' ],
		] );
		$tempUserCreator = $this->getServiceContainer()->getTempUserCreator();
		$tempUserCreator->create( '*Unregistered 1', new FauxRequest() );
		$tempUserCreator->create( '*Unregistered 2', new FauxRequest() );
		$tempUserCreator->create( '~Unregistered 3', new FauxRequest() );
		$tempUserCreator->create( '~Unregistered 4567', new FauxRequest() );
		$tempUserCreator->create( '~Unregistered 456789', new FauxRequest() );
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'user' )
			->set( [ 'user_is_temp' => 0 ] )
			->where( [ 'user_name' => [ '*Unregistered 2', '~Unregistered 3', '~Unregistered 456789' ] ] )
			->execute();
	}
}
