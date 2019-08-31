<?php
/**
 * Localisation messages cache.
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
 * @ingroup Cache
 */
use MediaWiki\MediaWikiServices;
use Wikimedia\ScopedCallback;
use MediaWiki\Logger\LoggerFactory;
use Wikimedia\Rdbms\Database;

/**
 * MediaWiki message cache structure version.
 * Bump this whenever the message cache format has changed.
 */
define( 'MSG_CACHE_VERSION', 2 );

/**
 * Cache of messages that are defined by MediaWiki namespace pages or by hooks
 *
 * Performs various MediaWiki namespace-related functions
 * @ingroup Cache
 */
class MessageCache {
	const FOR_UPDATE = 1; // force message reload

	/** How long to wait for memcached locks */
	const WAIT_SEC = 15;
	/** How long memcached locks last */
	const LOCK_TTL = 30;

	/**
	 * Lifetime for cache, for keys stored in $wanCache, in seconds.
	 * @var int
	 */
	const WAN_TTL = IExpiringStore::TTL_DAY;

	/**
	 * Process cache of loaded messages that are defined in MediaWiki namespace
	 *
	 * @var MapCacheLRU Map of (language code => key => " <MESSAGE>" or "!TOO BIG" or "!ERROR")
	 */
	protected $cache;

	/**
	 * Map of (lowercase message key => index) for all software defined messages
	 *
	 * @var array
	 */
	protected $overridable;

	/**
	 * @var bool[] Map of (language code => boolean)
	 */
	protected $cacheVolatile = [];

	/**
	 * Should mean that database cannot be used, but check
	 * @var bool $mDisable
	 */
	protected $mDisable;

	/**
	 * Message cache has its own parser which it uses to transform messages
	 * @var ParserOptions
	 */
	protected $mParserOptions;
	/** @var Parser */
	protected $mParser;

	/**
	 * @var bool $mInParser
	 */
	protected $mInParser = false;

	/** @var WANObjectCache */
	protected $wanCache;
	/** @var BagOStuff */
	protected $clusterCache;
	/** @var BagOStuff */
	protected $srvCache;
	/** @var Language */
	protected $contLang;

	/**
	 * Track which languages have been loaded by load().
	 * @var array
	 */
	private $loadedLanguages = [];

	/**
	 * Get the singleton instance of this class
	 *
	 * @deprecated in 1.34 inject an instance of this class instead of using global state
	 * @since 1.18
	 * @return MessageCache
	 */
	public static function singleton() {
		return MediaWikiServices::getInstance()->getMessageCache();
	}

	/**
	 * Normalize message key input
	 *
	 * @param string $key Input message key to be normalized
	 * @return string Normalized message key
	 */
	public static function normalizeKey( $key ) {
		$lckey = strtr( $key, ' ', '_' );
		if ( ord( $lckey ) < 128 ) {
			$lckey[0] = strtolower( $lckey[0] );
		} else {
			$lckey = MediaWikiServices::getInstance()->getContentLanguage()->lcfirst( $lckey );
		}

		return $lckey;
	}

	/**
	 * @param WANObjectCache $wanCache
	 * @param BagOStuff $clusterCache
	 * @param BagOStuff $serverCache
	 * @param bool $useDB Whether to look for message overrides (e.g. MediaWiki: pages)
	 * @param Language|null $contLang Content language of site
	 */
	public function __construct(
		WANObjectCache $wanCache,
		BagOStuff $clusterCache,
		BagOStuff $serverCache,
		$useDB,
		Language $contLang = null
	) {
		$this->wanCache = $wanCache;
		$this->clusterCache = $clusterCache;
		$this->srvCache = $serverCache;

		$this->cache = new MapCacheLRU( 5 ); // limit size for sanity

		$this->mDisable = !$useDB;
		$this->contLang = $contLang ?? MediaWikiServices::getInstance()->getContentLanguage();
	}

	/**
	 * ParserOptions is lazy initialised.
	 *
	 * @return ParserOptions
	 */
	function getParserOptions() {
		global $wgUser;

		if ( !$this->mParserOptions ) {
			if ( !$wgUser || !$wgUser->isSafeToLoad() ) {
				// $wgUser isn't available yet, so don't try to get a
				// ParserOptions for it. And don't cache this ParserOptions
				// either.
				$po = ParserOptions::newFromAnon();
				$po->setAllowUnsafeRawHtml( false );
				$po->setTidy( true );
				return $po;
			}

			$this->mParserOptions = new ParserOptions;
			// Messages may take parameters that could come
			// from malicious sources. As a precaution, disable
			// the <html> parser tag when parsing messages.
			$this->mParserOptions->setAllowUnsafeRawHtml( false );
			// For the same reason, tidy the output!
			$this->mParserOptions->setTidy( true );
		}

		return $this->mParserOptions;
	}

	/**
	 * Try to load the cache from APC.
	 *
	 * @param string $code Optional language code, see documentation of load().
	 * @return array|bool The cache array, or false if not in cache.
	 */
	protected function getLocalCache( $code ) {
		$cacheKey = $this->srvCache->makeKey( __CLASS__, $code );

		return $this->srvCache->get( $cacheKey );
	}

	/**
	 * Save the cache to APC.
	 *
	 * @param string $code
	 * @param array $cache The cache array
	 */
	protected function saveToLocalCache( $code, $cache ) {
		$cacheKey = $this->srvCache->makeKey( __CLASS__, $code );
		$this->srvCache->set( $cacheKey, $cache );
	}

	/**
	 * Loads messages from caches or from database in this order:
	 * (1) local message cache (if $wgUseLocalMessageCache is enabled)
	 * (2) memcached
	 * (3) from the database.
	 *
	 * When successfully loading from (2) or (3), all higher level caches are
	 * updated for the newest version.
	 *
	 * Nothing is loaded if member variable mDisable is true, either manually
	 * set by calling code or if message loading fails (is this possible?).
	 *
	 * Returns true if cache is already populated or it was successfully populated,
	 * or false if populating empty cache fails. Also returns true if MessageCache
	 * is disabled.
	 *
	 * @param string $code Language to which load messages
	 * @param int|null $mode Use MessageCache::FOR_UPDATE to skip process cache [optional]
	 * @throws InvalidArgumentException
	 * @return bool
	 */
	protected function load( $code, $mode = null ) {
		if ( !is_string( $code ) ) {
			throw new InvalidArgumentException( "Missing language code" );
		}

		# Don't do double loading...
		if ( isset( $this->loadedLanguages[$code] ) && $mode != self::FOR_UPDATE ) {
			return true;
		}

		$this->overridable = array_flip( Language::getMessageKeysFor( $code ) );

		# 8 lines of code just to say (once) that message cache is disabled
		if ( $this->mDisable ) {
			static $shownDisabled = false;
			if ( !$shownDisabled ) {
				wfDebug( __METHOD__ . ": disabled\n" );
				$shownDisabled = true;
			}

			return true;
		}

		# Loading code starts
		$success = false; # Keep track of success
		$staleCache = false; # a cache array with expired data, or false if none has been loaded
		$where = []; # Debug info, delayed to avoid spamming debug log too much

		# Hash of the contents is stored in memcache, to detect if data-center cache
		# or local cache goes out of date (e.g. due to replace() on some other server)
		list( $hash, $hashVolatile ) = $this->getValidationHash( $code );
		$this->cacheVolatile[$code] = $hashVolatile;

		# Try the local cache and check against the cluster hash key...
		$cache = $this->getLocalCache( $code );
		if ( !$cache ) {
			$where[] = 'local cache is empty';
		} elseif ( !isset( $cache['HASH'] ) || $cache['HASH'] !== $hash ) {
			$where[] = 'local cache has the wrong hash';
			$staleCache = $cache;
		} elseif ( $this->isCacheExpired( $cache ) ) {
			$where[] = 'local cache is expired';
			$staleCache = $cache;
		} elseif ( $hashVolatile ) {
			$where[] = 'local cache validation key is expired/volatile';
			$staleCache = $cache;
		} else {
			$where[] = 'got from local cache';
			$this->cache->set( $code, $cache );
			$success = true;
		}

		if ( !$success ) {
			$cacheKey = $this->clusterCache->makeKey( 'messages', $code );
			# Try the global cache. If it is empty, try to acquire a lock. If
			# the lock can't be acquired, wait for the other thread to finish
			# and then try the global cache a second time.
			for ( $failedAttempts = 0; $failedAttempts <= 1; $failedAttempts++ ) {
				if ( $hashVolatile && $staleCache ) {
					# Do not bother fetching the whole cache blob to avoid I/O.
					# Instead, just try to get the non-blocking $statusKey lock
					# below, and use the local stale value if it was not acquired.
					$where[] = 'global cache is presumed expired';
				} else {
					$cache = $this->clusterCache->get( $cacheKey );
					if ( !$cache ) {
						$where[] = 'global cache is empty';
					} elseif ( $this->isCacheExpired( $cache ) ) {
						$where[] = 'global cache is expired';
						$staleCache = $cache;
					} elseif ( $hashVolatile ) {
						# DB results are replica DB lag prone until the holdoff TTL passes.
						# By then, updates should be reflected in loadFromDBWithLock().
						# One thread regenerates the cache while others use old values.
						$where[] = 'global cache is expired/volatile';
						$staleCache = $cache;
					} else {
						$where[] = 'got from global cache';
						$this->cache->set( $code, $cache );
						$this->saveToCaches( $cache, 'local-only', $code );
						$success = true;
					}
				}

				if ( $success ) {
					# Done, no need to retry
					break;
				}

				# We need to call loadFromDB. Limit the concurrency to one process.
				# This prevents the site from going down when the cache expires.
				# Note that the DB slam protection lock here is non-blocking.
				$loadStatus = $this->loadFromDBWithLock( $code, $where, $mode );
				if ( $loadStatus === true ) {
					$success = true;
					break;
				} elseif ( $staleCache ) {
					# Use the stale cache while some other thread constructs the new one
					$where[] = 'using stale cache';
					$this->cache->set( $code, $staleCache );
					$success = true;
					break;
				} elseif ( $failedAttempts > 0 ) {
					# Already blocked once, so avoid another lock/unlock cycle.
					# This case will typically be hit if memcached is down, or if
					# loadFromDB() takes longer than LOCK_WAIT.
					$where[] = "could not acquire status key.";
					break;
				} elseif ( $loadStatus === 'cantacquire' ) {
					# Wait for the other thread to finish, then retry. Normally,
					# the memcached get() will then yield the other thread's result.
					$where[] = 'waited for other thread to complete';
					$this->getReentrantScopedLock( $cacheKey );
				} else {
					# Disable cache; $loadStatus is 'disabled'
					break;
				}
			}
		}

		if ( !$success ) {
			$where[] = 'loading FAILED - cache is disabled';
			$this->mDisable = true;
			$this->cache->set( $code, [] );
			wfDebugLog( 'MessageCacheError', __METHOD__ . ": Failed to load $code\n" );
			# This used to throw an exception, but that led to nasty side effects like
			# the whole wiki being instantly down if the memcached server died
		} else {
			# All good, just record the success
			$this->loadedLanguages[$code] = true;
		}

		if ( !$this->cache->has( $code ) ) { // sanity
			throw new LogicException( "Process cache for '$code' should be set by now." );
		}

		$info = implode( ', ', $where );
		wfDebugLog( 'MessageCache', __METHOD__ . ": Loading $code... $info\n" );

		return $success;
	}

	/**
	 * @param string $code
	 * @param array &$where List of wfDebug() comments
	 * @param int|null $mode Use MessageCache::FOR_UPDATE to use DB_MASTER
	 * @return bool|string True on success or one of ("cantacquire", "disabled")
	 */
	protected function loadFromDBWithLock( $code, array &$where, $mode = null ) {
		# If cache updates on all levels fail, give up on message overrides.
		# This is to avoid easy site outages; see $saveSuccess comments below.
		$statusKey = $this->clusterCache->makeKey( 'messages', $code, 'status' );
		$status = $this->clusterCache->get( $statusKey );
		if ( $status === 'error' ) {
			$where[] = "could not load; method is still globally disabled";
			return 'disabled';
		}

		# Now let's regenerate
		$where[] = 'loading from database';

		# Lock the cache to prevent conflicting writes.
		# This lock is non-blocking so stale cache can quickly be used.
		# Note that load() will call a blocking getReentrantScopedLock()
		# after this if it really need to wait for any current thread.
		$cacheKey = $this->clusterCache->makeKey( 'messages', $code );
		$scopedLock = $this->getReentrantScopedLock( $cacheKey, 0 );
		if ( !$scopedLock ) {
			$where[] = 'could not acquire main lock';
			return 'cantacquire';
		}

		$cache = $this->loadFromDB( $code, $mode );
		$this->cache->set( $code, $cache );
		$saveSuccess = $this->saveToCaches( $cache, 'all', $code );

		if ( !$saveSuccess ) {
			/**
			 * Cache save has failed.
			 *
			 * There are two main scenarios where this could be a problem:
			 * - The cache is more than the maximum size (typically 1MB compressed).
			 * - Memcached has no space remaining in the relevant slab class. This is
			 *   unlikely with recent versions of memcached.
			 *
			 * Either way, if there is a local cache, nothing bad will happen. If there
			 * is no local cache, disabling the message cache for all requests avoids
			 * incurring a loadFromDB() overhead on every request, and thus saves the
			 * wiki from complete downtime under moderate traffic conditions.
			 */
			if ( $this->srvCache instanceof EmptyBagOStuff ) {
				$this->clusterCache->set( $statusKey, 'error', 60 * 5 );
				$where[] = 'could not save cache, disabled globally for 5 minutes';
			} else {
				$where[] = "could not save global cache";
			}
		}

		return true;
	}

	/**
	 * Loads cacheable messages from the database. Messages bigger than
	 * $wgMaxMsgCacheEntrySize are assigned a special value, and are loaded
	 * on-demand from the database later.
	 *
	 * @param string $code Language code
	 * @param int|null $mode Use MessageCache::FOR_UPDATE to skip process cache
	 * @return array Loaded messages for storing in caches
	 */
	protected function loadFromDB( $code, $mode = null ) {
		global $wgMaxMsgCacheEntrySize, $wgLanguageCode, $wgAdaptiveMessageCache;

		// (T164666) The query here performs really poorly on WMF's
		// contributions replicas. We don't have a way to say "any group except
		// contributions", so for the moment let's specify 'api'.
		// @todo: Get rid of this hack.
		$dbr = wfGetDB( ( $mode == self::FOR_UPDATE ) ? DB_MASTER : DB_REPLICA, 'api' );

		$cache = [];

		$mostused = []; // list of "<cased message key>/<code>"
		if ( $wgAdaptiveMessageCache && $code !== $wgLanguageCode ) {
			if ( !$this->cache->has( $wgLanguageCode ) ) {
				$this->load( $wgLanguageCode );
			}
			$mostused = array_keys( $this->cache->get( $wgLanguageCode ) );
			foreach ( $mostused as $key => $value ) {
				$mostused[$key] = "$value/$code";
			}
		}

		// Get the list of software-defined messages in core/extensions
		$overridable = array_flip( Language::getMessageKeysFor( $wgLanguageCode ) );

		// Common conditions
		$conds = [
			'page_is_redirect' => 0,
			'page_namespace' => NS_MEDIAWIKI,
		];
		if ( count( $mostused ) ) {
			$conds['page_title'] = $mostused;
		} elseif ( $code !== $wgLanguageCode ) {
			$conds[] = 'page_title' . $dbr->buildLike( $dbr->anyString(), '/', $code );
		} else {
			# Effectively disallows use of '/' character in NS_MEDIAWIKI for uses
			# other than language code.
			$conds[] = 'page_title NOT' .
				$dbr->buildLike( $dbr->anyString(), '/', $dbr->anyString() );
		}

		// Set the stubs for oversized software-defined messages in the main cache map
		$res = $dbr->select(
			'page',
			[ 'page_title', 'page_latest' ],
			array_merge( $conds, [ 'page_len > ' . intval( $wgMaxMsgCacheEntrySize ) ] ),
			__METHOD__ . "($code)-big"
		);
		foreach ( $res as $row ) {
			// Include entries/stubs for all keys in $mostused in adaptive mode
			if ( $wgAdaptiveMessageCache || $this->isMainCacheable( $row->page_title, $overridable ) ) {
				$cache[$row->page_title] = '!TOO BIG';
			}
			// At least include revision ID so page changes are reflected in the hash
			$cache['EXCESSIVE'][$row->page_title] = $row->page_latest;
		}

		// Set the text for small software-defined messages in the main cache map
		$revisionStore = MediaWikiServices::getInstance()->getRevisionStore();
		$revQuery = $revisionStore->getQueryInfo( [ 'page', 'user' ] );

		// T231196: MySQL/MariaDB (10.1.37) can sometimes irrationally decide that querying `actor` then
		// `revision` then `page` is somehow better than starting with `page`. Tell it not to reorder the
		// query (and also reorder it ourselves because as generated by RevisionStore it'll have
		// `revision` first rather than `page`).
		$revQuery['joins']['revision'] = $revQuery['joins']['page'];
		unset( $revQuery['joins']['page'] );
		// It isn't actually necesssary to reorder $revQuery['tables'] as Database does the right thing
		// when join conditions are given for all joins, but Gergő is wary of relying on that so pull
		// `page` to the start.
		$revQuery['tables'] = array_merge(
			[ 'page' ],
			array_diff( $revQuery['tables'], [ 'page' ] )
		);

		$res = $dbr->select(
			$revQuery['tables'],
			$revQuery['fields'],
			array_merge( $conds, [
				'page_len <= ' . intval( $wgMaxMsgCacheEntrySize ),
				'page_latest = rev_id' // get the latest revision only
			] ),
			__METHOD__ . "($code)-small",
			[ 'STRAIGHT_JOIN' ],
			$revQuery['joins']
		);
		foreach ( $res as $row ) {
			// Include entries/stubs for all keys in $mostused in adaptive mode
			if ( $wgAdaptiveMessageCache || $this->isMainCacheable( $row->page_title, $overridable ) ) {
				try {
					$rev = $revisionStore->newRevisionFromRow( $row );
					$content = $rev->getContent( MediaWiki\Revision\SlotRecord::MAIN );
					$text = $this->getMessageTextFromContent( $content );
				} catch ( Exception $ex ) {
					$text = false;
				}

				if ( !is_string( $text ) ) {
					$entry = '!ERROR';
					wfDebugLog(
						'MessageCache',
						__METHOD__
						. ": failed to load message page text for {$row->page_title} ($code)"
					);
				} else {
					$entry = ' ' . $text;
				}
				$cache[$row->page_title] = $entry;
			} else {
				// T193271: cache object gets too big and slow to generate.
				// At least include revision ID so page changes are reflected in the hash.
				$cache['EXCESSIVE'][$row->page_title] = $row->page_latest;
			}
		}

		$cache['VERSION'] = MSG_CACHE_VERSION;
		ksort( $cache );

		# Hash for validating local cache (APC). No need to take into account
		# messages larger than $wgMaxMsgCacheEntrySize, since those are only
		# stored and fetched from memcache.
		$cache['HASH'] = md5( serialize( $cache ) );
		$cache['EXPIRY'] = wfTimestamp( TS_MW, time() + self::WAN_TTL );
		unset( $cache['EXCESSIVE'] ); // only needed for hash

		return $cache;
	}

	/**
	 * @param string $name Message name (possibly with /code suffix)
	 * @param array $overridable Map of (key => unused) for software-defined messages
	 * @return bool
	 */
	private function isMainCacheable( $name, array $overridable ) {
		// Convert first letter to lowercase, and strip /code suffix
		$name = $this->contLang->lcfirst( $name );
		$msg = preg_replace( '/\/[a-z0-9-]{2,}$/', '', $name );
		// Include common conversion table pages. This also avoids problems with
		// Installer::parse() bailing out due to disallowed DB queries (T207979).
		return ( isset( $overridable[$msg] ) || strpos( $name, 'conversiontable/' ) === 0 );
	}

	/**
	 * Updates cache as necessary when message page is changed
	 *
	 * @param string $title Message cache key with initial uppercase letter
	 * @param string|bool $text New contents of the page (false if deleted)
	 */
	public function replace( $title, $text ) {
		global $wgLanguageCode;

		if ( $this->mDisable ) {
			return;
		}

		list( $msg, $code ) = $this->figureMessage( $title );
		if ( strpos( $title, '/' ) !== false && $code === $wgLanguageCode ) {
			// Content language overrides do not use the /<code> suffix
			return;
		}

		// (a) Update the process cache with the new message text
		if ( $text === false ) {
			// Page deleted
			$this->cache->setField( $code, $title, '!NONEXISTENT' );
		} else {
			// Ignore $wgMaxMsgCacheEntrySize so the process cache is up to date
			$this->cache->setField( $code, $title, ' ' . $text );
		}

		// (b) Update the shared caches in a deferred update with a fresh DB snapshot
		DeferredUpdates::addUpdate(
			new MessageCacheUpdate( $code, $title, $msg ),
			DeferredUpdates::PRESEND
		);
	}

	/**
	 * @param string $code
	 * @param array[] $replacements List of (title, message key) pairs
	 * @throws MWException
	 */
	public function refreshAndReplaceInternal( $code, array $replacements ) {
		global $wgMaxMsgCacheEntrySize;

		// Allow one caller at a time to avoid race conditions
		$scopedLock = $this->getReentrantScopedLock(
			$this->clusterCache->makeKey( 'messages', $code )
		);
		if ( !$scopedLock ) {
			foreach ( $replacements as list( $title ) ) {
				LoggerFactory::getInstance( 'MessageCache' )->error(
					__METHOD__ . ': could not acquire lock to update {title} ({code})',
					[ 'title' => $title, 'code' => $code ] );
			}

			return;
		}

		// Load the existing cache to update it in the local DC cache.
		// The other DCs will see a hash mismatch.
		if ( $this->load( $code, self::FOR_UPDATE ) ) {
			$cache = $this->cache->get( $code );
		} else {
			// Err? Fall back to loading from the database.
			$cache = $this->loadFromDB( $code, self::FOR_UPDATE );
		}
		// Check if individual cache keys should exist and update cache accordingly
		$newTextByTitle = []; // map of (title => content)
		$newBigTitles = []; // map of (title => latest revision ID), like EXCESSIVE in loadFromDB()
		foreach ( $replacements as list( $title ) ) {
			$page = WikiPage::factory( Title::makeTitle( NS_MEDIAWIKI, $title ) );
			$page->loadPageData( $page::READ_LATEST );
			$text = $this->getMessageTextFromContent( $page->getContent() );
			// Remember the text for the blob store update later on
			$newTextByTitle[$title] = $text;
			// Note that if $text is false, then $cache should have a !NONEXISTANT entry
			if ( !is_string( $text ) ) {
				$cache[$title] = '!NONEXISTENT';
			} elseif ( strlen( $text ) > $wgMaxMsgCacheEntrySize ) {
				$cache[$title] = '!TOO BIG';
				$newBigTitles[$title] = $page->getLatest();
			} else {
				$cache[$title] = ' ' . $text;
			}
		}
		// Update HASH for the new key. Incorporates various administrative keys,
		// including the old HASH (and thereby the EXCESSIVE value from loadFromDB()
		// and previous replace() calls), but that doesn't really matter since we
		// only ever compare it for equality with a copy saved by saveToCaches().
		$cache['HASH'] = md5( serialize( $cache + [ 'EXCESSIVE' => $newBigTitles ] ) );
		// Update the too-big WAN cache entries now that we have the new HASH
		foreach ( $newBigTitles as $title => $id ) {
			// Match logic of loadCachedMessagePageEntry()
			$this->wanCache->set(
				$this->bigMessageCacheKey( $cache['HASH'], $title ),
				' ' . $newTextByTitle[$title],
				self::WAN_TTL
			);
		}
		// Mark this cache as definitely being "latest" (non-volatile) so
		// load() calls do not try to refresh the cache with replica DB data
		$cache['LATEST'] = time();
		// Update the process cache
		$this->cache->set( $code, $cache );
		// Pre-emptively update the local datacenter cache so things like edit filter and
		// blacklist changes are reflected immediately; these often use MediaWiki: pages.
		// The datacenter handling replace() calls should be the same one handling edits
		// as they require HTTP POST.
		$this->saveToCaches( $cache, 'all', $code );
		// Release the lock now that the cache is saved
		ScopedCallback::consume( $scopedLock );

		// Relay the purge. Touching this check key expires cache contents
		// and local cache (APC) validation hash across all datacenters.
		$this->wanCache->touchCheckKey( $this->getCheckKey( $code ) );

		// Purge the messages in the message blob store and fire any hook handlers
		$blobStore = MediaWikiServices::getInstance()->getResourceLoader()->getMessageBlobStore();
		foreach ( $replacements as list( $title, $msg ) ) {
			$blobStore->updateMessage( $this->contLang->lcfirst( $msg ) );
			Hooks::run( 'MessageCacheReplace', [ $title, $newTextByTitle[$title] ] );
		}
	}

	/**
	 * Is the given cache array expired due to time passing or a version change?
	 *
	 * @param array $cache
	 * @return bool
	 */
	protected function isCacheExpired( $cache ) {
		if ( !isset( $cache['VERSION'] ) || !isset( $cache['EXPIRY'] ) ) {
			return true;
		}
		if ( $cache['VERSION'] != MSG_CACHE_VERSION ) {
			return true;
		}
		if ( wfTimestampNow() >= $cache['EXPIRY'] ) {
			return true;
		}

		return false;
	}

	/**
	 * Shortcut to update caches.
	 *
	 * @param array $cache Cached messages with a version.
	 * @param string $dest Either "local-only" to save to local caches only
	 *   or "all" to save to all caches.
	 * @param string|bool $code Language code (default: false)
	 * @return bool
	 */
	protected function saveToCaches( array $cache, $dest, $code = false ) {
		if ( $dest === 'all' ) {
			$cacheKey = $this->clusterCache->makeKey( 'messages', $code );
			$success = $this->clusterCache->set( $cacheKey, $cache );
			$this->setValidationHash( $code, $cache );
		} else {
			$success = true;
		}

		$this->saveToLocalCache( $code, $cache );

		return $success;
	}

	/**
	 * Get the md5 used to validate the local APC cache
	 *
	 * @param string $code
	 * @return array (hash or false, bool expiry/volatility status)
	 */
	protected function getValidationHash( $code ) {
		$curTTL = null;
		$value = $this->wanCache->get(
			$this->wanCache->makeKey( 'messages', $code, 'hash', 'v1' ),
			$curTTL,
			[ $this->getCheckKey( $code ) ]
		);

		if ( $value ) {
			$hash = $value['hash'];
			if ( ( time() - $value['latest'] ) < WANObjectCache::TTL_MINUTE ) {
				// Cache was recently updated via replace() and should be up-to-date.
				// That method is only called in the primary datacenter and uses FOR_UPDATE.
				// Also, it is unlikely that the current datacenter is *now* secondary one.
				$expired = false;
			} else {
				// See if the "check" key was bumped after the hash was generated
				$expired = ( $curTTL < 0 );
			}
		} else {
			// No hash found at all; cache must regenerate to be safe
			$hash = false;
			$expired = true;
		}

		return [ $hash, $expired ];
	}

	/**
	 * Set the md5 used to validate the local disk cache
	 *
	 * If $cache has a 'LATEST' UNIX timestamp key, then the hash will not
	 * be treated as "volatile" by getValidationHash() for the next few seconds.
	 * This is triggered when $cache is generated using FOR_UPDATE mode.
	 *
	 * @param string $code
	 * @param array $cache Cached messages with a version
	 */
	protected function setValidationHash( $code, array $cache ) {
		$this->wanCache->set(
			$this->wanCache->makeKey( 'messages', $code, 'hash', 'v1' ),
			[
				'hash' => $cache['HASH'],
				'latest' => $cache['LATEST'] ?? 0
			],
			WANObjectCache::TTL_INDEFINITE
		);
	}

	/**
	 * @param string $key A language message cache key that stores blobs
	 * @param int $timeout Wait timeout in seconds
	 * @return null|ScopedCallback
	 */
	protected function getReentrantScopedLock( $key, $timeout = self::WAIT_SEC ) {
		return $this->clusterCache->getScopedLock( $key, $timeout, self::LOCK_TTL, __METHOD__ );
	}

	/**
	 * Get a message from either the content language or the user language.
	 *
	 * First, assemble a list of languages to attempt getting the message from. This
	 * chain begins with the requested language and its fallbacks and then continues with
	 * the content language and its fallbacks. For each language in the chain, the following
	 * process will occur (in this order):
	 *  1. If a language-specific override, i.e., [[MW:msg/lang]], is available, use that.
	 *     Note: for the content language, there is no /lang subpage.
	 *  2. Fetch from the static CDB cache.
	 *  3. If available, check the database for fallback language overrides.
	 *
	 * This process provides a number of guarantees. When changing this code, make sure all
	 * of these guarantees are preserved.
	 *  * If the requested language is *not* the content language, then the CDB cache for that
	 *    specific language will take precedence over the root database page ([[MW:msg]]).
	 *  * Fallbacks will be just that: fallbacks. A fallback language will never be reached if
	 *    the message is available *anywhere* in the language for which it is a fallback.
	 *
	 * @param string $key The message key
	 * @param bool $useDB If true, look for the message in the DB, false
	 *   to use only the compiled l10n cache.
	 * @param bool|string|object $langcode Code of the language to get the message for.
	 *   - If string and a valid code, will create a standard language object
	 *   - If string but not a valid code, will create a basic language object
	 *   - If boolean and false, create object from the current users language
	 *   - If boolean and true, create object from the wikis content language
	 *   - If language object, use it as given
	 *
	 * @throws MWException When given an invalid key
	 * @return string|bool False if the message doesn't exist, otherwise the
	 *   message (which can be empty)
	 */
	function get( $key, $useDB = true, $langcode = true ) {
		if ( is_int( $key ) ) {
			// Fix numerical strings that somehow become ints
			// on their way here
			$key = (string)$key;
		} elseif ( !is_string( $key ) ) {
			throw new MWException( 'Non-string key given' );
		} elseif ( $key === '' ) {
			// Shortcut: the empty key is always missing
			return false;
		}

		// Normalise title-case input (with some inlining)
		$lckey = self::normalizeKey( $key );

		Hooks::run( 'MessageCache::get', [ &$lckey ] );

		// Loop through each language in the fallback list until we find something useful
		$message = $this->getMessageFromFallbackChain(
			wfGetLangObj( $langcode ),
			$lckey,
			!$this->mDisable && $useDB
		);

		// If we still have no message, maybe the key was in fact a full key so try that
		if ( $message === false ) {
			$parts = explode( '/', $lckey );
			// We may get calls for things that are http-urls from sidebar
			// Let's not load nonexistent languages for those
			// They usually have more than one slash.
			if ( count( $parts ) == 2 && $parts[1] !== '' ) {
				$message = Language::getMessageFor( $parts[0], $parts[1] );
				if ( $message === null ) {
					$message = false;
				}
			}
		}

		// Post-processing if the message exists
		if ( $message !== false ) {
			// Fix whitespace
			$message = str_replace(
				[
					# Fix for trailing whitespace, removed by textarea
					'&#32;',
					# Fix for NBSP, converted to space by firefox
					'&nbsp;',
					'&#160;',
					'&shy;'
				],
				[
					' ',
					"\u{00A0}",
					"\u{00A0}",
					"\u{00AD}"
				],
				$message
			);
		}

		return $message;
	}

	/**
	 * Given a language, try and fetch messages from that language.
	 *
	 * Will also consider fallbacks of that language, the site language, and fallbacks for
	 * the site language.
	 *
	 * @see MessageCache::get
	 * @param Language|StubObject $lang Preferred language
	 * @param string $lckey Lowercase key for the message (as for localisation cache)
	 * @param bool $useDB Whether to include messages from the wiki database
	 * @return string|bool The message, or false if not found
	 */
	protected function getMessageFromFallbackChain( $lang, $lckey, $useDB ) {
		$alreadyTried = [];

		// First try the requested language.
		$message = $this->getMessageForLang( $lang, $lckey, $useDB, $alreadyTried );
		if ( $message !== false ) {
			return $message;
		}

		// Now try checking the site language.
		$message = $this->getMessageForLang( $this->contLang, $lckey, $useDB, $alreadyTried );
		return $message;
	}

	/**
	 * Given a language, try and fetch messages from that language and its fallbacks.
	 *
	 * @see MessageCache::get
	 * @param Language|StubObject $lang Preferred language
	 * @param string $lckey Lowercase key for the message (as for localisation cache)
	 * @param bool $useDB Whether to include messages from the wiki database
	 * @param bool[] $alreadyTried Contains true for each language that has been tried already
	 * @return string|bool The message, or false if not found
	 */
	private function getMessageForLang( $lang, $lckey, $useDB, &$alreadyTried ) {
		$langcode = $lang->getCode();

		// Try checking the database for the requested language
		if ( $useDB ) {
			$uckey = $this->contLang->ucfirst( $lckey );

			if ( !isset( $alreadyTried[$langcode] ) ) {
				$message = $this->getMsgFromNamespace(
					$this->getMessagePageName( $langcode, $uckey ),
					$langcode
				);
				if ( $message !== false ) {
					return $message;
				}
				$alreadyTried[$langcode] = true;
			}
		} else {
			$uckey = null;
		}

		// Check the CDB cache
		$message = $lang->getMessage( $lckey );
		if ( $message !== null ) {
			return $message;
		}

		// Try checking the database for all of the fallback languages
		if ( $useDB ) {
			$fallbackChain = Language::getFallbacksFor( $langcode );

			foreach ( $fallbackChain as $code ) {
				if ( isset( $alreadyTried[$code] ) ) {
					continue;
				}

				$message = $this->getMsgFromNamespace(
					$this->getMessagePageName( $code, $uckey ), $code );

				if ( $message !== false ) {
					return $message;
				}
				$alreadyTried[$code] = true;
			}
		}

		return false;
	}

	/**
	 * Get the message page name for a given language
	 *
	 * @param string $langcode
	 * @param string $uckey Uppercase key for the message
	 * @return string The page name
	 */
	private function getMessagePageName( $langcode, $uckey ) {
		global $wgLanguageCode;

		if ( $langcode === $wgLanguageCode ) {
			// Messages created in the content language will not have the /lang extension
			return $uckey;
		} else {
			return "$uckey/$langcode";
		}
	}

	/**
	 * Get a message from the MediaWiki namespace, with caching. The key must
	 * first be converted to two-part lang/msg form if necessary.
	 *
	 * Unlike self::get(), this function doesn't resolve fallback chains, and
	 * some callers require this behavior. LanguageConverter::parseCachedTable()
	 * and self::get() are some examples in core.
	 *
	 * @param string $title Message cache key with initial uppercase letter
	 * @param string $code Code denoting the language to try
	 * @return string|bool The message, or false if it does not exist or on error
	 */
	public function getMsgFromNamespace( $title, $code ) {
		// Load all MediaWiki page definitions into cache. Note that individual keys
		// already loaded into cache during this request remain in the cache, which
		// includes the value of hook-defined messages.
		$this->load( $code );

		$entry = $this->cache->getField( $code, $title );

		if ( $entry !== null ) {
			// Message page exists as an override of a software messages
			if ( substr( $entry, 0, 1 ) === ' ' ) {
				// The message exists and is not '!TOO BIG' or '!ERROR'
				return (string)substr( $entry, 1 );
			} elseif ( $entry === '!NONEXISTENT' ) {
				// The text might be '-' or missing due to some data loss
				return false;
			}
			// Load the message page, utilizing the individual message cache.
			// If the page does not exist, there will be no hook handler fallbacks.
			$entry = $this->loadCachedMessagePageEntry(
				$title,
				$code,
				$this->cache->getField( $code, 'HASH' )
			);
		} else {
			// Message page either does not exist or does not override a software message
			if ( !$this->isMainCacheable( $title, $this->overridable ) ) {
				// Message page does not override any software-defined message. A custom
				// message might be defined to have content or settings specific to the wiki.
				// Load the message page, utilizing the individual message cache as needed.
				$entry = $this->loadCachedMessagePageEntry(
					$title,
					$code,
					$this->cache->getField( $code, 'HASH' )
				);
			}
			if ( $entry === null || substr( $entry, 0, 1 ) !== ' ' ) {
				// Message does not have a MediaWiki page definition; try hook handlers
				$message = false;
				Hooks::run( 'MessagesPreLoad', [ $title, &$message, $code ] );
				if ( $message !== false ) {
					$this->cache->setField( $code, $title, ' ' . $message );
				} else {
					$this->cache->setField( $code, $title, '!NONEXISTENT' );
				}

				return $message;
			}
		}

		if ( $entry !== false && substr( $entry, 0, 1 ) === ' ' ) {
			if ( $this->cacheVolatile[$code] ) {
				// Make sure that individual keys respect the WAN cache holdoff period too
				LoggerFactory::getInstance( 'MessageCache' )->debug(
					__METHOD__ . ': loading volatile key \'{titleKey}\'',
					[ 'titleKey' => $title, 'code' => $code ] );
			} else {
				$this->cache->setField( $code, $title, $entry );
			}
			// The message exists, so make sure a string is returned
			return (string)substr( $entry, 1 );
		}

		$this->cache->setField( $code, $title, '!NONEXISTENT' );

		return false;
	}

	/**
	 * @param string $dbKey
	 * @param string $code
	 * @param string $hash
	 * @return string Either " <MESSAGE>" or "!NONEXISTANT"
	 */
	private function loadCachedMessagePageEntry( $dbKey, $code, $hash ) {
		$fname = __METHOD__;
		return $this->srvCache->getWithSetCallback(
			$this->srvCache->makeKey( 'messages-big', $hash, $dbKey ),
			BagOStuff::TTL_HOUR,
			function () use ( $code, $dbKey, $hash, $fname ) {
				return $this->wanCache->getWithSetCallback(
					$this->bigMessageCacheKey( $hash, $dbKey ),
					self::WAN_TTL,
					function ( $oldValue, &$ttl, &$setOpts ) use ( $dbKey, $code, $fname ) {
						// Try loading the message from the database
						$dbr = wfGetDB( DB_REPLICA );
						$setOpts += Database::getCacheSetOptions( $dbr );
						// Use newKnownCurrent() to avoid querying revision/user tables
						$title = Title::makeTitle( NS_MEDIAWIKI, $dbKey );
						$revision = Revision::newKnownCurrent( $dbr, $title );
						if ( !$revision ) {
							// The wiki doesn't have a local override page. Cache absence with normal TTL.
							// When overrides are created, self::replace() takes care of the cache.
							return '!NONEXISTENT';
						}
						$content = $revision->getContent();
						if ( $content ) {
							$message = $this->getMessageTextFromContent( $content );
						} else {
							LoggerFactory::getInstance( 'MessageCache' )->warning(
								$fname . ': failed to load page text for \'{titleKey}\'',
								[ 'titleKey' => $dbKey, 'code' => $code ]
							);
							$message = null;
						}

						if ( !is_string( $message ) ) {
							// Revision failed to load Content, or Content is incompatible with wikitext.
							// Possibly a temporary loading failure.
							$ttl = 5;

							return '!NONEXISTENT';
						}

						return ' ' . $message;
					}
				);
			}
		);
	}

	/**
	 * @param string $message
	 * @param bool $interface
	 * @param Language|null $language
	 * @param Title|null $title
	 * @return string
	 */
	public function transform( $message, $interface = false, $language = null, $title = null ) {
		// Avoid creating parser if nothing to transform
		if ( strpos( $message, '{{' ) === false ) {
			return $message;
		}

		if ( $this->mInParser ) {
			return $message;
		}

		$parser = $this->getParser();
		if ( $parser ) {
			$popts = $this->getParserOptions();
			$popts->setInterfaceMessage( $interface );
			$popts->setTargetLanguage( $language );

			$userlang = $popts->setUserLang( $language );
			$this->mInParser = true;
			$message = $parser->transformMsg( $message, $popts, $title );
			$this->mInParser = false;
			$popts->setUserLang( $userlang );
		}

		return $message;
	}

	/**
	 * @return Parser
	 */
	public function getParser() {
		global $wgParserConf;
		if ( !$this->mParser ) {
			$parser = MediaWikiServices::getInstance()->getParser();
			# Do some initialisation so that we don't have to do it twice
			$parser->firstCallInit();
			# Clone it and store it
			$class = $wgParserConf['class'];
			if ( $class == ParserDiffTest::class ) {
				# Uncloneable
				// @phan-suppress-next-line PhanTypeMismatchProperty
				$this->mParser = new $class( $wgParserConf );
			} else {
				$this->mParser = clone $parser;
			}
		}

		return $this->mParser;
	}

	/**
	 * @param string $text
	 * @param Title|null $title
	 * @param bool $linestart Whether or not this is at the start of a line
	 * @param bool $interface Whether this is an interface message
	 * @param Language|string|null $language Language code
	 * @return ParserOutput|string
	 */
	public function parse( $text, $title = null, $linestart = true,
		$interface = false, $language = null
	) {
		global $wgTitle;

		if ( $this->mInParser ) {
			return htmlspecialchars( $text );
		}

		$parser = $this->getParser();
		$popts = $this->getParserOptions();
		$popts->setInterfaceMessage( $interface );

		if ( is_string( $language ) ) {
			$language = Language::factory( $language );
		}
		$popts->setTargetLanguage( $language );

		if ( !$title || !$title instanceof Title ) {
			wfDebugLog( 'GlobalTitleFail', __METHOD__ . ' called by ' .
				wfGetAllCallers( 6 ) . ' with no title set.' );
			$title = $wgTitle;
		}
		// Sometimes $wgTitle isn't set either...
		if ( !$title ) {
			# It's not uncommon having a null $wgTitle in scripts. See r80898
			# Create a ghost title in such case
			$title = Title::makeTitle( NS_SPECIAL, 'Badtitle/title not set in ' . __METHOD__ );
		}

		$this->mInParser = true;
		$res = $parser->parse( $text, $title, $popts, $linestart );
		$this->mInParser = false;

		return $res;
	}

	public function disable() {
		$this->mDisable = true;
	}

	public function enable() {
		$this->mDisable = false;
	}

	/**
	 * Whether DB/cache usage is disabled for determining messages
	 *
	 * If so, this typically indicates either:
	 *   - a) load() failed to find a cached copy nor query the DB
	 *   - b) we are in a special context or error mode that cannot use the DB
	 * If the DB is ignored, any derived HTML output or cached objects may be wrong.
	 * To avoid long-term cache pollution, TTLs can be adjusted accordingly.
	 *
	 * @return bool
	 * @since 1.27
	 */
	public function isDisabled() {
		return $this->mDisable;
	}

	/**
	 * Clear all stored messages in global and local cache
	 *
	 * Mainly used after a mass rebuild
	 */
	public function clear() {
		$langs = Language::fetchLanguageNames( null, 'mw' );
		foreach ( array_keys( $langs ) as $code ) {
			$this->wanCache->touchCheckKey( $this->getCheckKey( $code ) );
		}
		$this->cache->clear();
		$this->loadedLanguages = [];
	}

	/**
	 * @param string $key
	 * @return array
	 */
	public function figureMessage( $key ) {
		global $wgLanguageCode;

		$pieces = explode( '/', $key );
		if ( count( $pieces ) < 2 ) {
			return [ $key, $wgLanguageCode ];
		}

		$lang = array_pop( $pieces );
		if ( !Language::fetchLanguageName( $lang, null, 'mw' ) ) {
			return [ $key, $wgLanguageCode ];
		}

		$message = implode( '/', $pieces );

		return [ $message, $lang ];
	}

	/**
	 * Get all message keys stored in the message cache for a given language.
	 * If $code is the content language code, this will return all message keys
	 * for which MediaWiki:msgkey exists. If $code is another language code, this
	 * will ONLY return message keys for which MediaWiki:msgkey/$code exists.
	 * @param string $code Language code
	 * @return array Array of message keys (strings)
	 */
	public function getAllMessageKeys( $code ) {
		$this->load( $code );
		if ( !$this->cache->has( $code ) ) {
			// Apparently load() failed
			return null;
		}
		// Remove administrative keys
		$cache = $this->cache->get( $code );
		unset( $cache['VERSION'] );
		unset( $cache['EXPIRY'] );
		unset( $cache['EXCESSIVE'] );
		// Remove any !NONEXISTENT keys
		$cache = array_diff( $cache, [ '!NONEXISTENT' ] );

		// Keys may appear with a capital first letter. lcfirst them.
		return array_map( [ $this->contLang, 'lcfirst' ], array_keys( $cache ) );
	}

	/**
	 * Purge message caches when a MediaWiki: page is created, updated, or deleted
	 *
	 * @param Title $title Message page title
	 * @param Content|null $content New content for edit/create, null on deletion
	 * @since 1.29
	 */
	public function updateMessageOverride( Title $title, Content $content = null ) {
		$msgText = $this->getMessageTextFromContent( $content );
		if ( $msgText === null ) {
			$msgText = false; // treat as not existing
		}

		$this->replace( $title->getDBkey(), $msgText );

		if ( $this->contLang->hasVariants() ) {
			$this->contLang->updateConversionTable( $title );
		}
	}

	/**
	 * @param string $code Language code
	 * @return string WAN cache key usable as a "check key" against language page edits
	 */
	public function getCheckKey( $code ) {
		return $this->wanCache->makeKey( 'messages', $code );
	}

	/**
	 * @param Content|null $content Content or null if the message page does not exist
	 * @return string|bool|null Returns false if $content is null and null on error
	 */
	private function getMessageTextFromContent( Content $content = null ) {
		// @TODO: could skip pseudo-messages like js/css here, based on content model
		if ( $content ) {
			// Message page exists...
			// XXX: Is this the right way to turn a Content object into a message?
			// NOTE: $content is typically either WikitextContent, JavaScriptContent or
			//       CssContent. MessageContent is *not* used for storing messages, it's
			//       only used for wrapping them when needed.
			$msgText = $content->getWikitextForTransclusion();
			if ( $msgText === false || $msgText === null ) {
				// This might be due to some kind of misconfiguration...
				$msgText = null;
				LoggerFactory::getInstance( 'MessageCache' )->warning(
					__METHOD__ . ": message content doesn't provide wikitext "
					. "(content model: " . $content->getModel() . ")" );
			}
		} else {
			// Message page does not exist...
			$msgText = false;
		}

		return $msgText;
	}

	/**
	 * @param string $hash Hash for this version of the entire key/value overrides map
	 * @param string $title Message cache key with initial uppercase letter
	 * @return string
	 */
	private function bigMessageCacheKey( $hash, $title ) {
		return $this->wanCache->makeKey( 'messages-big', $hash, $title );
	}
}
