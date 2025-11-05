<?php

namespace MediaWiki\Tests\Unit\RCFeed;

use InvalidArgumentException;
use MediaWiki\RCFeed\RCFeed;
use MediaWiki\RCFeed\RedisPubSubFeedEngine;
use MediaWiki\RCFeed\UDPRCFeedEngine;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\RCFeed\RCFeed
 */
class RCFeedTest extends MediaWikiUnitTestCase {

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
		$mockClass = get_class( $this->createMock( RCFeed::class ) );
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
		$this->expectDeprecationAndContinue( '/\$wgRCFeeds without class/' );
		$this->expectException( InvalidArgumentException::class );
		$feed = RCFeed::factory( [ 'uri' => 'test://bogus' ] );
	}

	public function testFactoryCustomUriUnknown() {
		$this->hideDeprecated( '$wgRCFeeds without class' );
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Unknown RCFeed engine' );
		$GLOBALS['wgRCEngines'] = [];
		$feed = RCFeed::factory( [ 'uri' => 'test://bogus' ] );
	}
}
