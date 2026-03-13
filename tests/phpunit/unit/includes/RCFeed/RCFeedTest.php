<?php

namespace MediaWiki\Tests\Unit\RCFeed;

use InvalidArgumentException;
use MediaWiki\RCFeed\RCFeed;
use MediaWiki\RCFeed\UDPRCFeedEngine;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\RCFeed\RCFeed
 */
class RCFeedTest extends MediaWikiUnitTestCase {

	public function testFactoryEmptyClass() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'must have a class set' );
		$feed = RCFeed::factory( [] );
	}

	public function testFactoryInvalidSchema() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Invalid RCFeed uri' );
		$feed = RCFeed::factory( [
			'class' => UDPRCFeedEngine::class,
			'uri' => 'bogus',
		] );
	}

	public function testFactoryUnknownClass() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Unknown class' );

		// Explicitly set the global configuration for testing
		$feed = RCFeed::factory( [
			'class' => Bogus::class,
			'uri' => 'udp://localhost'
		] );
	}

	public function testFactoryCustomUri() {
		$feed = RCFeed::factory( [
			'class' => UDPRCFeedEngine::class,
			'uri' => 'test://bogus',
		] );
		$this->assertInstanceOf( UDPRCFeedEngine::class, $feed );
	}

	public function testFactoryEmpty() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'must have a class set' );
		$feed = RCFeed::factory( [] );
	}
}
