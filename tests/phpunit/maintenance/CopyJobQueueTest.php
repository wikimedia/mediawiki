<?php

namespace MediaWiki\Tests\Maintenance;

use CopyJobQueue;
use MediaWiki\JobQueue\JobQueueDB;
use MediaWiki\JobQueue\JobQueueMemory;
use MediaWiki\JobQueue\Jobs\NullJob;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \CopyJobQueue
 * @group Database
 * @author Dreamy Jazz
 */
class CopyJobQueueTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return CopyJobQueue::class;
	}

	/** @dataProvider provideExecuteForFatalError */
	public function testExecuteForFatalError( $jobQueueMigrationConfig, $options, $expectedOutputRegex ) {
		$this->overrideConfigValue( 'JobQueueMigrationConfig', $jobQueueMigrationConfig );
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->expectOutputRegex( $expectedOutputRegex );
		$this->expectCallToFatalError();
		$this->maintenance->execute();
	}

	public static function provideExecuteForFatalError() {
		return [
			'Job queue migration config empty' => [
				[], [ 'src' => 'source', 'dst' => 'dest' ], "/wgJobQueueMigrationConfig not set for 'source'/",
			],
			'Job queue migration config set but src does not match' => [
				[ 'source' => 'test', 'dest' => 'test' ], [ 'src' => 'abcd', 'dst' => 'dest' ],
				"/wgJobQueueMigrationConfig not set for 'abcd'/",
			],
			'Job queue migration config set but dst does not match' => [
				[ 'source' => 'test', 'dest' => 'test' ], [ 'src' => 'source', 'dst' => 'abc' ],
				"/wgJobQueueMigrationConfig not set for 'abc'/",
			],
		];
	}

	/**
	 * Sets the job queue migration configuration to move jobs from a memory based queue to a DB based queue,
	 * along with setting the job queue configuration to be memory based (to allow inserting test jobs to migrate).
	 *
	 * This also sets the maintenance script options to be in-line with the migration config.
	 *
	 * @return void
	 */
	private function setMigrationConfig() {
		$this->overrideConfigValue( 'JobTypeConf', [
			'default' => [ 'class' => JobQueueMemory::class ],
		] );
		$this->overrideConfigValue( 'JobQueueMigrationConfig', [
			'source' => [
				'class' => JobQueueMemory::class,
				'idGenerator' => $this->getServiceContainer()->getGlobalIdGenerator(),
			],
			'dest' => [
				'class' => JobQueueDB::class, 'idGenerator' => $this->getServiceContainer()->getGlobalIdGenerator(),
			],
		] );
		$this->maintenance->setOption( 'src', 'source' );
		$this->maintenance->setOption( 'dst', 'dest' );
	}

	public function testExecuteWhenNoJobsPresent() {
		$this->setMigrationConfig();

		$this->maintenance->setOption( 'type', 'all' );
		$this->maintenance->execute();

		// Check that all jobs were copied by checking a subset of jobs defined by MediaWiki core are in the output.
		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( 'Copied 0/0 queued null jobs', $actualOutput );
		$this->assertStringContainsString( 'Copied 0/0 delayed null jobs', $actualOutput );
		$this->assertStringContainsString( 'Copied 0/0 queued categoryMembershipChange jobs', $actualOutput );
		$this->assertStringContainsString( 'Copied 0/0 delayed categoryMembershipChange jobs', $actualOutput );
	}

	public function testExecuteWhenJobsPresentButTypeDoesNotMatch() {
		$this->setMigrationConfig();

		// Add a 'null' job to the in memory queue
		$nullJobQueueGroup = $this->getServiceContainer()->getJobQueueGroup()->get( 'null' );
		$nullJobQueueGroup->push( new NullJob( [] ) );

		$this->maintenance->setOption( 'type', 'categoryMembershipChange' );
		$this->maintenance->execute();

		// Check that no jobs were copied as the only jobs existing did not match the specified type.
		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( 'Copied 0/0 queued categoryMembershipChange jobs', $actualOutput );
		$this->assertStringContainsString( 'Copied 0/0 delayed categoryMembershipChange jobs', $actualOutput );
	}

	/** @dataProvider provideBatchSizeValues */
	public function testExecuteWhenJobsPresentAndCopied( $batchSize ) {
		$this->setMigrationConfig();

		// Add two 'null' jobs to the in memory queue
		$nullJobQueueGroup = $this->getServiceContainer()->getJobQueueGroup()->get( 'null' );
		$nullJobQueueGroup->push( new NullJob( [] ) );
		$nullJobQueueGroup->push( new NullJob( [] ) );

		// Check that the above jobs were inserted to memory and not the DB. This is needed so that we can
		// assert that the jobs appear in the DB table after the maintenance script has been run.
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'job' )
			->caller( __METHOD__ )
			->assertFieldValue( 0 );

		// Run the script. We use a local variable to reference the object as $this->maintenance does not indicate
		// that the type of the object is TestingAccessWrapper (so will cause errors in IDEs).
		/** @var TestingAccessWrapper $maintenance */
		$maintenance = $this->maintenance;
		$maintenance->setBatchSize( $batchSize );
		$maintenance->setOption( 'type', 'null' );
		$maintenance->execute();

		// Check that the jobs now exist in the DB and were marked as copied in the output.
		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( 'Copied 2/2 queued null jobs', $actualOutput );
		$this->assertStringContainsString( 'Copied 0/0 delayed null jobs', $actualOutput );

		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'job' )
			->where( [ 'job_cmd' => 'null' ] )
			->caller( __METHOD__ )
			->assertFieldValue( 2 );

		// Finally, run the jobs using the DB job queue configuration to ensure that the move did not corrupt the jobs.
		$this->overrideConfigValue( 'JobTypeConf', [
			'default' => [ 'class' => JobQueueDB::class ],
		] );
		$this->runJobs( [ 'minJobs' => 2 ] );
	}

	public static function provideBatchSizeValues() {
		return [
			'Batch size of 1' => [ 1 ],
			'Batch size of 10' => [ 10 ],
		];
	}
}
