<?php

namespace MediaWiki\Tests\Maintenance;

use DeleteAutoPatrolLogs;

/**
 * @group Database
 * @covers DeleteAutoPatrolLogs
 */
class DeleteAutoPatrolLogsTest extends MaintenanceBaseTestCase {

	public function getMaintenanceClass() {
		return DeleteAutoPatrolLogs::class;
	}

	public function setUp() {
		parent::setUp();
		$this->tablesUsed = [ 'logging' ];

		$this->cleanLoggingTable();
		$this->insertLoggingData();
	}

	private function cleanLoggingTable() {
		wfGetDB( DB_MASTER )->delete( 'logging', '*' );
	}

	private function insertLoggingData() {
		$logs = [];

		// Manual patrolling
		$logs[] = [
			'log_type' => 'patrol',
			'log_action' => 'patrol',
			'log_user' => 7251,
			'log_params' => '',
			'log_timestamp' => 20041223210426
		];

		// Autopatrol #1
		$logs[] = [
			'log_type' => 'patrol',
			'log_action' => 'autopatrol',
			'log_user' => 7252,
			'log_params' => '',
			'log_timestamp' => 20051223210426
		];

		// Block
		$logs[] = [
			'log_type' => 'block',
			'log_action' => 'block',
			'log_user' => 7253,
			'log_params' => '',
			'log_timestamp' => 20061223210426
		];

		// Very old/ invalid patrol
		$logs[] = [
			'log_type' => 'patrol',
			'log_action' => 'patrol',
			'log_user' => 7253,
			'log_params' => 'nanana',
			'log_timestamp' => 20061223210426
		];

		// Autopatrol #2
		$logs[] = [
			'log_type' => 'patrol',
			'log_action' => 'autopatrol',
			'log_user' => 7254,
			'log_params' => '',
			'log_timestamp' => 20071223210426
		];

		// Autopatrol #3 old way
		$logs[] = [
			'log_type' => 'patrol',
			'log_action' => 'patrol',
			'log_user' => 7255,
			'log_params' => serialize( [ '6::auto' => true ] ),
			'log_timestamp' => 20081223210426
		];

		// Manual patrol #2 old way
		$logs[] = [
			'log_type' => 'patrol',
			'log_action' => 'patrol',
			'log_user' => 7256,
			'log_params' => serialize( [ '6::auto' => false ] ),
			'log_timestamp' => 20091223210426
		];

		wfGetDB( DB_MASTER )->insert( 'logging', $logs );
	}

	public function runProvider() {
		$allRows = [
			(object)[
				'log_type' => 'patrol',
				'log_action' => 'patrol',
				'log_user' => '7251',
			],
			(object)[
				'log_type' => 'patrol',
				'log_action' => 'autopatrol',
				'log_user' => '7252',
			],
			(object)[
				'log_type' => 'block',
				'log_action' => 'block',
				'log_user' => '7253',
			],
			(object)[
				'log_type' => 'patrol',
				'log_action' => 'patrol',
				'log_user' => '7253',
			],
			(object)[
				'log_type' => 'patrol',
				'log_action' => 'autopatrol',
				'log_user' => '7254',
			],
			(object)[
				'log_type' => 'patrol',
				'log_action' => 'patrol',
				'log_user' => '7255',
			],
			(object)[
				'log_type' => 'patrol',
				'log_action' => 'patrol',
				'log_user' => '7256',
			],
		];

		$cases = [
			'dry run' => [
				$allRows,
				[ '--sleep', '0', '--dry-run', '-q' ]
			],
			'basic run' => [
				[
					$allRows[0],
					$allRows[2],
					$allRows[3],
					$allRows[5],
					$allRows[6],
				],
				[ '--sleep', '0', '-q' ]
			],
			'run with before' => [
				[
					$allRows[0],
					$allRows[2],
					$allRows[3],
					$allRows[4],
					$allRows[5],
					$allRows[6],
				],
				[ '--sleep', '0', '--before', '20060123210426', '-q' ]
			],
			'run with check-old' => [
				[
					$allRows[0],
					$allRows[1],
					$allRows[2],
					$allRows[3],
					$allRows[4],
					$allRows[6],
				],
				[ '--sleep', '0', '--check-old', '-q' ]
			],
		];

		foreach ( $cases as $key => $case ) {
			yield $key . '-batch-size-1' => [
				$case[0],
				array_merge( $case[1], [ '--batch-size', '1' ] )
			];
			yield $key . '-batch-size-5' => [
				$case[0],
				array_merge( $case[1], [ '--batch-size', '5' ] )
			];
			yield $key . '-batch-size-1000' => [
				$case[0],
				array_merge( $case[1], [ '--batch-size', '1000' ] )
			];
		}
	}

	/**
	 * @dataProvider runProvider
	 */
	public function testRun( $expected, $args ) {
		$this->maintenance->loadWithArgv( $args );

		$this->maintenance->execute();

		$remainingLogs = wfGetDB( DB_REPLICA )->select(
			[ 'logging' ],
			[ 'log_type', 'log_action', 'log_user' ],
			[],
			__METHOD__,
			[ 'ORDER BY' => 'log_id' ]
		);

		$this->assertEquals( $expected, iterator_to_array( $remainingLogs, false ) );
	}

	public function testFromId() {
		$fromId = wfGetDB( DB_REPLICA )->selectField(
			'logging',
			'log_id',
			[ 'log_params' => 'nanana' ]
		);

		$this->maintenance->loadWithArgv( [ '--sleep', '0', '--from-id', strval( $fromId ), '-q' ] );

		$this->maintenance->execute();

		$remainingLogs = wfGetDB( DB_REPLICA )->select(
			[ 'logging' ],
			[ 'log_type', 'log_action', 'log_user' ],
			[],
			__METHOD__,
			[ 'ORDER BY' => 'log_id' ]
		);

		$deleted = [
			'log_type' => 'patrol',
			'log_action' => 'autopatrol',
			'log_user' => '7254',
		];
		$notDeleted = [
			'log_type' => 'patrol',
			'log_action' => 'autopatrol',
			'log_user' => '7252',
		];

		$remainingLogs = array_map(
			function ( $val ) {
				return (array)$val;
			},
			iterator_to_array( $remainingLogs, false )
		);

		$this->assertNotContains( $deleted, $remainingLogs );
		$this->assertContains( $notDeleted, $remainingLogs );
	}

}
