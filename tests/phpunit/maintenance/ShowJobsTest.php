<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\Http\Telemetry;
use NullJob;
use ShowJobs;

/**
 * @covers \ShowJobs
 * @group Database
 * @author Dreamy Jazz
 */
class ShowJobsTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return ShowJobs::class;
	}

	private function commonTestExecute( $options, $expectedOutputRegex ) {
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->maintenance->execute();
		// Check that the script returns the right output
		$this->expectOutputRegex( $expectedOutputRegex );
	}

	public function testExecuteWithNoOptionsSpecified() {
		$jobQueueGroup = $this->getServiceContainer()->getJobQueueGroup();
		$jobQueueGroup->push( new NullJob( [] ) );
		$jobQueueGroup->push( new NullJob( [] ) );
		$this->commonTestExecute( [], '/2/' );
	}

	/** @dataProvider provideJobCount */
	public function testExecuteWithForListSpecified( int $jobCount ) {
		$jobQueueGroup = $this->getServiceContainer()->getJobQueueGroup();
		$expectedRegexOutput = '/';
		for ( $i = 0; $i < $jobCount; $i++ ) {
			$jobQueueGroup->push( new NullJob( [] ) );
			$expectedRegexOutput .= '.*null.*' . Telemetry::getInstance()->getRequestId() . ".*\n";
		}
		$this->commonTestExecute( [ 'list' => 1 ], $expectedRegexOutput . '/' );
	}

	public function testExecuteWithForListSpecifiedWithLimit() {
		$jobQueueGroup = $this->getServiceContainer()->getJobQueueGroup();
		for ( $i = 0; $i < 5; $i++ ) {
			$jobQueueGroup->push( new NullJob( [] ) );
		}
		$expectedRegexOutput = '/';
		for ( $i = 0; $i < 4; $i++ ) {
			$expectedRegexOutput .= '.*null.*' . Telemetry::getInstance()->getRequestId() . ".*\n";
		}
		$this->commonTestExecute( [ 'list' => 1, 'limit' => 4 ], $expectedRegexOutput . '/' );
	}

	/** @dataProvider provideJobCount */
	public function testExecuteWithForGroupSpecified( int $jobCount ) {
		$jobQueueGroup = $this->getServiceContainer()->getJobQueueGroup();
		for ( $i = 0; $i < $jobCount; $i++ ) {
			$jobQueueGroup->push( new NullJob( [] ) );
		}
		$this->commonTestExecute( [ 'group' => 1 ], "/.*null.*$jobCount queued.*\n/" );
	}

	public static function provideJobCount() {
		return [
			'1 job' => [ 1 ],
			'3 jobs' => [ 3 ],
		];
	}
}
