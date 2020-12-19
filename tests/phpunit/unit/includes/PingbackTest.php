<?php

use MediaWiki\Http\HttpRequestFactory;
use Psr\Log\NullLogger;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers Pingback
 */
class PingbackTest extends MediaWikiUnitTestCase {

	public function setUp() : void {
		parent::setUp();
		ConvertibleTimestamp::setFakeTime( '20110401090000' );
	}

	public function tearDown() : void {
		ConvertibleTimestamp::setFakeTime( false );
		parent::tearDown();
	}

	public function testDisabled() {
		$db = $this->createMock( IDatabase::class );
		$lb = $this->createMock( ILoadBalancer::class );
		$lb->method( 'getConnectionRef' )->willReturn( $db );
		$http = $this->createMock( HttpRequestFactory::class );

		// Scenario: Disabled
		$config = new HashConfig( [ 'Pingback' => false ] );
		$cache = new HashBagOStuff();

		// Expect:
		// - no db calls (no select, lock, or upsert)
		// - no HTTP request
		$db->expects( $this->never() )->method( $this->anything() );
		$http->expects( $this->never() )->method( $this->anything() );

		$pingback = new MockPingback( $config, $lb, $cache, $http, new NullLogger );
		$this->assertNull( $pingback->run() );
	}

	public function testCacheBusy() {
		$db = $this->createMock( IDatabase::class );
		$lb = $this->createMock( ILoadBalancer::class );
		$lb->method( 'getConnectionRef' )->willReturn( $db );
		$http = $this->createMock( HttpRequestFactory::class );

		// Scenario:
		// - enabled
		// - table is empty
		// - cache lock is unavailable
		$config = new HashConfig( [ 'Pingback' => true ] );
		$db->expects( $this->once() )->method( 'selectField' )->willReturn( false );
		$cache = $this->createMock( BagOStuff::class );
		$cache->method( 'add' )->willReturn( false );

		// Expect:
		// - no db lock
		// - no HTTP request
		// - no db upsert for timestamp
		$db->expects( $this->never() )->method( 'lock' );
		$http->expects( $this->never() )->method( $this->anything() );
		$db->expects( $this->never() )->method( 'upsert' );

		$pingback = new MockPingback( $config, $lb, $cache, $http, new NullLogger );
		$this->assertNull( $pingback->run() );
	}

	public function testDbBusy() {
		$db = $this->createMock( IDatabase::class );
		$lb = $this->createMock( ILoadBalancer::class );
		$lb->method( 'getConnectionRef' )->willReturn( $db );
		$http = $this->createMock( HttpRequestFactory::class );

		// Scenario:
		// - enabled
		// - table is empty
		// - cache lock is available
		// - db lock is unavailable
		$config = new HashConfig( [ 'Pingback' => true ] );
		$db->expects( $this->once() )->method( 'selectField' )->willReturn( false );
		$cache = new HashBagOStuff();
		$db->expects( $this->once() )->method( 'lock' )->willReturn( false );

		// Expect:
		// - no HTTP request
		// - no db upsert for timestamp
		$http->expects( $this->never() )->method( $this->anything() );
		$db->expects( $this->never() )->method( 'upsert' );

		$pingback = new MockPingback( $config, $lb, $cache, $http, new NullLogger );
		$this->assertNull( $pingback->run() );
	}

	public function testFirstPing() {
		$db = $this->createMock( IDatabase::class );
		$lb = $this->createMock( ILoadBalancer::class );
		$lb->method( 'getConnectionRef' )->willReturn( $db );
		$http = $this->createMock( HttpRequestFactory::class );

		// Scenario:
		// - enabled
		// - table is empty
		// - cache lock and db lock are available
		$config = new HashConfig( [ 'Pingback' => true ] );
		$db->expects( $this->once() )->method( 'selectField' )->willReturn( false );
		$cache = new HashBagOStuff();

		// Expect:
		// - db lock acquired
		// - HTTP POST request
		// - db upsert for timestamp
		$db->expects( $this->once() )->method( 'lock' )->willReturn( true );
		$http->expects( $this->never() )->method( $this->anythingBut( 'post' ) );
		$http->expects( $this->once() )->method( 'post' )
			->with( $this->identicalTo(
				'https://www.mediawiki.org/beacon/event?%7B%22some%22%3A%22stuff%22%7D;'
			) )
			->willReturn( true );
		$db->expects( $this->once() )->method( 'upsert' );

		$pingback = new MockPingback( $config, $lb, $cache, $http, new NullLogger );
		$this->assertNull( $pingback->run() );
	}

	public function testAfterRecentPing() {
		$db = $this->createMock( IDatabase::class );
		$lb = $this->createMock( ILoadBalancer::class );
		$lb->method( 'getConnectionRef' )->willReturn( $db );
		$http = $this->createMock( HttpRequestFactory::class );

		// Scenario:
		// - enabled
		// - table contains a ping from one hour ago
		// - cache lock and db lock are available
		$config = new HashConfig( [ 'Pingback' => true ] );
		$db->expects( $this->once() )->method( 'selectField' )->willReturn(
			ConvertibleTimestamp::convert( TS_UNIX, '20110401080000' )
		);
		$cache = new HashBagOStuff();

		// Expect:
		// - no db lock
		// - no HTTP request
		// - no db upsert for timestamp
		$db->expects( $this->never() )->method( 'lock' );
		$http->expects( $this->never() )->method( $this->anything() );
		$db->expects( $this->never() )->method( 'upsert' );

		$pingback = new MockPingback( $config, $lb, $cache, $http, new NullLogger );
		$this->assertNull( $pingback->run() );
	}

	public function testAfterOldPing() {
		$db = $this->createMock( IDatabase::class );
		$lb = $this->createMock( ILoadBalancer::class );
		$lb->method( 'getConnectionRef' )->willReturn( $db );
		$http = $this->createMock( HttpRequestFactory::class );

		// Scenario:
		// - enabled
		// - table contains a ping from over a month ago
		// - cache lock and db lock are available
		$config = new HashConfig( [ 'Pingback' => true ] );
		$db->expects( $this->once() )->method( 'selectField' )->willReturn(
			ConvertibleTimestamp::convert( TS_UNIX, '20110301080000' )
		);
		$cache = new HashBagOStuff();

		// Expect:
		// - db lock acquire
		// - HTTP POST request
		// - db upsert for timestamp
		$db->expects( $this->once() )->method( 'lock' )->willReturn( true );
		$http->expects( $this->never() )->method( $this->anythingBut( 'post' ) );
		$http->expects( $this->once() )->method( 'post' )
			->with( $this->identicalTo(
				'https://www.mediawiki.org/beacon/event?%7B%22some%22%3A%22stuff%22%7D;'
			) )
			->willReturn( true );
		$db->expects( $this->once() )->method( 'upsert' );

		$pingback = new MockPingback( $config, $lb, $cache, $http, new NullLogger );
		$this->assertNull( $pingback->run() );
	}
}

class MockPingback extends Pingback {
	protected function getData() : array {
		return [ 'some' => 'stuff' ];
	}
}
