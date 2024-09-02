<?php

namespace MediaWiki\Tests\Maintenance;

use Protect;

/**
 * @covers \Protect
 * @group Database
 * @author Dreamy Jazz
 */
class ProtectTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return Protect::class;
	}

	/** @dataProvider provideExecute */
	public function testExecute( $options, $expectedProtectionLevel ) {
		$testPage = $this->getExistingTestPage();
		// Set the options from $options.
		foreach ( $options as $name => $option ) {
			$this->maintenance->setOption( $name, $option );
		}
		// Call ::execute
		$this->maintenance->setArg( 'title', $testPage );
		$this->maintenance->execute();
		// Verify that the specified protection level has been applied
		$this->expectOutputString( "Updating protection status..." . "done\n" );
		$resultingPageRestrictions = $this->getServiceContainer()->getRestrictionStore()
			->getAllRestrictions( $testPage );
		foreach ( $resultingPageRestrictions as $restrictions ) {
			$this->assertContains( $expectedProtectionLevel, $restrictions );
		}
	}

	public static function provideExecute() {
		return [
			'Sysop protection' => [ [], 'sysop' ],
			'Autoconfirmed protection' => [ [ 'semiprotect' => 1 ], 'autoconfirmed' ],
		];
	}

	public function testExecuteForUnprotect() {
		$testPage = $this->getExistingTestPage();
		// Protect the test page so that we can unprotect it
		$cascade = false;
		$testPage->doUpdateRestrictions(
			[ 'edit' => 'sysop', 'move' => 'sysop' ], [], $cascade, '', $this->getTestSysop()->getUser()
		);
		// Verify that the specified protection level has been applied
		$resultingPageRestrictions = $this->getServiceContainer()->getRestrictionStore()
			->getAllRestrictions( $testPage );
		foreach ( $resultingPageRestrictions as $restrictions ) {
			$this->assertContains( 'sysop', $restrictions );
		}
		// Call ::execute to unprotect the page
		$this->maintenance->setArg( 'title', $testPage );
		$this->maintenance->setOption( 'unprotect', 1 );
		$this->maintenance->execute();
		// Verify that the specified protection level has been applied
		$this->expectOutputString( "Updating protection status...done\n" );
		$resultingPageRestrictions = $this->getServiceContainer()->getRestrictionStore()
			->getAllRestrictions( $testPage );
		foreach ( $resultingPageRestrictions as $restrictions ) {
			$this->assertCount( 0, $restrictions );
		}
	}

	public function testExecuteWhenReadOnly() {
		// Get a test page and then add it as an argument to the maintenance script
		$testPage = $this->getExistingTestPage();
		$this->maintenance->setArg( 'title', $testPage );
		// Enable read-only mode
		$this->getServiceContainer()->getReadOnlyMode()->setReason( 'test' );
		// Call ::execute
		$this->maintenance->execute();
		// Verify that the updating the protection status failed.
		$this->expectOutputString( "Updating protection status..." . "failed\n" );
	}

	public function testExecuteOnInvalidUserOption() {
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Invalid username/' );
		$this->maintenance->setOption( 'user', 'Template:Testing#test' );
		$this->maintenance->setArg( 'title', 'unused-for-this-test' );
		$this->maintenance->execute();
	}

	public function testExecuteOnInvalidTitleArgument() {
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Invalid title/' );
		$this->maintenance->setArg( 'title', ':::' );
		$this->maintenance->execute();
	}
}
