<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\User\Options\StaticUserOptionsLookup;
use UserOptionsMaintenance;

/**
 * @covers \UserOptionsMaintenance
 * @group Database
 * @author Dreamy Jazz
 */
class UserOptionsMaintenanceTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return UserOptionsMaintenance::class;
	}

	/** @dataProvider provideExecuteForFatalError */
	public function testExecuteForFatalError( $options, $optionNameArg, $expectedOutputRegex ) {
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->maintenance->setArg( 0, $optionNameArg );
		$this->maintenance->getParameters()->setName( 'userOptions.php' );
		$this->expectCallToFatalError();
		$this->expectOutputRegex( $expectedOutputRegex );
		$this->maintenance->execute();
	}

	public static function provideExecuteForFatalError() {
		return [
			'--delete-defaults with no option argument' => [
				[ 'delete-defaults' => 1 ], null, '/Option name is required/',
			],
			'--usage with invalid option argument' => [ [ 'usage' => 1 ], 'invalidoption', '/Invalid user option/' ],
			'No options provided' => [
				[], 'option',
				// Check that the description is outputted, as this is the start of the help output
				'/Pass through all users and change or delete one of their options/',
			],
		];
	}

	public function testListOptions() {
		$this->setService( 'UserOptionsLookup', new StaticUserOptionsLookup(
			[], [ 'requireemail' => 1, 'disablemail' => 0 ]
		) );
		$this->maintenance->setOption( 'list', 1 );
		$this->maintenance->execute();
		$this->expectOutputString( "disablemail : 0\nrequireemail: 1\n" );
	}

	/** @dataProvider provideShowUsageStats */
	public function testShowUsageStats( $optionArgName, $expectedOutputString ) {
		$testUser1 = $this->getMutableTestUser()->getUserIdentity();
		$testUser2 = $this->getMutableTestUser()->getUserIdentity();
		$this->setService( 'UserOptionsLookup', new StaticUserOptionsLookup(
			[
				$testUser1->getName() => [ 'requireemail' => 0 ],
				$testUser2->getName() => [ 'disablemail' => 1 ],
			],
			[ 'requireemail' => 1, 'disablemail' => 0 ]
		) );
		$this->maintenance->setOption( 'usage', 1 );
		$this->maintenance->setArg( 0, $optionArgName );
		$this->maintenance->execute();
		$this->expectOutputString( $expectedOutputString );
	}

	public static function provideShowUsageStats() {
		return [
			'All options' => [
				null,
				"Usage for <requireemail> (default: '1'):\n 1 user(s): '0'\n\n" .
				"Usage for <disablemail> (default: '0'):\n 1 user(s): '1'\n\n",
			],
			'Only the "requireemail" option' => [
				'requireemail',
				"Usage for <requireemail> (default: '1'):\n 1 user(s): '0'\n\n",
			],
		];
	}
}
