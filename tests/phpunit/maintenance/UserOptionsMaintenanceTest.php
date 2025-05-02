<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\MainConfigNames;
use MediaWiki\User\Options\StaticUserOptionsLookup;
use MediaWiki\User\UserIdentity;
use RuntimeException;
use UserOptionsMaintenance;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \UserOptionsMaintenance
 * @group Database
 * @author Dreamy Jazz
 */
class UserOptionsMaintenanceTest extends MaintenanceBaseTestCase {

	private static UserIdentity $firstTestUser;
	private static UserIdentity $secondTestUser;
	private static UserIdentity $thirdTestUser;
	private static UserIdentity $fourthTestUser;
	private static UserIdentity $fifthTestUser;
	private static UserIdentity $sixthTestUser;

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
			'-delete-defaults with dry run set' => [
				[ 'delete-defaults' => 1, 'dry' => 1 ], 'preference-one',
				'/delete-defaults does not support a dry run/',
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
		$this->setService( 'UserOptionsLookup', new StaticUserOptionsLookup(
			[
				self::$firstTestUser->getName() => [ 'requireemail' => 0 ],
				self::$secondTestUser->getName() => [ 'disablemail' => 1 ],
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

	private function insertTestingPreferencesData() {
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'user_properties' )
			->rows( [
				[ 'up_user' => self::$firstTestUser->getId(), 'up_property' => 'preference-one', 'up_value' => 'first-value' ],
				[ 'up_user' => self::$secondTestUser->getId(), 'up_property' => 'preference-one', 'up_value' => 'second-value' ],
				[ 'up_user' => self::$thirdTestUser->getId(), 'up_property' => 'preference-one', 'up_value' => '1' ],
				[ 'up_user' => self::$fourthTestUser->getId(), 'up_property' => 'preference-one', 'up_value' => '0' ],
				[ 'up_user' => self::$fifthTestUser->getId(), 'up_property' => 'preference-one', 'up_value' => '0' ],
				[ 'up_user' => self::$sixthTestUser->getId(), 'up_property' => 'preference-one', 'up_value' => null ],
				[ 'up_user' => self::$fourthTestUser->getId(), 'up_property' => 'preference-two', 'up_value' => 'ignored' ],
			] )
			->caller( __METHOD__ )
			->execute();
	}

	/** @dataProvider provideExecuteForDeletingOptions */
	public function testExecuteForDeletingOptions( callable $optionsCallback, $expectedOutputString, callable $expectedRowsCallback ) {
		$this->insertTestingPreferencesData();
		// Run the maintenance script
		foreach ( $optionsCallback() as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->maintenance->setOption( 'delete', 1 );
		$this->maintenance->setOption( 'nowarn', 1 );
		$this->maintenance->setArg( 0, 'preference-one' );
		$this->maintenance->execute();
		// Check that the maintenance script executed as intended by asserting that the user_properties table is
		// as expected.
		$this->expectOutputString( $expectedOutputString );
		$this->newSelectQueryBuilder()
			->select( [ 'up_property', 'up_user', 'up_value' ] )
			->from( 'user_properties' )
			->caller( __METHOD__ )
			->assertResultSet( $expectedRowsCallback() );
	}

	public static function provideExecuteForDeletingOptions() {
		return [
			'Deleting all values for preference-one' => [
				static fn () =>  [], "Done! Deleted 6 rows.\n",
				static fn () =>  [ [ 'preference-two', self::$fourthTestUser->getId(), 'ignored' ] ],
			],
			'Deleting all values for preference-one but dry run' => [
				static fn () =>  [ 'dry' => 1 ],
				"Would delete 6 rows.\n",
				static fn () =>  [
					[ 'preference-one', self::$firstTestUser->getId(), 'first-value' ],
					[ 'preference-one', self::$secondTestUser->getId(), 'second-value' ],
					[ 'preference-one', self::$thirdTestUser->getId(), '1' ],
					[ 'preference-one', self::$fourthTestUser->getId(), '0' ],
					[ 'preference-one', self::$fifthTestUser->getId(), '0' ],
					[ 'preference-one', self::$sixthTestUser->getId(), null ],
					[ 'preference-two', self::$fourthTestUser->getId(), 'ignored' ],
				],
			],
			'Deleting one specific value for preference-one' => [
				static fn () =>  [ 'old' => [ 'second-value' ] ],
				"Done! Deleted 1 rows.\n",
				static fn () =>  [
					[ 'preference-one', self::$firstTestUser->getId(), 'first-value' ],
					[ 'preference-one', self::$thirdTestUser->getId(), '1' ],
					[ 'preference-one', self::$fourthTestUser->getId(), '0' ],
					[ 'preference-one', self::$fifthTestUser->getId(), '0' ],
					[ 'preference-one', self::$sixthTestUser->getId(), null ],
					[ 'preference-two', self::$fourthTestUser->getId(), 'ignored' ],
				],
			],
			'Deleting all values for preference-one for specific users' => [
				static fn () =>  [ 'fromuserid' => 0, 'touserid' => self::$fifthTestUser->getId() ],
				"Done! Deleted 4 rows.\n",
				static fn () =>  [
					[ 'preference-one', self::$fifthTestUser->getId(), '0' ],
					[ 'preference-one', self::$sixthTestUser->getId(), null ],
					[ 'preference-two', self::$fourthTestUser->getId(), 'ignored' ],
				],
			],
			'Deleting one specific value for preference-one that no user has' => [
				static fn () =>  [ 'old' => 'unknown' ],
				"Done! Deleted 0 rows.\n",
				static fn () =>  [
					[ 'preference-one', self::$firstTestUser->getId(), 'first-value' ],
					[ 'preference-one', self::$secondTestUser->getId(), 'second-value' ],
					[ 'preference-one', self::$thirdTestUser->getId(), '1' ],
					[ 'preference-one', self::$fourthTestUser->getId(), '0' ],
					[ 'preference-one', self::$fifthTestUser->getId(), '0' ],
					[ 'preference-one', self::$sixthTestUser->getId(), null ],
					[ 'preference-two', self::$fourthTestUser->getId(), 'ignored' ],
				],
			],
		];
	}

	/** @dataProvider provideExecuteForDeletingDefaults */
	public function testExecuteForDeletingDefaults( callable $optionsCallback, callable $expectedRowsCallback ) {
		$this->overrideConfigValue( MainConfigNames::DefaultUserOptions, [ 'preference-one' => 0 ] );
		$this->insertTestingPreferencesData();
		// Run the maintenance script
		foreach ( $optionsCallback() as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->maintenance->setOption( 'delete-defaults', 1 );
		$this->maintenance->setOption( 'nowarn', 1 );
		$this->maintenance->setArg( 0, 'preference-one' );
		$this->maintenance->execute();
		// Check that the maintenance script executed as intended by asserting that the user_properties table is
		// as expected.
		$this->expectOutputString( "Done!\n" );
		$this->newSelectQueryBuilder()
			->select( [ 'up_property', 'up_user', 'up_value' ] )
			->from( 'user_properties' )
			->caller( __METHOD__ )
			->assertResultSet( $expectedRowsCallback() );
	}

	public static function provideExecuteForDeletingDefaults() {
		return [
			'Deleting defaults for preference-one' => [
				static fn () =>  [],
				static fn () =>  [
					[ 'preference-one', self::$firstTestUser->getId(), 'first-value' ],
					[ 'preference-one', self::$secondTestUser->getId(), 'second-value' ],
					[ 'preference-one', self::$thirdTestUser->getId(), '1' ],
					[ 'preference-two', self::$fourthTestUser->getId(), 'ignored' ],
				],
			],
			'Deleting defaults for preference-one but limited to specific user IDs' => [
				static fn () =>  [
					'fromuserid' => self::$fourthTestUser->getId(),
					'touserid' => self::$fifthTestUser->getId(),
				],
				static fn () =>  [
					[ 'preference-one', self::$firstTestUser->getId(), 'first-value' ],
					[ 'preference-one', self::$secondTestUser->getId(), 'second-value' ],
					[ 'preference-one', self::$thirdTestUser->getId(), '1' ],
					[ 'preference-one', self::$fourthTestUser->getId(), '0' ],
					[ 'preference-one', self::$sixthTestUser->getId(), null ],
					[ 'preference-two', self::$fourthTestUser->getId(), 'ignored' ],
				],
			],
		];
	}

	/** @dataProvider provideExecuteForUpdatingOptions */
	public function testExecuteForUpdatingOptions(
		callable $optionsCallback, callable $expectedOutputRegexCallback, callable $expectedRowsCallback
	) {
		$this->insertTestingPreferencesData();
		// Run the maintenance script
		foreach ( $optionsCallback() as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->maintenance->setOption( 'nowarn', 1 );
		$this->maintenance->setArg( 0, 'preference-one' );
		$this->maintenance->execute();
		$this->expectOutputRegex( $expectedOutputRegexCallback() );
		// Check that the maintenance script executed as intended by asserting that the user_properties table is
		// as expected.
		$this->newSelectQueryBuilder()
			->select( [ 'up_property', 'up_user', 'up_value' ] )
			->from( 'user_properties' )
			->caller( __METHOD__ )
			->assertResultSet( $expectedRowsCallback() );
	}

	public static function provideExecuteForUpdatingOptions() {
		return [
			'Updating first-value to first-value-new for preference-one' => [
				static fn () =>  [ 'new' => 'first-value-new', 'old' => [ 'first-value' ] ],
				static fn () =>  '/Setting preference-one for ' . preg_quote( self::$firstTestUser->getName() ) .
					' from \'first-value\' to \'first-value-new\'/',
				static fn () =>  [
					[ 'preference-one', self::$firstTestUser->getId(), 'first-value-new' ],
					[ 'preference-one', self::$secondTestUser->getId(), 'second-value' ],
					[ 'preference-one', self::$thirdTestUser->getId(), '1' ],
					[ 'preference-one', self::$fourthTestUser->getId(), '0' ],
					[ 'preference-one', self::$fifthTestUser->getId(), '0' ],
					[ 'preference-one', self::$sixthTestUser->getId(), null ],
					[ 'preference-two', self::$fourthTestUser->getId(), 'ignored' ],
				],
			],
			'Updating all preference values to 1' => [
				static fn () =>  [ 'new' => '1', 'old' => [ 'first-value', 'second-value', '0', null ] ],
				static fn () =>  '/Setting preference-one for ' . preg_quote( self::$firstTestUser->getName() ) .
					' from \'first-value\' to \'1\'/',
				static fn () =>  [
					[ 'preference-one', self::$firstTestUser->getId(), '1' ],
					[ 'preference-one', self::$secondTestUser->getId(), '1' ],
					[ 'preference-one', self::$thirdTestUser->getId(), '1' ],
					[ 'preference-one', self::$fourthTestUser->getId(), '1' ],
					[ 'preference-one', self::$fifthTestUser->getId(), '1' ],
					[ 'preference-one', self::$sixthTestUser->getId(), '1' ],
					[ 'preference-two', self::$fourthTestUser->getId(), 'ignored' ],
				],
			],
			'Updating all preference values to 1 but dry run' => [
				static fn () =>  [ 'new' => '1', 'old' => [ 'first-value', 'second-value', '0', null ], 'dry' => 1 ],
				static fn () =>  '/Would set preference-one for ' . preg_quote( self::$firstTestUser->getName() ) .
					' from \'first-value\' to \'1\'/',
				static fn () =>  [
					[ 'preference-one', self::$firstTestUser->getId(), 'first-value' ],
					[ 'preference-one', self::$secondTestUser->getId(), 'second-value' ],
					[ 'preference-one', self::$thirdTestUser->getId(), '1' ],
					[ 'preference-one', self::$fourthTestUser->getId(), '0' ],
					[ 'preference-one', self::$fifthTestUser->getId(), '0' ],
					[ 'preference-one', self::$sixthTestUser->getId(), null ],
					[ 'preference-two', self::$fourthTestUser->getId(), 'ignored' ],
				],
			],
			'Updating all preference values to 1 for specific user ID range' => [
				static fn () =>  [
					'new' => '1', 'old' => [ 'first-value', 'second-value', '0', null ],
					'fromuserid' => self::$secondTestUser->getId(), 'touserid' => self::$fifthTestUser->getId(),
				],
				static fn () =>  '/Setting preference-one for ' . preg_quote( self::$secondTestUser->getName() ) .
					' from \'second-value\' to \'1\'/',
				static fn () =>  [
					[ 'preference-one', self::$firstTestUser->getId(), 'first-value' ],
					[ 'preference-one', self::$secondTestUser->getId(), '1' ],
					[ 'preference-one', self::$thirdTestUser->getId(), '1' ],
					[ 'preference-one', self::$fourthTestUser->getId(), '1' ],
					[ 'preference-one', self::$fifthTestUser->getId(), '1' ],
					[ 'preference-one', self::$sixthTestUser->getId(), null ],
					[ 'preference-two', self::$fourthTestUser->getId(), 'ignored' ],
				],
			],
			'Updating preference values to 1 using old-is-default' => [
				static fn () =>  [ 'new' => '1', 'old' => [ null ], 'old-is-default' => 1 ],
				static fn () =>  '/Setting preference-one for ' . preg_quote( self::$sixthTestUser->getName() ) .
					" from '' to '1'/",
				static fn () =>  [
					[ 'preference-one', self::$firstTestUser->getId(), 'first-value' ],
					[ 'preference-one', self::$secondTestUser->getId(), 'second-value' ],
					[ 'preference-one', self::$thirdTestUser->getId(), '1' ],
					[ 'preference-one', self::$fourthTestUser->getId(), '0' ],
					[ 'preference-one', self::$fifthTestUser->getId(), '0' ],
					[ 'preference-one', self::$sixthTestUser->getId(), '1' ],
					[ 'preference-two', self::$fourthTestUser->getId(), 'ignored' ],
				],
			],
			'Updating preference values when --old-is-default is set but --from does not include null' => [
				static fn () => [ 'new' => '1', 'old' => [ 'abc' ], 'old-is-default' => 1 ],
				static fn () => '/Skipping preference-one for ' . preg_quote( self::$sixthTestUser->getName() ) .
					' as the default value for that user is not specified in --from/',
				static fn () => [
					[ 'preference-one', self::$firstTestUser->getId(), 'first-value' ],
					[ 'preference-one', self::$secondTestUser->getId(), 'second-value' ],
					[ 'preference-one', self::$thirdTestUser->getId(), '1' ],
					[ 'preference-one', self::$fourthTestUser->getId(), '0' ],
					[ 'preference-one', self::$fifthTestUser->getId(), '0' ],
					[ 'preference-one', self::$sixthTestUser->getId(), null ],
					[ 'preference-two', self::$fourthTestUser->getId(), 'ignored' ],
				],
			],
		];
	}

	/** @dataProvider provideCallsCountDownForWriteOperations */
	public function testCallsCountDownForWriteOperations( $options ) {
		// Create a partially mocked instance of the maintenance script we are testing that has ::countDown
		// mocked to expect a call and not perform the sleep (to avoid a slow test).
		$mockMaintenance = $this->getMockBuilder( UserOptionsMaintenance::class )
			->onlyMethods( [ 'countDown' ] )
			->getMock();
		$exception = new RuntimeException(
			"Test exception to simulate a user exiting the script during the count down."
		);
		$mockMaintenance->expects( $this->once() )
			->method( 'countDown' )
			->with( 5 )
			->willThrowException( $exception );
		$this->maintenance = TestingAccessWrapper::newFromObject( $mockMaintenance );
		// Run the maintenance script
		$this->maintenance->setArg( 0, 'preference-one' );
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->expectExceptionObject( $exception );
		$this->maintenance->execute();
	}

	public static function provideCallsCountDownForWriteOperations() {
		return [
			'--delete option provided' => [ [ 'delete' => 1 ] ],
			'updating options' => [ [ 'old' => [ 'a' ], 'new' => 'b' ] ],
			'--delete-defaults provided' => [ [ 'delete-defaults' => 1 ] ],
		];
	}

	public function addDBDataOnce() {
		self::$firstTestUser = $this->getMutableTestUser()->getUserIdentity();
		self::$secondTestUser = $this->getMutableTestUser()->getUserIdentity();
		self::$thirdTestUser = $this->getMutableTestUser()->getUserIdentity();
		self::$fourthTestUser = $this->getMutableTestUser()->getUserIdentity();
		self::$fifthTestUser = $this->getMutableTestUser()->getUserIdentity();
		self::$sixthTestUser = $this->getMutableTestUser()->getUserIdentity();
	}
}
