<?php

/**
 * Abstract special page class with scaffolding for caching the HTML output.
 *
 * To add HTML that should be cached, use addCachedHTML like this:
 * $this->addCachedHTML( $callback );
 *
 * The callback function is only called when needed, so do all your expensive
 * computations here. This function should returns the HTML to be cached.
 * It should not add anything to the PageOutput object!
 *
 * Before the first addCachedHTML call, you should call $this->startCache();
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
	 * @var integer
	 */
	protected $cacheExpiry = 3600;

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
	 * If the cache is enabled or not.
	 *
	 * @since 1.20
	 * @var boolean
	 */
	protected $cacheEnabled = true;

	/**
	 * Sets if the cache should be enabled or not.
	 *
	 * @since 1.20
	 * @param boolean $cacheEnabled
	 */
	public function setCacheEnabled( $cacheEnabled ) {
		$this->cacheEnabled = $cacheEnabled;
	}

	/**
	 * Initializes the caching.
	 * Should be called before the first time anything is added via addCachedHTML.
	 *
	 * @since 1.20
	 *
	 * @param integer|null $cacheExpiry Sets the cache expirty, either ttl in seconds or unix timestamp.
	 * @param boolean|null $cacheEnabled Sets if the cache should be enabled or not.
	 */
	public function startCache( $cacheExpiry = null, $cacheEnabled = null ) {
		if ( !is_null( $cacheExpiry ) ) {
			$this->cacheExpiry = $cacheExpiry;
		}

		if ( !is_null( $cacheEnabled ) ) {
			$this->setCacheEnabled( $cacheEnabled );
		}

		if ( $this->getRequest()->getText( 'action' ) === 'purge' ) {
			$this->hasCached = false;
		}

		$this->initCaching();
	}

	/**
	 * Returns a message that notifies the user he/she is looking at
	 * a cached version of the page, including a refresh link.
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	protected function getCachedNotice() {
		$refreshArgs = $this->getRequest()->getQueryValues();
		unset( $refreshArgs['title'] );
		$refreshArgs['action'] = 'purge';

		$refreshLink = Linker::link(
			$this->getTitle( $this->getTitle()->getSubpageText() ),
			$this->msg( 'cachedspecial-refresh-now' )->escaped(),
			array(),
			$refreshArgs
		);

		if ( $this->cacheExpiry < 86400 * 3650 ) {
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
			$cachedChunks = wfGetCache( CACHE_ANYTHING )->get( $this->getCacheKeyString() );

			$this->hasCached = is_array( $cachedChunks );
			$this->cachedChunks = $this->hasCached ? $cachedChunks : array();

			$this->onCacheInitialized();
		}
	}

	/**
	 * Gets called after the cache got initialized.
	 *
	 * @since 1.20
	 */
	protected function onCacheInitialized() {
		if ( $this->hasCached ) {
			$this->getOutput()->setSubtitle( $this->getCachedNotice() );
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

		if ( $this->cacheEnabled && $this->hasCached ) {
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

			if ( $this->cacheEnabled ) {
				if ( is_null( $key ) ) {
					$this->cachedChunks[] = $html;
				}
				else {
					$this->cachedChunks[$key] = $html;
				}
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
		if ( $this->cacheEnabled && $this->hasCached === false && !empty( $this->cachedChunks ) ) {
			wfGetCache( CACHE_ANYTHING )->set( $this->getCacheKeyString(), $this->cachedChunks, $this->cacheExpiry );
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
	protected function getCacheKeyString() {
		return call_user_func_array( 'wfMemcKey', $this->getCacheKey() );
	}

	/**
	 * Returns the variables used to constructed the cache key in an array.
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	protected function getCacheKey() {
		return array(
			$this->mName,
			$this->getLanguage()->getCode()
		);
	}

}
