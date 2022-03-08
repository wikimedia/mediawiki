<?php

/**
 * @covers RCFeed
 */
class RCFeedTest extends MediaWikiUnitTestCase {
	public function setUp(): void {
		parent::setUp();
		MWDebug::init();
	}

	public function tearDown(): void {
		MWDebug::clearDeprecationFilters();
		MWDebug::clearLog();
		MWDebug::deinit();
		parent::tearDown();
	}

	public function testFactoryClass() {
		$feed = RCFeed::factory( [ 'class' => UDPRCFeedEngine::class ] );
		$this->assertInstanceOf( UDPRCFeedEngine::class, $feed );
	}

	public function testFactoryUriUdp() {
		$feed = RCFeed::factory( [ 'uri' => 'udp://127.0.0.1:8000' ] );
		$this->assertInstanceOf( UDPRCFeedEngine::class, $feed );
	}

	public function testFactoryUriRedis() {
		$feed = RCFeed::factory( [ 'uri' => 'redis://127.0.0.1' ] );
		$this->assertInstanceOf( RedisPubSubFeedEngine::class, $feed );
	}

	public function testFactoryCustomUri() {
		$mockClass = $this->getMockClass( RCFeed::class );
		$GLOBALS['wgRCEngines'] = [ 'test' => $mockClass ];

		$this->hideDeprecated( '$wgRCFeeds without class' );
		$feed = RCFeed::factory( [ 'uri' => 'test://bogus' ] );
		$this->assertInstanceOf( $mockClass, $feed );
	}

	public function testFactoryEmpty() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'must have a class set' );
		$feed = RCFeed::factory( [] );
	}

	public function testFactoryCustomUriDeprecated() {
		$this->expectDeprecation();
		$this->expectDeprecationMessage( '$wgRCFeeds without class' );
		$feed = RCFeed::factory( [ 'uri' => 'test://bogus' ] );
	}

	public function testFactoryCustomUriUnknown() {
		$this->hideDeprecated( '$wgRCFeeds without class' );
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Unknown RCFeed engine' );
		$feed = RCFeed::factory( [ 'uri' => 'test://bogus' ] );
	}
}
