<?php
/**
 * Cache of various elements in a single cache entry.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @license GPL-2.0-or-later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

use MediaWiki\MediaWikiServices;

/**
 * Helper class for caching various elements in a single cache entry.
 *
 * To get a cached value or compute it, use getCachedValue like this:
 * $this->getCachedValue( $callback );
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
 */
class CacheHelper implements ICacheHelper {
	/**
	 * The time to live for the cache, in seconds or a unix timestamp indicating the point of expiry.
	 *
	 * @since 1.20
	 * @var int
	 */
	protected $cacheExpiry = 3600;

	/**
	 * List of HTML chunks to be cached (if !hasCached) or that where cached (of hasCached).
	 * If not cached already, then the newly computed chunks are added here,
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
	 * @var bool|null
	 */
	protected $hasCached = null;

	/**
	 * If the cache is enabled or not.
	 *
	 * @since 1.20
	 * @var bool
	 */
	protected $cacheEnabled = true;

	/**
	 * Function that gets called when initialization is done.
	 *
	 * @since 1.20
	 * @var callable|null
	 */
	protected $onInitHandler;

	/**
	 * Elements to build a cache key with.
	 *
	 * @since 1.20
	 * @var array
	 */
	protected $cacheKey = [];

	/**
	 * Sets if the cache should be enabled or not.
	 *
	 * @since 1.20
	 * @param bool $cacheEnabled
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
	 * @param int|null $cacheExpiry Sets the cache expiry, either ttl in seconds or unix timestamp.
	 * @param bool|null $cacheEnabled Sets if the cache should be enabled or not.
	 */
	public function startCache( $cacheExpiry = null, $cacheEnabled = null ) {
		if ( $this->hasCached === null ) {
			if ( $cacheExpiry !== null ) {
				$this->cacheExpiry = $cacheExpiry;
			}

			if ( $cacheEnabled !== null ) {
				$this->setCacheEnabled( $cacheEnabled );
			}

			$this->initCaching();
		}
	}

	/**
	 * Returns a message that notifies the user he/she is looking at
	 * a cached version of the page, including a refresh link.
	 *
	 * @since 1.20
	 *
	 * @param IContextSource $context
	 * @param bool $includePurgeLink
	 *
	 * @return string
	 */
	public function getCachedNotice( IContextSource $context, $includePurgeLink = true ) {
		if ( $this->cacheExpiry < 86400 * 3650 ) {
			$message = $context->msg(
				'cachedspecial-viewing-cached-ttl',
				$context->getLanguage()->formatDuration( $this->cacheExpiry )
			)->escaped();
		} else {
			$message = $context->msg(
				'cachedspecial-viewing-cached-ts'
			)->escaped();
		}

		if ( $includePurgeLink ) {
			$refreshArgs = $context->getRequest()->getQueryValues();
			unset( $refreshArgs['title'] );
			$refreshArgs['action'] = 'purge';

			$message .= ' ' . MediaWikiServices::getInstance()->getLinkRenderer()->makeLink(
				$context->getTitle(),
				$context->msg( 'cachedspecial-refresh-now' )->text(),
				[],
				$refreshArgs
			);
		}

		return $message;
	}

	/**
	 * Initializes the caching if not already done so.
	 * Should be called before any of the caching functionality is used.
	 *
	 * @since 1.20
	 */
	protected function initCaching() {
		if ( $this->cacheEnabled && $this->hasCached === null ) {
			$cachedChunks = wfGetCache( CACHE_ANYTHING )->get( $this->getCacheKeyString() );

			$this->hasCached = is_array( $cachedChunks );
			$this->cachedChunks = $this->hasCached ? $cachedChunks : [];

			if ( $this->onInitHandler !== null ) {
				call_user_func( $this->onInitHandler, $this->hasCached );
			}
		}
	}

	/**
	 * Get a cached value if available or compute it if not and then cache it if possible.
	 * The provided $computeFunction is only called when the computation needs to happen
	 * and should return a result value. $args are arguments that will be passed to the
	 * compute function when called.
	 *
	 * @since 1.20
	 *
	 * @param callable $computeFunction
	 * @param array|mixed $args
	 * @param string|null $key
	 *
	 * @return mixed
	 */
	public function getCachedValue( $computeFunction, $args = [], $key = null ) {
		$this->initCaching();

		if ( $this->cacheEnabled && $this->hasCached ) {
			$value = null;

			if ( $key === null ) {
				reset( $this->cachedChunks );
				$itemKey = key( $this->cachedChunks );

				if ( $itemKey === null ) {
					wfWarn( "Attempted to get an item while the queue is empty in " . __METHOD__ );
				} elseif ( !is_int( $itemKey ) ) {
					wfWarn( "Attempted to get item with non-numeric key while " .
						"the next item in the queue has a key ($itemKey) in " . __METHOD__ );
				} else {
					$value = array_shift( $this->cachedChunks );
				}
			} elseif ( array_key_exists( $key, $this->cachedChunks ) ) {
				$value = $this->cachedChunks[$key];
				unset( $this->cachedChunks[$key] );
			} else {
				wfWarn( "There is no item with key '$key' in this->cachedChunks in " . __METHOD__ );
			}
		} else {
			if ( !is_array( $args ) ) {
				$args = [ $args ];
			}

			$value = $computeFunction( ...$args );

			if ( $this->cacheEnabled ) {
				if ( $key === null ) {
					$this->cachedChunks[] = $value;
				} else {
					$this->cachedChunks[$key] = $value;
				}
			}
		}

		return $value;
	}

	/**
	 * Saves the HTML to the cache in case it got recomputed.
	 * Should be called after the last time anything is added via addCachedHTML.
	 *
	 * @since 1.20
	 */
	public function saveCache() {
		if ( $this->cacheEnabled && $this->hasCached === false && !empty( $this->cachedChunks ) ) {
			wfGetCache( CACHE_ANYTHING )->set(
				$this->getCacheKeyString(),
				$this->cachedChunks,
				$this->cacheExpiry
			);
		}
	}

	/**
	 * Sets the time to live for the cache, in seconds or a unix timestamp
	 * indicating the point of expiry...
	 *
	 * @since 1.20
	 *
	 * @param int $cacheExpiry
	 */
	public function setExpiry( $cacheExpiry ) {
		$this->cacheExpiry = $cacheExpiry;
	}

	/**
	 * Returns the cache key to use to cache this page's HTML output.
	 * Is constructed from the special page name and language code.
	 *
	 * @since 1.20
	 *
	 * @return string
	 * @throws MWException
	 */
	protected function getCacheKeyString() {
		if ( $this->cacheKey === [] ) {
			throw new MWException( 'No cache key set, so cannot obtain or save the CacheHelper values.' );
		}

		return ObjectCache::getLocalClusterInstance()->makeKey(
			...array_values( $this->cacheKey )
		);
	}

	/**
	 * Sets the cache key that should be used.
	 *
	 * @since 1.20
	 *
	 * @param array $cacheKey
	 */
	public function setCacheKey( array $cacheKey ) {
		$this->cacheKey = $cacheKey;
	}

	/**
	 * Rebuild the content, even if it's already cached.
	 * This effectively has the same effect as purging the cache,
	 * since it will be overridden with the new value on the next request.
	 *
	 * @since 1.20
	 */
	public function rebuildOnDemand() {
		$this->hasCached = false;
	}

	/**
	 * Sets a function that gets called when initialization of the cache is done.
	 *
	 * @since 1.20
	 *
	 * @param callable $handlerFunction
	 */
	public function setOnInitializedHandler( $handlerFunction ) {
		$this->onInitHandler = $handlerFunction;
	}
}
