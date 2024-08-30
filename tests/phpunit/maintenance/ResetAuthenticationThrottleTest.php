<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use ResetAuthenticationThrottle;

/**
 * @covers \ResetAuthenticationThrottle
 * @group Database
 * @author Dreamy Jazz
 */
class ResetAuthenticationThrottleTest extends MaintenanceBaseTestCase {

	use TempUserTestTrait;

	public function getMaintenanceClass() {
		return ResetAuthenticationThrottle::class;
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
			'No options' => [
				[], '/At least one of --login, --signup, --tempaccount, or --tempaccountnameacquisition is required/',
			],
			'--signup but no IP' => [ [ 'signup' => 1 ], '/--ip is required/' ],
			'Invalid --ip argument' => [ [ 'signup' => 1, 'ip' => 'abcef' ], '/Not a valid IP/' ],
			'Invalid --user argument' => [
				[ 'login' => 1, 'user' => 'Template:Testing#test', 'ip' => '1.2.3.4' ], '/Not a valid username/',
			],
		];
	}

	public function testClearTempAccountNameAcquisitionThrottle() {
		$this->enableAutoCreateTempUser();
		$this->overrideConfigValue( MainConfigNames::TempAccountNameAcquisitionThrottle, [
			'count' => 1,
			'seconds' => 86400,
		] );
		$request = RequestContext::getMain()->getRequest();
		$request->setIP( '1.2.3.4' );
		$temporaryAccountCreator = $this->getServiceContainer()->getTempUserCreator();
		// Acquire a temporary account name to increase the throttle counter
		$this->assertNotNull( $temporaryAccountCreator->acquireAndStashName( $request->getSession() ) );
		// Verify that a second call does not get a name
		$request->getSession()->clear();
		$this->assertNull( $temporaryAccountCreator->acquireAndStashName( $request->getSession() ) );
		// Run the maintenance script to clear the throttle
		$this->maintenance->setOption( 'tempaccountnameacquisition', 1 );
		$this->maintenance->setOption( 'ip', '1.2.3.4' );
		$this->maintenance->execute();
		$this->expectOutputRegex( '/Clearing temp account name acquisition throttle.*done/' );
		// Verify that a third call works now
		$this->assertNotNull( $temporaryAccountCreator->acquireAndStashName( $request->getSession() ) );
	}

	public function testClearTempAccountNameAcquisitionThrottleOnNoThrottle() {
		// Disable the temporary account name acquisition throttle
		$this->overrideConfigValue( MainConfigNames::TempAccountNameAcquisitionThrottle, [] );
		// Run the maintenance script
		$this->maintenance->setOption( 'tempaccountnameacquisition', 1 );
		$this->maintenance->setOption( 'ip', '1.2.3.4' );
		$this->maintenance->execute();
		// Verify that the script finds no throttle set
		$this->expectOutputRegex( '/Clearing temp account name acquisition throttle.*none set/' );
	}

	public function testClearTempAccountCreationThrottle() {
		$this->enableAutoCreateTempUser();
		$this->overrideConfigValue( MainConfigNames::TempAccountCreationThrottle, [
			'count' => 1,
			'seconds' => 86400,
		] );
		$request = RequestContext::getMain()->getRequest();
		$request->setIP( '1.2.3.4' );
		$temporaryAccountCreator = $this->getServiceContainer()->getTempUserCreator();
		// Acquire a temporary account to increase the throttle counter
		$this->assertStatusGood( $temporaryAccountCreator->create( null, $request ) );
		// Verify that a second call does not get a name
		$request->getSession()->clear();
		$this->assertStatusError(
			'acct_creation_throttle_hit', $temporaryAccountCreator->create( null, $request )
		);
		// Run the maintenance script to clear the throttle
		$this->maintenance->setOption( 'tempaccount', 1 );
		$this->maintenance->setOption( 'ip', '1.2.3.4' );
		$this->maintenance->execute();
		$this->expectOutputRegex( '/Clearing temp account creation throttle.*done/' );
		// Verify that a third call works now
		$this->assertStatusGood( $temporaryAccountCreator->create( null, $request ) );
	}

	public function testClearTempAccountCreationThrottleOnNoThrottle() {
		// Disable the temporary account creation throttle
		$this->overrideConfigValue( MainConfigNames::TempAccountCreationThrottle, [] );
		// Run the maintenance script
		$this->maintenance->setOption( 'tempaccount', 1 );
		$this->maintenance->setOption( 'ip', '1.2.3.4' );
		$this->maintenance->execute();
		// Verify that the script finds no throttle set
		$this->expectOutputRegex( '/Clearing temp account creation throttle.*none set/' );
	}
}
