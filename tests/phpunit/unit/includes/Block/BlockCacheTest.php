<?php

use MediaWiki\Block\BlockCache;
use MediaWiki\Block\BlockCacheKey;
use MediaWiki\Block\SystemBlock;
use MediaWiki\Request\FauxRequest;
use MediaWiki\User\UserIdentityValue;

/**
 * @covers \MediaWiki\Block\BlockCache
 * @covers \MediaWiki\Block\BlockCacheEntry
 * @covers \MediaWiki\Block\BlockCacheKey
 */
class BlockCacheTest extends MediaWikiUnitTestCase {
	public function testGet() {
		// The exact semantics of partial keys and evictions in BlockCache are
		// implementation details, not really part of the public contract.
		// But we have some tests for them here to confirm that the code as
		// implemented at least reflects the developer's intention.

		$cache = new BlockCache;

		$req1 = new FauxRequest();
		$req2 = new FauxRequest();

		$user1 = new UserIdentityValue( 1, 'User 1' );
		$user2 = new UserIdentityValue( 2, 'User 2' );
		$anon = new UserIdentityValue( 0, '127.0.0.1' );

		$keyR1U1 = new BlockCacheKey( $req1, $user1, false );
		$keyR2U1 = new BlockCacheKey( $req2, $user1, false );
		$keyR2U2 = new BlockCacheKey( $req2, $user2, false );
		$keyR2U2P = new BlockCacheKey( $req2, $user2, true );
		$keyU1 = new BlockCacheKey( null, $user1, false );
		$keyU2P = new BlockCacheKey( null, $user2, true );
		$keyA1 = new BlockCacheKey( null, $anon, false );

		$block1 = new SystemBlock();
		$block2 = new SystemBlock();
		$block3 = new SystemBlock();
		$block4 = new SystemBlock();
		$block5 = new SystemBlock();

		// Empty cache
		$this->assertNull( $cache->get( $keyR1U1 ) );
		$this->assertNull( $cache->get( $keyR2U1 ) );
		$this->assertNull( $cache->get( $keyR2U2 ) );
		$this->assertNull( $cache->get( $keyR2U2P ) );
		$this->assertNull( $cache->get( $keyU1 ) );
		$this->assertNull( $cache->get( $keyA1 ) );

		// Set a request block to absent
		$cache->set( $keyR1U1, false );
		$this->assertFalse( $cache->get( $keyR1U1 ) );
		$this->assertNull( $cache->get( $keyR2U1 ) );
		$this->assertNull( $cache->get( $keyR2U2 ) );
		$this->assertNull( $cache->get( $keyR2U2P ) );
		$this->assertNull( $cache->get( $keyU1 ) );
		$this->assertNull( $cache->get( $keyA1 ) );

		// Set another request block with a different request
		$cache->set( $keyR2U1, $block1 );
		$this->assertNull( $cache->get( $keyR1U1 ) );
		$this->assertSame( $block1, $cache->get( $keyR2U1 ) );
		$this->assertNull( $cache->get( $keyR2U2 ) );
		$this->assertNull( $cache->get( $keyR2U2P ) );
		$this->assertNull( $cache->get( $keyU1 ) );
		$this->assertNull( $cache->get( $keyA1 ) );

		// Set another request block with a different user
		$cache->set( $keyR2U2, $block2 );
		$this->assertNull( $cache->get( $keyR1U1 ) );
		$this->assertNull( $cache->get( $keyR2U1 ) );
		$this->assertSame( $block2, $cache->get( $keyR2U2 ) );
		$this->assertNull( $cache->get( $keyR2U2P ) );
		$this->assertNull( $cache->get( $keyU1 ) );
		$this->assertNull( $cache->get( $keyA1 ) );

		// Set another request block with a different fromPrimary flag
		$cache->set( $keyR2U2P, $block3 );
		$this->assertNull( $cache->get( $keyR1U1 ) );
		$this->assertNull( $cache->get( $keyR2U1 ) );
		$this->assertSame( $block3, $cache->get( $keyR2U2 ) );
		$this->assertSame( $block3, $cache->get( $keyR2U2P ) );
		$this->assertNull( $cache->get( $keyU1 ) );
		$this->assertNull( $cache->get( $keyA1 ) );

		// Set a user block with no request
		$cache->set( $keyU1, $block4 );
		$this->assertNull( $cache->get( $keyR1U1 ) );
		$this->assertNull( $cache->get( $keyR2U1 ) );
		$this->assertSame( $block3, $cache->get( $keyR2U2 ) );
		$this->assertSame( $block3, $cache->get( $keyR2U2P ) );
		$this->assertSame( $block4, $cache->get( $keyU1 ) );
		$this->assertNull( $cache->get( $keyA1 ) );

		// Set an anonymous block with no request
		$cache->set( $keyA1, $block5 );
		$this->assertNull( $cache->get( $keyR1U1 ) );
		$this->assertNull( $cache->get( $keyR2U1 ) );
		$this->assertSame( $block3, $cache->get( $keyR2U2 ) );
		$this->assertSame( $block3, $cache->get( $keyR2U2P ) );
		$this->assertSame( $block4, $cache->get( $keyU1 ) );
		$this->assertSame( $block5, $cache->get( $keyA1 ) );

		// The weak reference causes the request cache entry to disappear when
		// the request is destroyed
		$req2 = null;
		$this->assertNull( $cache->get( $keyR2U2P ) );

		// An non-request block does not match a destroyed weak reference
		$this->assertNull( $cache->get( $keyU2P ) );
	}
}
