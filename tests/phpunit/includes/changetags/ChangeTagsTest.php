<?php

class ChangeTagsTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();
		$this->setMwGlobals( array(
			'wgTagMaxHitcountUpdate' => 10,
		) );
		$this->initCaches();
	}

	protected function tearDown() {
		$this->purgeCaches();
		parent::tearDown();
	}

	/**
	 * We prefill the caches eventually retrieved by ChangeTagsContext.
	 */
	protected function initCaches() {
		$cache = ObjectCache::getMainWANInstance();

		$key = wfMemcKey( 'ChangeTags', 'valid-tags-db' );
		$cacheData = array(
			'StoredTagNoHits' => array( 'active' => true ),
			'StoredTagWithHits' => array( 'active' => true ),
			);
		$cache->set( $key, $cacheData, 60 );

		$key = wfMemcKey( 'ChangeTags', 'valid-tags-hook' );
		$cacheData = array(
			'ActiveRegisteredTag' => array( 'active' => true ),
			'InactiveRegisteredTag' => array(),
		);
		$cache->set( $key, $cacheData, 60 );

		$key1 = wfMemcKey( 'ChangeTags', 'tag-stats-reactive' );
		$key2 = wfMemcKey( 'ChangeTags', 'tag-stats-stable' );
		$cacheData = array(
			'UndefinedTag' => 65,
			'StoredTagWithHits' => 30,
			'TagWithFewHits' => 6,
			'TagWithManyHits' => 800,
		);
		$cache->set( $key1, $cacheData, 60 );
		$cache->set( $key2, $cacheData, 60 );
	}

	/**
	 * We purge the caches filed initially.
	 */
	protected function purgeCaches() {
		$cache = ObjectCache::getMainWANInstance();

		$key = wfMemcKey( 'ChangeTags', 'valid-tags-db' );
		$cache->touchCheckKey( $key );

		$key = wfMemcKey( 'ChangeTags', 'valid-tags-hook' );
		$cache->touchCheckKey( $key );

		$key = wfMemcKey( 'ChangeTags', 'tag-stats-reactive' );
		$cache->touchCheckKey( $key );

		$key = wfMemcKey( 'ChangeTags', 'tag-stats-stable' );
		$cache->touchCheckKey( $key );
	}
}
