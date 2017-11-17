<?php

namespace MediaWiki\Storage;

/**
 * FIXME: Find a better name! This is really about actions. We'll need a FileViewAction, etc...
 * FIXME: make ActionAdapter which uses a callback to delegate to ImpagePage::view etc!
 * FIXME: document!
 * FIXME: should probably be in a different namespace!
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class PageContentComposer {

	// FIXME: action overrides!

	public function getDefaultMainSlotModel( PageIdentity $page ) {

	}

	public function getDefaultLanguage( PageIdentity $page ) {

	}

	public function getRequiredSlots( PageIdentity $page ) {

	}

	public function getAvailableSlots( PageIdentity $page ) {

	}

	/**
	 * Check if this page is something we're going to be showing
	 * some sort of sensible content for. If we return false, page
	 * views (plain action=view) will return an HTTP 404 response,
	 * so spiders and robots can know they're following a bad link.
	 *
	 * @return bool
	 */
	public function hasViewableContent() {
		return $this->mTitle->isKnown();
	}

	/**
	 * Auto-generates a deletion reason
	 *
	 * @param bool &$hasHistory Whether the page has a history
	 * @return string|bool String containing deletion reason or empty string, or boolean false
	 *    if no revision occurred
	 */
	public function getAutoDeleteReason( &$hasHistory ) {
		return $this->getContentHandler()->getAutoDeleteReason( $this->getTitle(), $hasHistory );
	}

	/**
	 * Whether this content displayed on this page
	 * comes from the local database
	 *
	 * @since 1.28
	 * @return bool
	 */
	public function isLocal( PageIdentity $page ) {
		return true;
	}

	/**
	 * The display name for the site this content
	 * come from. If a subclass overrides isLocal(),
	 * this could return something other than the
	 * current site name
	 *
	 * @since 1.28
	 * @return string
	 */
	public function getWikiDisplayName( PageIdentity $page ) {
		global $wgSitename;
		return $wgSitename;
	}

	/**
	 * Get the source URL for the content on this page,
	 * typically the canonical URL, but may be a remote
	 * link if the content comes from another site
	 *
	 * @since 1.28
	 * @return string
	 */
	public function getSourceURL( PageIdentity $page ) {
		return $this->getTitle()->getCanonicalURL();
	}

	/**
	 * Loads page_touched and returns a value indicating if it should be used
	 * @return bool True if this page exists and is not a redirect
	 */
	public function usePageTouched() {
		return ( $this->exists() && !$this->mIsRedirect );
	}

	/**
	 * Get the Title object or URL this page redirects to
	 *
	 * @return bool|Title|string False, Title of in-wiki target, or string with URL
	 */
	public function followRedirect() {
		return $this->getRedirectURL( $this->getRedirectTarget(), $source );
	}

	/**
	 * Get a ParserOutput for the given ParserOptions and revision ID.
	 *
	 * The parser cache will be used if possible. Cache misses that result
	 * in parser runs are debounced with PoolCounter.
	 *
	 * @since 1.19
	 * @param ParserOptions $parserOptions ParserOptions to use for the parse operation
	 * @param null|int $oldid Revision ID to get the text from, passing null or 0 will
	 *   get the current revision (default value)
	 * @param bool $forceParse Force reindexing, regardless of cache settings
	 * @return bool|ParserOutput ParserOutput or false if the revision was not found
	 */
	public function getParserOutput(
		RevisionRecord $revision,
		ParserOptions $parserOptions,
		$oldid = null,
		$forceParse = false
	) {
		$useParserCache =
			( !$forceParse ) && $this->shouldCheckParserCache( $parserOptions, $oldid );

		if ( $useParserCache && !$parserOptions->isSafeToCache() ) {
			throw new InvalidArgumentException(
				'The supplied ParserOptions are not safe to cache. Fix the options or set $forceParse = true.'
			);
		}

		wfDebug( __METHOD__ .
			': using parser cache: ' . ( $useParserCache ? 'yes' : 'no' ) . "\n" );
		if ( $parserOptions->getStubThreshold() ) {
			wfIncrStats( 'pcache.miss.stub' );
		}

		if ( $useParserCache ) {
			$parserOutput = MediaWikiServices::getInstance()->getParserCache()
				->get( $this, $parserOptions );
			if ( $parserOutput !== false ) {
				return $parserOutput;
			}
		}

		if ( $oldid === null || $oldid === 0 ) {
			$oldid = $this->getLatest();
		}

		$pool = new PoolWorkArticleView( $this, $parserOptions, $oldid, $useParserCache );
		$pool->execute();

		return $pool->getParserOutput();
	}

}
