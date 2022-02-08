<?php

use MediaWiki\Page\DeletePage;

/**
 * Class JobRunnerTest
 *
 * @group Database
 * @covers JobRunner
 */
class JobRunnerTest extends MediaWikiIntegrationTestCase {

	/**
	 * @var Title
	 */
	private $page;

	/**
	 * @var JobRunner
	 */
	private $jobRunner;

	/**
	 * @var DeletePageJob
	 */
	private $deletePageJob;

	protected function setUp(): void {
		parent::setUp();

		$str = wfRandomString( 10 );
		$this->page = $this->insertPage( $str )['title'];

		$this->assertTrue( $this->page->exists(), 'The created page exists' );

		$this->jobRunner = $this->getServiceContainer()->getJobRunner();
		$jobParams = [
			'namespace' => $this->page->getNamespace(),
			'title' => $this->page->getDBkey(),
			'wikiPageId' => $this->page->getArticleID(),
			'requestId' => WebRequest::getRequestId(),
			'reason' => 'Testing delete job',
			'suppress' => false,
			'userId' => $this->getTestUser()->getUser()->getId(),
			'tags' => json_encode( [] ),
			'logsubtype' => 'delete',
			'pageRole' => DeletePage::PAGE_BASE,
		];
		$this->deletePageJob = new DeletePageJob( $jobParams );
	}

	/**
	 * @dataProvider provideTestRun
	 */
	public function testRun( $options, $expectedVal ) {
		$this->getServiceContainer()->getJobQueueGroup()->push( $this->deletePageJob );

		$results = $this->jobRunner->run( $options );

		$this->assertEquals( $expectedVal, $results['reached'] );
	}

	public function provideTestRun() {
		return [
			[ [], 'none-ready' ],
			[ [ 'type' => true ], 'none-possible' ],
			[ [ 'maxJobs' => 1 ], 'job-limit' ],
			[ [ 'maxTime' => -1 ], 'time-limit' ],
			[ [ 'type' => 'deletePage', 'throttle' => false ], 'none-ready' ]
		];
	}

	public function testExecuteJob() {
		$results = $this->jobRunner->executeJob( $this->deletePageJob );

		$this->assertIsInt( $results['timeMs'] );
		$this->assertTrue( $results['status'] );
		$this->assertIsArray( $results['caught'] );
		$this->assertNull( $results['error'] );

		$this->assertTrue( $this->page->hasDeletedEdits() );
	}
}
