<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\Storage\DatabaseWikiIdChecker;
use MediaWikiTestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabaseSqlite;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\Rdbms\TransactionProfiler;

class DatabaseWikiIdCheckerTest extends MediaWikiTestCase {

	use DatabaseWikiIdChecker;

	/**
	 * @return LoadBalancer|PHPUnit_Framework_MockObject_MockObject
	 */
	private function getLoadBalancerMock( array $server ) {
		$lb = $this->getMockBuilder( LoadBalancer::class )
			->setMethods( [ 'reallyOpenConnection' ] )
			->setConstructorArgs( [ [ 'servers' => [ $server ] ] ] )
			->getMock();

		$lb->method( 'reallyOpenConnection' )->willReturnCallback(
			function ( array $server, $dbNameOverride = false ) {
				return $this->getDatabaseMock( $server );
			}
		);

		return $lb;
	}

	/**
	 * @return Database|PHPUnit_Framework_MockObject_MockObject
	 */
	private function getDatabaseMock( array $params ) {
		$db = $this->getMockBuilder( DatabaseSqlite::class )
			->setMethods( [ 'select', 'doQuery', 'open', 'closeConnection', 'isOpen' ] )
			->setConstructorArgs( [ $params ] )
			->getMock();

		$db->method( 'select' )->willReturn( new FakeResultWrapper( [] ) );
		$db->method( 'isOpen' )->willReturn( true );

		return $db;
	}

	public function provideDomainCheck() {
		yield [ false, 'test', '' ];
		yield [ 'test', 'test', '' ];

		yield [ false, 'test', 'foo_' ];
		yield [ 'test-foo_', 'test', 'foo_' ];

		yield [ false, 'dash-test', '' ];
		yield [ 'dash-test', 'dash-test', '' ];

		yield [ false, 'underscore_test', 'foo_' ];
		yield [ 'underscore_test-foo_', 'underscore_test', 'foo_' ];
	}

	/**
	 * @dataProvider provideDomainCheck
	 * @covers \MediaWiki\Storage\SingleContentRevisionStore::checkDatabaseWikiId
	 */
	public function testDomainCheck( $wikiId, $dbName, $dbPrefix ) {
		$this->setMwGlobals(
			[
				'wgDBname' => $dbName,
				'wgDBprefix' => $dbPrefix,
			]
		);

		$loadBalancer = $this->getLoadBalancerMock(
			[
				'host' => '*dummy*',
				'dbDirectory' => '*dummy*',
				'user' => 'test',
				'password' => 'test',
				'flags' => 0,
				'variables' => [],
				'schema' => '',
				'cliMode' => true,
				'agent' => '',
				'load' => 100,
				'profiler' => null,
				'trxProfiler' => new TransactionProfiler(),
				'connLogger' => new \Psr\Log\NullLogger(),
				'queryLogger' => new \Psr\Log\NullLogger(),
				'errorLogger' => new \Psr\Log\NullLogger(),
				'type' => 'test',
				'dbname' => $dbName,
				'tablePrefix' => $dbPrefix,
			]
		);
		$db = $loadBalancer->getConnection( DB_REPLICA );

		$this->checkDatabaseWikiId( $db, $wikiId );
		// Phpunit will complain if there are no assertions
		$this->assertTrue( true );
	}

}
