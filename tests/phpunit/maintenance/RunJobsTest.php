<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\Json\FormatJson;
use NullJob;
use RunJobs;

/**
 * @covers RunJobs
 * @group Database
 * @author Dreamy Jazz
 */
class RunJobsTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return RunJobs::class;
	}

	public function testExecuteWhenNoJobsQueued() {
		$this->maintenance->execute();
		$this->expectOutputString( "Job queue is empty.\n" );
	}

	public function testExecuteWhenNoJobsQueuedAndJsonOutput() {
		$this->maintenance->setOption( 'result', 'json' );
		$this->maintenance->execute();
		$output = $this->getActualOutputForAssertion();
		$outputAsArray = FormatJson::decode( $output, true );
		$this->assertSame( 'none-ready', $outputAsArray['reached'] );
		$this->assertCount( 0, $outputAsArray['jobs'] );
	}

	public function testExecuteForQueuedJob() {
		$this->getServiceContainer()->getJobQueueGroup()
			->push( new NullJob( [] ) );
		$this->maintenance->setOption( 'result', 'json' );
		$this->maintenance->execute();
		$output = $this->getActualOutputForAssertion();
		$outputAsArray = FormatJson::decode( $output, true );
		$this->assertSame( 'none-ready', $outputAsArray['reached'] );
		$this->assertCount( 1, $outputAsArray['jobs'] );
		$this->assertSame( 'null', $outputAsArray['jobs'][0]['type'] );
		$this->assertSame( 'ok', $outputAsArray['jobs'][0]['status'] );
	}

	public function testExecuteForQueuedJobsAndReachesJobLimit() {
		$this->getServiceContainer()->getJobQueueGroup()
			->push( new NullJob( [ 'lives' => 100 ] ) );
		$this->maintenance->setOption( 'result', 'json' );
		$this->maintenance->setOption( 'maxjobs', 3 );
		$this->maintenance->execute();
		$output = $this->getActualOutputForAssertion();
		$outputAsArray = FormatJson::decode( $output, true );
		$this->assertSame( 'job-limit', $outputAsArray['reached'] );
		$this->assertCount( 3, $outputAsArray['jobs'] );
		foreach ( $outputAsArray['jobs'] as $job ) {
			$this->assertSame( 'null', $job['type'] );
			$this->assertSame( 'ok', $job['status'] );
		}
	}
}
