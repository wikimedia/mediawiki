<?php

use MediaWiki\Cache\HTMLCacheUpdater;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Title\TitleFactory;

/**
 * A no-op {@link HTMLCacheUpdater} implementation that prevents CDN cache purge
 * side effects in tests.
 *
 * Unlike the Null* HTTP classes, this does not throw on use — CDN purges are
 * expected side effects from normal test operations (e.g. editPage()) and tests
 * generally don't care about them.
 *
 * getUrls() is deliberately not overridden: URL generation is pure string
 * building with no HTTP or DB side effects, and some tests (e.g.
 * CdnCacheUpdateTest, MobileFrontendHooksTest) depend on it returning real URLs.
 *
 * @license GPL-2.0-or-later
 */
class NullHTMLCacheUpdater extends HTMLCacheUpdater {

	public function __construct( HookContainer $hookContainer, TitleFactory $titleFactory ) {
		parent::__construct( $hookContainer, $titleFactory, 0, false, 0 );
	}

	/** @inheritDoc */
	public function purgeUrls( $urls, $flags = self::PURGE_PRESEND, array $unless = [] ) {
		// No-op: suppress CDN purge requests in tests
	}

	/** @inheritDoc */
	public function purgeTitleUrls( $pages, $flags = self::PURGE_PRESEND, array $unless = [] ) {
		// No-op: suppress CDN purge requests in tests
	}
}
