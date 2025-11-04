<?php

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\JobQueue\JobQueueGroupFactory;
use MediaWiki\MainConfigNames;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\ReadOnlyMode;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\UUID\GlobalIdGenerator;

/**
 * @covers \MediaWiki\JobQueue\JobQueueGroupFactory
 */
class JobQueueGroupFactoryTest extends MediaWikiUnitTestCase {
	public function testMakeJobQueueGroupReturnsSameInstance(): void {
		$factory = new JobQueueGroupFactory(
			new ServiceOptions(
				JobQueueGroupFactory::CONSTRUCTOR_OPTIONS,
				new HashConfig( [
					MainConfigNames::LocalDatabases => [],
					MainConfigNames::JobClasses => [],
					MainConfigNames::JobTypeConf => [],
					MainConfigNames::JobTypesExcludedFromDefaultQueue => []
				] )
			),
			$this->createMock( ReadOnlyMode::class ),
			$this->createMock( StatsFactory::class ),
			$this->createMock( WANObjectCache::class ),
			$this->createMock( GlobalIdGenerator::class )
		);

		$group = $factory->makeJobQueueGroup();

		$this->assertSame( $group, $factory->makeJobQueueGroup() );
	}
}
