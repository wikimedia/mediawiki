<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Config\ServiceOptions;
use Wikimedia\ObjectCache\EmptyBagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\ChronologyProtector;
use Wikimedia\Rdbms\ConfiguredReadOnlyMode;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\LBFactorySimple;
use Wikimedia\RequestTimeout\CriticalSectionProvider;
use Wikimedia\RequestTimeout\RequestTimeout;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\Telemetry\NoopTracer;

/**
 * @covers \Wikimedia\Rdbms\LBFactory
 * @covers \Wikimedia\Rdbms\LBFactorySimple
 * @covers \Wikimedia\Rdbms\LBFactoryMulti
 * @covers \MWLBFactory
 */
class MWLBFactoryTest extends MediaWikiUnitTestCase {

	private function newMWLBFactory() {
		return new MWLBFactory(
			new ServiceOptions( [], [] ),
			new ConfiguredReadOnlyMode( 'Test' ),
			new ChronologyProtector(),
			new EmptyBagOStuff(),
			new WANObjectCache( [ 'cache' => new EmptyBagOStuff() ] ),
			new CriticalSectionProvider( RequestTimeout::singleton(), 1, null, null ),
			StatsFactory::newNull(),
			[],
			new NoopTracer(),
		);
	}

	/**
	 * @dataProvider getLBFactoryClassProvider
	 */
	public function testGetLBFactoryClass( $config, $expected ) {
		$this->assertEquals(
			$expected,
			$this->newMWLBFactory()->getLBFactoryClass( $config )
		);
	}

	public static function getLBFactoryClassProvider() {
		yield 'undercore alias default' => [
			[ 'class' => 'LBFactory_Simple' ],
			Wikimedia\Rdbms\LBFactorySimple::class,
		];
		yield 'short alias multi' => [
			[ 'class' => 'LBFactoryMulti' ],
			Wikimedia\Rdbms\LBFactoryMulti::class,
		];
	}

	/**
	 * @dataProvider setDomainAliasesProvider
	 */
	public function testDomainAliases( $dbname, $prefix, $expectedDomain ) {
		$servers = [ [
			'type'        => 'sqlite',
			'dbname'      => 'defaultdb',
			'tablePrefix' => 'defaultprefix_',
			'dbDirectory' => '~/sqldatadir/',
			'load'        => 0,
		] ];
		$lbFactory = new LBFactorySimple( [
			'servers' => $servers,
			'localDomain' => new DatabaseDomain( $dbname, null, $prefix )
		] );
		$this->newMWLBFactory()->setDomainAliases( $lbFactory );

		$rawDomain = rtrim( "$dbname-$prefix", '-' );
		$this->assertEquals(
			$expectedDomain,
			$lbFactory->getMainLB()->resolveDomainID( $rawDomain ),
			'Domain aliases set'
		);
	}

	public static function setDomainAliasesProvider() {
		return [
			[ 'enwiki', '', 'enwiki' ],
			[ 'wikipedia', 'fr_', 'wikipedia-fr_' ],
			[ 'wikipedia', 'zh', 'wikipedia-zh' ],
			[ 'wiki-pedia', '', 'wiki?hpedia' ],
			[ 'wiki-pedia', 'es_', 'wiki?hpedia-es_' ],
			[ 'wiki-pedia', 'ru', 'wiki?hpedia-ru' ]
		];
	}
}
