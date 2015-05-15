<?php

class ChangeTagsTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();
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

		$key = wfMemcKey( 'ChangeTags', 'tag-stats' );
		$cacheData = array(
			'UndefinedTag' => 65,
			'StoredTagWithHits' => 30,
		);
		$cache->set( $key, $cacheData, 60 );
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

		$key = wfMemcKey( 'ChangeTags', 'tag-stats' );
		$cache->touchCheckKey( $key );
	}
}
