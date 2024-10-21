<?php

namespace MediaWiki\Tests\Maintenance\Includes;

use MediaWiki\Maintenance\LoggedUpdateMaintenance;
use MediaWiki\Tests\Maintenance\MaintenanceBaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Maintenance\LoggedUpdateMaintenance
 * @author Dreamy Jazz
 * @group Database
 */
class LoggedUpdateMaintenanceTest extends MaintenanceBaseTestCase {

	/** @var LoggedUpdateMaintenance|MockObject */
	protected $maintenance;

	protected function getMaintenanceClass() {
		return LoggedUpdateMaintenance::class;
	}

	protected function createMaintenance() {
		$obj = $this->getMockBuilder( $this->getMaintenanceClass() )
			->onlyMethods( [ 'getUpdateKey', 'doDBUpdates' ] )
			->getMockForAbstractClass();

		// We use TestingAccessWrapper in order to access protected internals
		// such as `output()`.
		return TestingAccessWrapper::newFromObject( $obj );
	}

	/** @dataProvider provideForcedValues */
	public function testSetForce( $value ) {
		$this->maintenance->setForce( $value );
		$this->assertSame( $value, $this->maintenance->getParameters()->getOption( 'force' ) );
	}

	public static function provideForcedValues() {
		return [
			'--force set' => [ true ],
			'--force not set' => [ null ],
		];
	}

	/** @dataProvider provideExecute */
	public function testExecute(
		$doDBUpdatesReturnValue, $markedAsCompleteBeforeRun, $shouldBeMarkedAsCompleteAfterExecution, $force,
		$expectedReturnValueFromExecute, $expectedOutputRegex = false
	) {
		// If marked as complete before the run, manually add the key into the updatelog table
		if ( $markedAsCompleteBeforeRun ) {
			$this->getDb()->newInsertQueryBuilder()
				->insertInto( 'updatelog' )
				->row( [ 'ul_key' => 'test' ] )
				->execute();
		}
		// Set if --force is specified and also mock the return value of ::doDBUpdates
		$this->maintenance->setForce( $force );
		$this->maintenance->method( 'doDBUpdates' )
			->willReturn( $doDBUpdatesReturnValue );
		$this->maintenance->method( 'getUpdateKey' )
			->willReturn( 'test' );
		// Run the maintenance script and then assert that the updatelog table is as expected
		$this->assertSame( $expectedReturnValueFromExecute, $this->maintenance->execute() );
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'updatelog' )
			->where( [ 'ul_key' => 'test' ] )
			->assertFieldValue( (int)$shouldBeMarkedAsCompleteAfterExecution );
		if ( $expectedOutputRegex ) {
			$this->expectOutputRegex( $expectedOutputRegex );
		} else {
			$this->expectOutputString( '' );
		}
	}

	public static function provideExecute() {
		return [
			'Update has been run before' => [
				'doDBUpdatesReturnValue' => false,
				'markedAsCompleteBeforeRun' => true,
				'shouldBeMarkedAsCompleteAfterExecution' => true,
				'force' => null,
				'expectedReturnValueFromExecute' => true,
				'expectedOutputRegex' => "/Update 'test' already logged as completed/"
			],
			'Update has been run before, but force provided and update fails' => [
				'doDBUpdatesReturnValue' => false,
				'markedAsCompleteBeforeRun' => true,
				'shouldBeMarkedAsCompleteAfterExecution' => true,
				'force' => true,
				'expectedReturnValueFromExecute' => false,
			],
			'Update has never been run before and update succeeds' => [
				'doDBUpdatesReturnValue' => true,
				'markedAsCompleteBeforeRun' => false,
				'shouldBeMarkedAsCompleteAfterExecution' => true,
				'force' => null,
				'expectedReturnValueFromExecute' => true,
			],
			'Update has never been run before and update fails' => [
				'doDBUpdatesReturnValue' => false,
				'markedAsCompleteBeforeRun' => false,
				'shouldBeMarkedAsCompleteAfterExecution' => false,
				'force' => false,
				'expectedReturnValueFromExecute' => false,
			],
		];
	}
}
