<?php

use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\MainConfigNames;
use Psr\Log\NullLogger;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\ILoadBalancer;
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
	 */
	private function testRun(
		DBConnRef $database,
		$httpRequestFactory,
		bool $enablePingback = true,
		$cache = null
	) {
		$loadBalancer = $this->createNoOpMock( ILoadBalancer::class, [ 'getConnectionRef' ] );
		$loadBalancer->method( 'getConnectionRef' )->willReturn( $database );

		$pingback = new MockPingback(
			new HashConfig( [ MainConfigNames::Pingback => $enablePingback ] ),
			$loadBalancer,
			$cache ?? new HashBagOStuff(),
			$httpRequestFactory,
			new NullLogger()
		);
		// All of the assertions are in the expectations
		$pingback->run();
	}

	public function testDisabled() {
		// Scenario: Disabled
		// Expect:
		// - no db calls (no select, lock, or upsert)
		// - no HTTP request
		$this->testRun(
			$this->createNoOpMock( DBConnRef::class ),
			$this->createNoOpMock( HttpRequestFactory::class ),
			false /* $enablePingback */
		);
	}

	public function testCacheBusy() {
		// Scenario:
		// - enabled
		// - table is empty
		// - cache lock is unavailable
		// Expect:
		// - no db lock
		// - no HTTP request
		// - no db upsert for timestamp
		$database = $this->createNoOpMock( DBConnRef::class, [ 'selectField' ] );
		$database->expects( $this->once() )->method( 'selectField' )->willReturn( false );
		$cache = $this->createMock( BagOStuff::class );
		$cache->method( 'add' )->willReturn( false );

		$this->testRun(
			$database,
			$this->createNoOpMock( HttpRequestFactory::class ),
			true, /* $enablePingback */
			$cache
		);
	}

	public function testDbBusy() {
		// Scenario:
		// - enabled
		// - table is empty
		// - cache lock is available
		// - db lock is unavailable
		// Expect:
		// - no HTTP request
		// - no db upsert for timestamp
		$database = $this->createNoOpMock( DBConnRef::class, [ 'selectField', 'lock' ] );
		$database->expects( $this->once() )->method( 'selectField' )->willReturn( false );
		$database->expects( $this->once() )->method( 'lock' )->willReturn( false );

		$this->testRun(
			$database,
			$this->createNoOpMock( HttpRequestFactory::class )
		);
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
		// Expect:
		// - db lock acquired
		// - HTTP POST request
		// - db upsert for timestamp
		$database = $this->createNoOpMock( DBConnRef::class, [ 'selectField', 'lock', 'upsert' ] );
		$httpRequestFactory = $this->createNoOpMock( HttpRequestFactory::class, [ 'post' ] );

		$database->expects( $this->once() )->method( 'selectField' )->willReturn( $priorPing );
		$database->expects( $this->once() )->method( 'lock' )->willReturn( true );
		$httpRequestFactory->expects( $this->once() )
			->method( 'post' )
			->with( 'https://www.mediawiki.org/beacon/event?%7B%22some%22%3A%22stuff%22%7D;' )
			->willReturn( true );
		$database->expects( $this->once() )->method( 'upsert' );

		$this->testRun(
			$database,
			$httpRequestFactory
		);
	}

	public function provideMakePing() {
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
		// Expect:
		// - no db lock
		// - no HTTP request
		// - no db upsert for timestamp
		$database = $this->createNoOpMock( DBConnRef::class, [ 'selectField' ] );
		$database->expects( $this->once() )->method( 'selectField' )->willReturn(
			ConvertibleTimestamp::convert( TS_UNIX, '20110401080000' )
		);

		$this->testRun(
			$database,
			$this->createNoOpMock( HttpRequestFactory::class )
		);
	}
}

class MockPingback extends Pingback {
	protected function getData(): array {
		return [ 'some' => 'stuff' ];
	}
}
