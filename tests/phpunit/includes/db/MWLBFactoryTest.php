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

use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LBFactorySimple;

/**
 * @group Database
 * @covers \Wikimedia\Rdbms\LBFactory
 * @covers \Wikimedia\Rdbms\LBFactorySimple
 * @covers \Wikimedia\Rdbms\LBFactoryMulti
 */
class MWLBFactoryTest extends MediaWikiTestCase {
	/**
	 * @covers       MWLBFactory::getLBFactoryClass()
	 * @dataProvider getLBFactoryClassProvider
	 */
	public function testGetLBFactoryClass( $expected, $deprecated ) {
		$mockDB = $this->getMockBuilder( IDatabase::class )
			->disableOriginalConstructor()
			->getMock();

		$config = [
			'class' => $deprecated,
			'connection' => $mockDB,
			# Various other parameters required:
			'sectionsByDB' => [],
			'sectionLoads' => [],
			'serverTemplate' => [],
		];

		$this->filterDeprecated( '/\$wgLBFactoryConf must be updated/' );
		$result = MWLBFactory::getLBFactoryClass( $config );

		$this->assertEquals( $expected, $result );
	}

	public function getLBFactoryClassProvider() {
		return [
			# Format: new class, old class
			[ Wikimedia\Rdbms\LBFactorySimple::class, 'LBFactory_Simple' ],
			[ Wikimedia\Rdbms\LBFactorySingle::class, 'LBFactory_Single' ],
			[ Wikimedia\Rdbms\LBFactoryMulti::class, 'LBFactory_Multi' ],
			[ Wikimedia\Rdbms\LBFactorySimple::class, 'LBFactorySimple' ],
			[ Wikimedia\Rdbms\LBFactorySingle::class, 'LBFactorySingle' ],
			[ Wikimedia\Rdbms\LBFactoryMulti::class, 'LBFactoryMulti' ],
		];
	}

	/**
	 * @covers MWLBFactory::setDomainAliases()
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
		MWLBFactory::setDomainAliases( $lbFactory );

		$rawDomain = rtrim( "$dbname-$prefix", '-' );
		$this->assertEquals(
			$expectedDomain,
			$lbFactory->resolveDomainID( $rawDomain ),
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
