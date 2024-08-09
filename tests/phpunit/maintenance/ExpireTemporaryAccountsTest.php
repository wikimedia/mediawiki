<?php

namespace MediaWiki\Tests\Maintenance;

use ExpireTemporaryAccounts;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;

/**
 * @covers \ExpireTemporaryAccounts
 * @group Database
 * @author Dreamy Jazz
 */
class ExpireTemporaryAccountsTest extends MaintenanceBaseTestCase {
	use TempUserTestTrait;

	protected function getMaintenanceClass() {
		return ExpireTemporaryAccounts::class;
	}

	public function testExecuteWhenTemporaryAccountsNotKnown() {
		$this->disableAutoCreateTempUser();
		$this->expectOutputRegex( '/Temporary accounts are disabled/' );
		$this->maintenance->execute();
	}

	public function testExecuteWhenTemporaryAccountsNeverExpire() {
		$this->enableAutoCreateTempUser( [ 'expireAfterDays' => null, 'notifyBeforeExpirationDays' => null ] );
		$this->expectOutputRegex( '/Temporary account expiry is not enabled/' );
		$this->maintenance->execute();
	}

	public function testExecuteWithNoExistingTemporaryAccounts() {
		// Create a no-op mock AuthManager, as no accounts should be expired by the script.
		$this->setService( 'AuthManager', $this->createNoOpMock( AuthManager::class ) );
		$this->enableAutoCreateTempUser();
		$this->expectOutputRegex( '/Revoked access for 0 temporary users/' );
		$this->maintenance->execute();
	}
}
