<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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

	public function setDomainAliasesProvider() {
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
