<?php

/**
 * Abstract special page class with scaffolding for caching the HTML output.
 *
 * To enable the caching functionality, the cacheExpiry field should be set
 * in the constructor.
 *
 * To add HTML that should be cached, use addCachedHTML like this:
 * $this->addCachedHTML( array( $this, 'displayCachedContent' ) );
 *
 * After adding the last HTML that should be cached, call $this->saveCache();
 *
 * @since 1.20
 *
 * @file SpecialCachedPage.php
 * @ingroup SpecialPage
 *
 * @licence GNU GPL v2 or later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class SpecialCachedPage extends SpecialPage {

	/**
	 * The time to live for the cache, in seconds or a unix timestamp indicating the point of expiry.
	 *
	 * @since 1.20
	 * @var integer|null
	 */
	protected $cacheExpiry = null;

	/**
	 * List of HTML chunks to be cached (if !hasCached) or that where cashed (of hasCached).
	 * If no cached already, then the newly computed chunks are added here,
	 * if it as cached already, chunks are removed from this list as they are needed.
	 *
	 * @since 1.20
	 * @var array
	 */
	protected $cachedChunks;

	/**
	 * Indicates if the to be cached content was already cached.
	 * Null if this information is not available yet.
	 *
	 * @since 1.20
	 * @var boolean|null
	 */
	protected $hasCached = null;

	/**
	 * Main method.
	 *
	 * @since 1.20
	 *
	 * @param string|null $subPage
	 */
	public function execute( $subPage ) {
		if ( $this->getRequest()->getText( 'action' ) === 'purge' ) {
			$this->hasCached = false;
		}

		if ( !is_null( $this->cacheExpiry ) ) {
			$this->initCaching();

			if ( $this->hasCached === true ) {
				$this->getOutput()->setSubtitle( $this->getCachedNotice( $subPage ) );
			}
		}
	}

	/**
	 * Returns a message that notifies the user he/she is looking at
	 * a cached version of the page, including a refresh link.
	 *
	 * @since 1.20
	 *
	 * @param string|null $subPage
	 *
	 * @return string
	 */
	protected function getCachedNotice( $subPage ) {
		$refreshArgs = $this->getRequest()->getQueryValues();
		unset( $refreshArgs['title'] );
		$refreshArgs['action'] = 'purge';

		$refreshLink = Linker::link(
			$this->getTitle( $subPage ),
			$this->msg( 'cachedspecial-refresh-now' )->escaped(),
			array(),
			$refreshArgs
		);

		if ( $this->cacheExpiry < 1000000000 ) {
			$message = $this->msg(
				'cachedspecial-viewing-cached-ttl',
				$this->getLanguage()->formatDuration( $this->cacheExpiry )
			)->escaped();
		}
		else {
			$message = $this->msg(
				'cachedspecial-viewing-cached-ts'
			)->escaped();
		}

		return $message . ' ' . $refreshLink;
	}

	/**
	 * Initializes the caching if not already done so.
	 * Should be called before any of the caching functionality is used.
	 *
	 * @since 1.20
	 */
	protected function initCaching() {
		if ( is_null( $this->hasCached ) ) {
			$cachedChunks = wfGetCache( CACHE_ANYTHING )->get( $this->getCacheKey() );

			$this->hasCached = is_array( $cachedChunks );
			$this->cachedChunks = $this->hasCached ? $cachedChunks : array();
		}
	}

	/**
	 * Add some HTML to be cached.
	 * This is done by providing a callback function that should
	 * return the HTML to be added. It will only be called if the
	 * item is not in the cache yet or when the cache has been invalidated.
	 *
	 * @since 1.20
	 *
	 * @param {function} $callback
	 * @param array $args
	 * @param string|null $key
	 */
	public function addCachedHTML( $callback, $args = array(), $key = null ) {
		$this->initCaching();

		if ( $this->hasCached ) {
			$html = '';

			if ( is_null( $key ) ) {
				$itemKey = array_keys( array_slice( $this->cachedChunks, 0, 1 ) );
				$itemKey = array_shift( $itemKey );

				if ( !is_integer( $itemKey ) ) {
					wfWarn( "Attempted to get item with non-numeric key while the next item in the queue has a key ($itemKey) in " . __METHOD__ );
				}
				elseif ( is_null( $itemKey ) ) {
					wfWarn( "Attempted to get an item while the queue is empty in " . __METHOD__ );
				}
				else {
					$html = array_shift( $this->cachedChunks );
				}
			}
			else {
				if ( array_key_exists( $key, $this->cachedChunks ) ) {
					$html = $this->cachedChunks[$key];
					unset( $this->cachedChunks[$key] );
				}
				else {
					wfWarn( "There is no item with key '$key' in this->cachedChunks in " . __METHOD__ );
				}
			}
		}
		else {
			$html = call_user_func_array( $callback, $args );

			if ( is_null( $key ) ) {
				$this->cachedChunks[] = $html;
			}
			else {
				$this->cachedChunks[$key] = $html;
			}
		}

		$this->getOutput()->addHTML( $html );
	}

	/**
	 * Saves the HTML to the cache in case it got recomputed.
	 * Should be called after the last time anything is added via addCachedHTML.
	 *
	 * @since 1.20
	 */
	public function saveCache() {
		if ( $this->hasCached === false && !empty( $this->cachedChunks ) ) {
			wfGetCache( CACHE_ANYTHING )->set( $this->getCacheKey(), $this->cachedChunks, $this->cacheExpiry );
		}
	}

	/**
	 * Sets the time to live for the cache, in seconds or a unix timestamp indicating the point of expiry..
	 *
	 * @since 1.20
	 *
	 * @param integer $cacheExpiry
	 */
	protected function setExpirey( $cacheExpiry ) {
		$this->cacheExpiry = $cacheExpiry;
	}

	/**
	 * Returns the cache key to use to cache this page's HTML output.
	 * Is constructed from the special page name and language code.
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	protected function getCacheKey() {
		return wfMemcKey( $this->mName, $this->getLanguage()->getCode() );
	}

}
