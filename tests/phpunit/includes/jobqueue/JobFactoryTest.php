<?php

use MediaWiki\JobQueue\JobFactory;
use MediaWiki\JobQueue\Jobs\DeleteLinksJob;
use MediaWiki\JobQueue\Jobs\NullJob;
use MediaWiki\JobQueue\Jobs\ParsoidCachePrewarmJob;
use MediaWiki\Title\Title;

/**
 * @author Addshore
 * @covers \MediaWiki\JobQueue\JobFactory
 */
class JobFactoryTest extends MediaWikiIntegrationTestCase {

	/**
	 * @dataProvider provideTestNewJob
	 */
	public function testNewJob( $handler, $expectedClass ) {
		$specs = [
			'testdummy' => $handler
		];

		$factory = new JobFactory(
			$this->getServiceContainer()->getObjectFactory(),
			$specs
		);

		$job = $factory->newJob( 'testdummy', Title::newMainPage(), [] );
		$this->assertInstanceOf( $expectedClass, $job );

		$job2 = $factory->newJob( 'testdummy', [] );
		$this->assertInstanceOf( $expectedClass, $job2 );
		$this->assertNotSame( $job, $job2, 'should not reuse instance' );

		$job3 = $factory->newJob( 'testdummy', [ 'namespace' => NS_MAIN, 'title' => 'JobTestTitle' ] );
		$this->assertInstanceOf( $expectedClass, $job3 );
		$this->assertNotSame( $job, $job3, 'should not reuse instance' );
	}

	public function provideTestNewJob() {
		return [
			'class name, no title' => [ 'NullJob', NullJob::class ],
			'class name with title' => [ DeleteLinksJob::class, DeleteLinksJob::class ],
			'closure' => [ static function ( Title $title, array $params ) {
				return new NullJob( $params );
			}, NullJob::class ],
			'function' => [ [ $this, 'newNullJob' ], NullJob::class ],
			'object spec, no title' => [ [ 'class' => 'NullJob' ], NullJob::class ],
			'object spec with title' => [ [ 'class' => DeleteLinksJob::class ], DeleteLinksJob::class ],
			'object spec with no title and not subclass of GenericParameterJob' => [
				[
					'class' => ParsoidCachePrewarmJob::class,
					'services' => [
						'ParserOutputAccess',
						'PageStore',
						'RevisionLookup',
						'ParsoidSiteConfig',
					],
					'needsPage' => false
				],
				ParsoidCachePrewarmJob::class
			]
		];
	}

	public function newNullJob( Title $title, array $params ) {
		return new NullJob( $params );
	}
}
