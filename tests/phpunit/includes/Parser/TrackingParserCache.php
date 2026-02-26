<?php

namespace MediaWiki\Tests\Parser;

use MediaWiki\Page\PageRecord;
use MediaWiki\Parser\ParserCache;

/**
 * Spy for ParserCache.
 */
class TrackingParserCache extends ParserCache {
	private readonly TrackerWrapper $wrapper;
	private readonly string $cacheName;

	/**
	 * @inheritDoc
	 * @param TrackerWrapper $wrapper
	 * @param string $name
	 * @param ...$args
	 */
	public function __construct(
		TrackerWrapper $wrapper,
		string $name,
		...$args
	) {
		parent::__construct(
			$name,
			...$args
		);
		$this->wrapper = $wrapper;
		$this->cacheName = $name;
	}

	/**
	 * @inheritDoc
	 * Tracks "get" access with the cache name and whether the get was successful or not.
	 */
	public function get( PageRecord $page, $popts, $useOutdated = false ) {
		$res = parent::get( $page, $popts, $useOutdated );
		$this->wrapper->calls[] = [ $this->cacheName, $res !== false ];
		return $res;
	}
}
