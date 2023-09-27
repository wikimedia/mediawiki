<?php

use MediaWiki\Config\HashConfig;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Installer\Pingback;
use MediaWiki\MainConfigNames;
use Psr\Log\NullLogger;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\InsertQueryBuilder;
use Wikimedia\Rdbms\SelectQueryBuilder;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers Pingback
 */
class PingbackTest extends MediaWikiUnitTestCase {

	protected function setUp(): void {
		parent::setUp();
		ConvertibleTimestamp::setFakeTime( '20110401090000' );
	}

	/**
	 * @param DBConnRef $database
	 * @param HttpRequestFactory $httpRequestFactory
	 * @param bool $enablePingback
	 * @param BagOStuff|null $cache
	 * @return Pingback
	 */
	private function makePingback(
		DBConnRef $database,
		$httpRequestFactory,
		bool $enablePingback = true,
		$cache = null
	) {
		$dbProvider = $this->createNoOpMock( IConnectionProvider::class, [ 'getPrimaryDatabase', 'getReplicaDatabase' ] );
		$dbProvider->method( 'getPrimaryDatabase' )->willReturn( $database );
		$dbProvider->method( 'getReplicaDatabase' )->willReturn( $database );

		return new MockPingback(
			new HashConfig( [ MainConfigNames::Pingback => $enablePingback ] ),
			$dbProvider,
			$cache ?? new HashBagOStuff(),
			$httpRequestFactory,
			new NullLogger()
		);
	}

	public function testDisabled() {
		// Scenario: Disabled
		$pingback = $this->makePingback(
			$this->createNoOpMock( DBConnRef::class ),
			$this->createNoOpMock( HttpRequestFactory::class ),
			false /* $enablePingback */
		);
		// Expect:
		// - no db select, lock, or upsert (asserted via no-op mock)
		// - no HTTP request (asserted via no-op mock)
		$pingback->run();
	}

	public function testCacheBusy() {
		// Scenario:
		// - enabled
		// - table is empty
		// - cache lock is unavailable
		$database = $this->createNoOpMock( DBConnRef::class, [ 'selectField', 'newSelectQueryBuilder' ] );
		$database->expects( $this->once() )->method( 'selectField' )->willReturn( false );
		$database->method( 'newSelectQueryBuilder' )->willReturnCallback( static fn () => new SelectQueryBuilder( $database ) );

		$cache = $this->createMock( BagOStuff::class );
		$cache->method( 'add' )->willReturn( false );

		$pingback = $this->makePingback(
			$database,
			$this->createNoOpMock( HttpRequestFactory::class ),
			true, /* $enablePingback */
			$cache
		);

		// Expect:
		// - no db lock
		// - no HTTP request
		// - no db upsert for timestamp
		$pingback->run();
	}

	public function testDbBusy() {
		// Scenario:
		// - enabled
		// - table is empty
		// - cache lock is available
		// - db lock is unavailable
		$database = $this->createNoOpMock( DBConnRef::class, [ 'selectField', 'lock', 'newSelectQueryBuilder' ] );
		$database->expects( $this->once() )->method( 'selectField' )->willReturn( false );
		$database->expects( $this->once() )->method( 'lock' )->willReturn( false );
		$database->method( 'newSelectQueryBuilder' )->willReturnCallback( static fn () => new SelectQueryBuilder( $database ) );

		$pingback = $this->makePingback(
			$database,
			$this->createNoOpMock( HttpRequestFactory::class )
		);
		// Expect:
		// - no HTTP request
		// - no db upsert for timestamp
		$pingback->run();
	}

	/**
	 * @dataProvider provideMakePing
	 */
	public function testMakePing( $priorPing ) {
		// Scenario:
		// - enabled
		// - table is either
		//     - empty ($priorPing is false)
		//     - has a ping from over a month ago ($piorPing)
		// - cache lock and db lock are available
		$database = $this->createNoOpMock(
			DBConnRef::class,
			[ 'selectField', 'lock', 'upsert', 'newSelectQueryBuilder', 'newInsertQueryBuilder' ]
		);
		$httpRequestFactory = $this->createNoOpMock( HttpRequestFactory::class, [ 'post' ] );

		$database->expects( $this->once() )->method( 'selectField' )->willReturn( $priorPing );
		$database->expects( $this->once() )->method( 'lock' )->willReturn( true );
		$httpRequestFactory->expects( $this->once() )
			->method( 'post' )
			->with( 'https://www.mediawiki.org/beacon/event?%7B%22some%22%3A%22stuff%22%7D;' )
			->willReturn( true );
		$database->expects( $this->once() )->method( 'upsert' );
		$database->method( 'newSelectQueryBuilder' )->willReturnCallback( static fn () => new SelectQueryBuilder( $database ) );
		$database->method( 'newInsertQueryBuilder' )->willReturnCallback( static fn () => new InsertQueryBuilder( $database ) );

		$pingback = $this->makePingback(
			$database,
			$httpRequestFactory
		);
		// Expect:
		// - db lock acquired
		// - HTTP POST request
		// - db upsert for timestamp
		$pingback->run();
	}

	public static function provideMakePing() {
		yield 'No prior ping' => [ false ];
		yield 'Prior ping from over a month ago' => [
			ConvertibleTimestamp::convert( TS_UNIX, '20110301080000' )
		];
	}

	public function testAfterRecentPing() {
		// Scenario:
		// - enabled
		// - table contains a ping from one hour ago
		// - cache lock and db lock are available
		$database = $this->createNoOpMock( DBConnRef::class, [ 'selectField', 'newSelectQueryBuilder' ] );
		$database->expects( $this->once() )->method( 'selectField' )->willReturn(
			ConvertibleTimestamp::convert( TS_UNIX, '20110401080000' )
		);
		$database->method( 'newSelectQueryBuilder' )->willReturnCallback( static fn () => new SelectQueryBuilder( $database ) );

		$pingback = $this->makePingback(
			$database,
			$this->createNoOpMock( HttpRequestFactory::class )
		);
		// Expect:
		// - no db lock
		// - no HTTP request
		// - no db upsert for timestamp
		$pingback->run();
	}
}

class MockPingback extends Pingback {
	protected function getData(): array {
		return [ 'some' => 'stuff' ];
	}
}
