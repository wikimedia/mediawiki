<?php

namespace MediaWiki\Tests\Maintenance;

use ManageJobs;
use NullJob;

/**
 * @covers \ManageJobs
 * @group Database
 * @author Dreamy Jazz
 */
class ManageJobsTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return ManageJobs::class;
	}

	private function testCommonExecute( string $action ) {
		$this->maintenance->setOption( 'type', 'null' );
		$this->maintenance->setOption( 'action', $action );
		$this->maintenance->execute();
	}

	public function testExecuteForUnknownAction() {
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Invalid action.*invalidaction/' );
		$this->testCommonExecute( 'invalidaction' );
	}

	/** @dataProvider provideJobCounts */
	public function testExecuteForDeleteAction( $numberOfJobs ) {
		// Create two jobs
		$jobQueueGroup = $this->getServiceContainer()->getJobQueueGroup();
		for ( $i = 0; $i < $numberOfJobs; $i++ ) {
			$jobQueueGroup->push( new NullJob( [] ) );
		}
		$this->testCommonExecute( 'delete' );
		// Expect that two jobs are deleted
		$this->expectOutputRegex(
			"/Queue has $numberOfJobs job\(s\).*deleting[\s\S]*Done; current size is 0 job\(s\)/"
		);
		$this->assertSame( 0, $jobQueueGroup->getQueueSizes()['null'] );
	}

	public static function provideJobCounts() {
		return [
			'0 jobs' => [ 0 ],
			'2 job' => [ 2 ],
		];
	}
}
