<?php

namespace MediaWiki\Tests\Maintenance;

/**
 * @group Database
 * @covers DeletePatrolLogs
 */
class DeletePatrolLogsTest extends MaintenanceBaseTestCase {

	public function getMaintenanceClass() {
		return \DeleteAutoPatrolLogs::class;
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

		// Autopatrol #2
		$logs[] = [
			'log_type' => 'patrol',
			'log_action' => 'autopatrol',
			'log_user' => 7254,
			'log_params' => '',
			'log_timestamp' => 20071223210426
		];

		wfGetDB( DB_MASTER )->insert( 'logging', $logs );
	}

	public function testBasicRun() {
		$this->maintenance->loadWithArgv( [ '--sleep', '0', '-q' ] );

		$this->maintenance->execute();

		$remainingScores = wfGetDB( DB_REPLICA )->select(
			[ 'logging' ],
			[ 'log_type', 'log_action', 'log_user' ],
			[],
			__METHOD__,
			[ 'ORDER BY' => 'log_id' ]
		);

		$expected = [
			(object)[
				'log_type' => 'patrol',
				'log_action' => 'patrol',
				'log_user' => '7251',
			],
			(object)[
				'log_type' => 'block',
				'log_action' => 'block',
				'log_user' => '7253',
			],
		];
		$this->assertEquals( $expected, iterator_to_array( $remainingScores, false ) );
	}

	public function testDryRun() {
		$this->maintenance->loadWithArgv( [ '--sleep', '0', '--dry-run', '-q' ] );

		$this->maintenance->execute();

		$remainingScores = wfGetDB( DB_REPLICA )->select(
			[ 'logging' ],
			[ 'log_type', 'log_action', 'log_user' ],
			[],
			__METHOD__,
			[ 'ORDER BY' => 'log_id' ]
		);

		$expected = [
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
				'log_action' => 'autopatrol',
				'log_user' => '7254',
			],
		];
		$this->assertEquals( $expected, iterator_to_array( $remainingScores, false ) );
	}

	public function testRunWithTimestamp() {
		$this->maintenance->loadWithArgv( [ '--sleep', '0', '--before', '20060123210426', '-q' ] );

		$this->maintenance->execute();

		$remainingScores = wfGetDB( DB_REPLICA )->select(
			[ 'logging' ],
			[ 'log_type', 'log_action', 'log_user' ],
			[],
			__METHOD__,
			[ 'ORDER BY' => 'log_id' ]
		);

		$expected = [
			(object)[
				'log_type' => 'patrol',
				'log_action' => 'patrol',
				'log_user' => '7251',
			],
			(object)[
				'log_type' => 'block',
				'log_action' => 'block',
				'log_user' => '7253',
			],
			(object)[
				'log_type' => 'patrol',
				'log_action' => 'autopatrol',
				'log_user' => '7254',
			],
		];
		$this->assertEquals( $expected, iterator_to_array( $remainingScores, false ) );
	}

}
