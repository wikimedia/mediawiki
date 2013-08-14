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

/**
 * MediaWiki message cache structure version.
 * Bump this whenever the message cache format has changed.
 */
define( 'MSG_CACHE_VERSION', 1 );

/**
 * Memcached timeout when loading a key.
 * See MessageCache::load()
 */
define( 'MSG_LOAD_TIMEOUT', 60 );

/**
 * Memcached timeout when locking a key for a writing operation.
 * See MessageCache::lock()
 */
define( 'MSG_LOCK_TIMEOUT', 30 );
/**
 * Number of times we will try to acquire a lock from Memcached.
 * This comes in addition to MSG_LOCK_TIMEOUT.
 */
define( 'MSG_WAIT_TIMEOUT', 30 );

/**
 * Message cache
 * Performs various MediaWiki namespace-related functions
 * @ingroup Cache
 */
class MessageCache {
	/**
	 * Process local cache of loaded messages that are defined in
	 * MediaWiki namespace. First array level is a language code,
	 * second level is message key and the values are either message
	 * content prefixed with space, or !NONEXISTENT for negative
	 * caching.
	 */
	protected $mCache;

	/**
	 * Should  mean that database cannot be used, but check
	 * @var bool $mDisable
	 */
	protected $mDisable;

	/**
	 * Lifetime for cache, used by object caching.
	 * Set on construction, see __construct().
	 */
	protected $mExpiry;

	/**
	 * Message cache has it's own parser which it uses to transform
	 * messages.
	 */
	protected $mParserOptions, $mParser;

	/**
	 * Variable for tracking which variables are already loaded
	 * @var array $mLoadedLanguages
	 */
	protected $mLoadedLanguages = array();

	/**
	 * Singleton instance
	 *
	 * @var MessageCache $instance
	 */
	private static $instance;

	/**
	 * @var bool $mInParser
	 */
	protected $mInParser = false;

	/**
	 * Get the signleton instance of this class
	 *
	 * @since 1.18
	 * @return MessageCache
	 */
	public static function singleton() {
		if ( is_null( self::$instance ) ) {
			global $wgUseDatabaseMessages, $wgMsgCacheExpiry;
			self::$instance = new self(
				wfGetMessageCacheStorage(),
				$wgUseDatabaseMessages,
				$wgMsgCacheExpiry
			);
		}
		return self::$instance;
	}

	/**
	 * Destroy the singleton instance
	 *
	 * @since 1.18
	 */
	public static function destroyInstance() {
		self::$instance = null;
	}

	/**
	 * @param ObjectCache $memCached A cache instance. If none, fall back to CACHE_NONE.
	 * @param bool $useDB
	 * @param int $expiry Lifetime for cache. @see $mExpiry.
	 */
	function __construct( $memCached, $useDB, $expiry ) {
		if ( !$memCached ) {
			$memCached = wfGetCache( CACHE_NONE );
		}

		$this->mMemc = $memCached;
		$this->mDisable = !$useDB;
		$this->mExpiry = $expiry;
	}

	/**
	 * ParserOptions is lazy initialised.
	 *
	 * @return ParserOptions
	 */
	function getParserOptions() {
		if ( !$this->mParserOptions ) {
			$this->mParserOptions = new ParserOptions;
			$this->mParserOptions->setEditSection( false );
		}
		return $this->mParserOptions;
	}

	/**
	 * Try to load the cache from a local file.
	 *
	 * @param string $hash the hash of contents, to check validity.
	 * @param Mixed $code Optional language code, see documenation of load().
	 * @return array The cache array
	 */
	function getLocalCache( $hash, $code ) {
		global $wgCacheDirectory;

		$filename = "$wgCacheDirectory/messages-" . wfWikiID() . "-$code";

		# Check file existence
		wfSuppressWarnings();
		$file = fopen( $filename, 'r' );
		wfRestoreWarnings();
		if ( !$file ) {
			return false; // No cache file
		}

		// Check to see if the file has the hash specified
		$localHash = fread( $file, 32 );
		if ( $hash === $localHash ) {
			// All good, get the rest of it
			$serialized = '';
			while ( !feof( $file ) ) {
				$serialized .= fread( $file, 100000 );
			}
			fclose( $file );
			return unserialize( $serialized );
		} else {
			fclose( $file );
			return false; // Wrong hash
		}
	}

	/**
	 * Save the cache to a local file.
	 */
	function saveToLocal( $serialized, $hash, $code ) {
		global $wgCacheDirectory;

		$filename = "$wgCacheDirectory/messages-" . wfWikiID() . "-$code";
		wfMkdirParents( $wgCacheDirectory, null, __METHOD__ ); // might fail

		wfSuppressWarnings();
		$file = fopen( $filename, 'w' );
		wfRestoreWarnings();

		if ( !$file ) {
			wfDebug( "Unable to open local cache file for writing\n" );
			return;
		}

		fwrite( $file, $hash . $serialized );
		fclose( $file );
		wfSuppressWarnings();
		chmod( $filename, 0666 );
		wfRestoreWarnings();
	}

	/**
	 * Loads messages from caches or from database in this order:
	 * (1) local message cache (if $wgUseLocalMessageCache is enabled)
	 * (2) memcached
	 * (3) from the database.
	 *
	 * When succesfully loading from (2) or (3), all higher level caches are
	 * updated for the newest version.
	 *
	 * Nothing is loaded if member variable mDisable is true, either manually
	 * set by calling code or if message loading fails (is this possible?).
	 *
	 * Returns true if cache is already populated or it was succesfully populated,
	 * or false if populating empty cache fails. Also returns true if MessageCache
	 * is disabled.
	 *
	 * @param bool|String $code Language to which load messages
	 * @throws MWException
	 * @return bool
	 */
	function load( $code = false ) {
		global $wgUseLocalMessageCache;

		if ( !is_string( $code ) ) {
			# This isn't really nice, so at least make a note about it and try to
			# fall back
			wfDebug( __METHOD__ . " called without providing a language code\n" );
			$code = 'en';
		}

		# Don't do double loading...
		if ( isset( $this->mLoadedLanguages[$code] ) ) {
			return true;
		}

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
		wfProfileIn( __METHOD__ );
		$success = false; # Keep track of success
		$staleCache = false; # a cache array with expired data, or false if none has been loaded
		$where = array(); # Debug info, delayed to avoid spamming debug log too much
		$cacheKey = wfMemcKey( 'messages', $code ); # Key in memc for messages

		# Local cache
		# Hash of the contents is stored in memcache, to detect if local cache goes
		# out of date (e.g. due to replace() on some other server)
		if ( $wgUseLocalMessageCache ) {
			wfProfileIn( __METHOD__ . '-fromlocal' );

			$hash = $this->mMemc->get( wfMemcKey( 'messages', $code, 'hash' ) );
			if ( $hash ) {
				$cache = $this->getLocalCache( $hash, $code );
				if ( !$cache ) {
					$where[] = 'local cache is empty or has the wrong hash';
				} elseif ( $this->isCacheExpired( $cache ) ) {
					$where[] = 'local cache is expired';
					$staleCache = $cache;
				} else {
					$where[] = 'got from local cache';
					$success = true;
					$this->mCache[$code] = $cache;
				}
			}
			wfProfileOut( __METHOD__ . '-fromlocal' );
		}

		if ( !$success ) {
			# Try the global cache. If it is empty, try to acquire a lock. If
			# the lock can't be acquired, wait for the other thread to finish
			# and then try the global cache a second time.
			for ( $failedAttempts = 0; $failedAttempts < 2; $failedAttempts++ ) {
				wfProfileIn( __METHOD__ . '-fromcache' );
				$cache = $this->mMemc->get( $cacheKey );
				if ( !$cache ) {
					$where[] = 'global cache is empty';
				} elseif ( $this->isCacheExpired( $cache ) ) {
					$where[] = 'global cache is expired';
					$staleCache = $cache;
				} else {
					$where[] = 'got from global cache';
					$this->mCache[$code] = $cache;
					$this->saveToCaches( $cache, 'local-only', $code );
					$success = true;
				}

				wfProfileOut( __METHOD__ . '-fromcache' );

				if ( $success ) {
					# Done, no need to retry
					break;
				}

				# We need to call loadFromDB. Limit the concurrency to a single
				# process. This prevents the site from going down when the cache
				# expires.
				$statusKey = wfMemcKey( 'messages', $code, 'status' );
				$acquired = $this->mMemc->add( $statusKey, 'loading', MSG_LOAD_TIMEOUT );
				if ( $acquired ) {
					# Unlock the status key if there is an exception
					$that = $this;
					$statusUnlocker = new ScopedCallback( function () use ( $that, $statusKey ) {
						$that->mMemc->delete( $statusKey );
					} );

					# Now let's regenerate
					$where[] = 'loading from database';

					# Lock the cache to prevent conflicting writes
					# If this lock fails, it doesn't really matter, it just means the
					# write is potentially non-atomic, e.g. the results of a replace()
					# may be discarded.
					if ( $this->lock( $cacheKey ) ) {
						$mainUnlocker = new ScopedCallback( function () use ( $that, $cacheKey ) {
							$that->unlock( $cacheKey );
						} );
					} else {
						$mainUnlocker = null;
						$where[] = 'could not acquire main lock';
					}

					$cache = $this->loadFromDB( $code );
					$this->mCache[$code] = $cache;
					$success = true;
					$saveSuccess = $this->saveToCaches( $cache, 'all', $code );

					# Unlock
					ScopedCallback::consume( $mainUnlocker );
					ScopedCallback::consume( $statusUnlocker );

					if ( !$saveSuccess ) {
						# Cache save has failed.
						# There are two main scenarios where this could be a problem:
						#
						#   - The cache is more than the maximum size (typically
						#     1MB compressed).
						#
						#   - Memcached has no space remaining in the relevant slab
						#     class. This is unlikely with recent versions of
						#     memcached.
						#
						# Either way, if there is a local cache, nothing bad will
						# happen. If there is no local cache, disabling the message
						# cache for all requests avoids incurring a loadFromDB()
						# overhead on every request, and thus saves the wiki from
						# complete downtime under moderate traffic conditions.
						if ( !$wgUseLocalMessageCache ) {
							$this->mMemc->set( $statusKey, 'error', 60 * 5 );
							$where[] = 'could not save cache, disabled globally for 5 minutes';
						} else {
							$where[] = "could not save global cache";
						}
					}

					# Load from DB complete, no need to retry
					break;
				} elseif ( $staleCache ) {
					# Use the stale cache while some other thread constructs the new one
					$where[] = 'using stale cache';
					$this->mCache[$code] = $staleCache;
					$success = true;
					break;
				} elseif ( $failedAttempts > 0 ) {
					# Already retried once, still failed, so don't do another lock/unlock cycle
					# This case will typically be hit if memcached is down, or if
					# loadFromDB() takes longer than MSG_WAIT_TIMEOUT
					$where[] = "could not acquire status key.";
					break;
				} else {
					$status = $this->mMemc->get( $statusKey );
					if ( $status === 'error' ) {
						# Disable cache
						break;
					} else {
						# Wait for the other thread to finish, then retry
						$where[] = 'waited for other thread to complete';
						$this->lock( $cacheKey );
						$this->unlock( $cacheKey );
					}
				}
			}
		}

		if ( !$success ) {
			$where[] = 'loading FAILED - cache is disabled';
			$this->mDisable = true;
			$this->mCache = false;
			# This used to throw an exception, but that led to nasty side effects like
			# the whole wiki being instantly down if the memcached server died
		} else {
			# All good, just record the success
			$this->mLoadedLanguages[$code] = true;
		}
		$info = implode( ', ', $where );
		wfDebug( __METHOD__ . ": Loading $code... $info\n" );
		wfProfileOut( __METHOD__ );
		return $success;
	}

	/**
	 * Loads cacheable messages from the database. Messages bigger than
	 * $wgMaxMsgCacheEntrySize are assigned a special value, and are loaded
	 * on-demand from the database later.
	 *
	 * @param string $code Language code.
	 * @return array Loaded messages for storing in caches.
	 */
	function loadFromDB( $code ) {
		wfProfileIn( __METHOD__ );
		global $wgMaxMsgCacheEntrySize, $wgLanguageCode, $wgAdaptiveMessageCache;
		$dbr = wfGetDB( DB_SLAVE );
		$cache = array();

		# Common conditions
		$conds = array(
			'page_is_redirect' => 0,
			'page_namespace' => NS_MEDIAWIKI,
		);

		$mostused = array();
		if ( $wgAdaptiveMessageCache && $code !== $wgLanguageCode ) {
			if ( !isset( $this->mCache[$wgLanguageCode] ) ) {
				$this->load( $wgLanguageCode );
			}
			$mostused = array_keys( $this->mCache[$wgLanguageCode] );
			foreach ( $mostused as $key => $value ) {
				$mostused[$key] = "$value/$code";
			}
		}

		if ( count( $mostused ) ) {
			$conds['page_title'] = $mostused;
		} elseif ( $code !== $wgLanguageCode ) {
			$conds[] = 'page_title' . $dbr->buildLike( $dbr->anyString(), '/', $code );
		} else {
			# Effectively disallows use of '/' character in NS_MEDIAWIKI for uses
			# other than language code.
			$conds[] = 'page_title NOT' . $dbr->buildLike( $dbr->anyString(), '/', $dbr->anyString() );
		}

		# Conditions to fetch oversized pages to ignore them
		$bigConds = $conds;
		$bigConds[] = 'page_len > ' . intval( $wgMaxMsgCacheEntrySize );

		# Load titles for all oversized pages in the MediaWiki namespace
		$res = $dbr->select( 'page', 'page_title', $bigConds, __METHOD__ . "($code)-big" );
		foreach ( $res as $row ) {
			$cache[$row->page_title] = '!TOO BIG';
		}

		# Conditions to load the remaining pages with their contents
		$smallConds = $conds;
		$smallConds[] = 'page_latest=rev_id';
		$smallConds[] = 'rev_text_id=old_id';
		$smallConds[] = 'page_len <= ' . intval( $wgMaxMsgCacheEntrySize );

		$res = $dbr->select(
			array( 'page', 'revision', 'text' ),
			array( 'page_title', 'old_text', 'old_flags' ),
			$smallConds,
			__METHOD__ . "($code)-small"
		);

		foreach ( $res as $row ) {
			$text = Revision::getRevisionText( $row );
			if ( $text === false ) {
				// Failed to fetch data; possible ES errors?
				// Store a marker to fetch on-demand as a workaround...
				$entry = '!TOO BIG';
				wfDebugLog(
					'MessageCache',
					__METHOD__
						. ": failed to load message page text for {$row->page_title} ($code)"
				);
			} else {
				$entry = ' ' . $text;
			}
			$cache[$row->page_title] = $entry;
		}

		$cache['VERSION'] = MSG_CACHE_VERSION;
		$cache['EXPIRY'] = wfTimestamp( TS_MW, time() + $this->mExpiry );
		wfProfileOut( __METHOD__ );
		return $cache;
	}

	/**
	 * Updates cache as necessary when message page is changed
	 *
	 * @param string $title Name of the page changed.
	 * @param mixed $text New contents of the page.
	 */
	public function replace( $title, $text ) {
		global $wgMaxMsgCacheEntrySize;
		wfProfileIn( __METHOD__ );

		if ( $this->mDisable ) {
			wfProfileOut( __METHOD__ );
			return;
		}

		list( $msg, $code ) = $this->figureMessage( $title );

		$cacheKey = wfMemcKey( 'messages', $code );
		$this->load( $code );
		$this->lock( $cacheKey );

		$titleKey = wfMemcKey( 'messages', 'individual', $title );

		if ( $text === false ) {
			# Article was deleted
			$this->mCache[$code][$title] = '!NONEXISTENT';
			$this->mMemc->delete( $titleKey );
		} elseif ( strlen( $text ) > $wgMaxMsgCacheEntrySize ) {
			# Check for size
			$this->mCache[$code][$title] = '!TOO BIG';
			$this->mMemc->set( $titleKey, ' ' . $text, $this->mExpiry );
		} else {
			$this->mCache[$code][$title] = ' ' . $text;
			$this->mMemc->delete( $titleKey );
		}

		# Update caches
		$this->saveToCaches( $this->mCache[$code], 'all', $code );
		$this->unlock( $cacheKey );

		// Also delete cached sidebar... just in case it is affected
		$codes = array( $code );
		if ( $code === 'en' ) {
			// Delete all sidebars, like for example on action=purge on the
			// sidebar messages
			$codes = array_keys( Language::fetchLanguageNames() );
		}

		global $wgMemc;
		foreach ( $codes as $code ) {
			$sidebarKey = wfMemcKey( 'sidebar', $code );
			$wgMemc->delete( $sidebarKey );
		}

		// Update the message in the message blob store
		global $wgContLang;
		MessageBlobStore::updateMessage( $wgContLang->lcfirst( $msg ) );

		wfRunHooks( 'MessageCacheReplace', array( $title, $text ) );

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Is the given cache array expired due to time passing or a version change?
	 *
	 * @param $cache
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
	protected function saveToCaches( $cache, $dest, $code = false ) {
		wfProfileIn( __METHOD__ );
		global $wgUseLocalMessageCache;

		$cacheKey = wfMemcKey( 'messages', $code );

		if ( $dest === 'all' ) {
			$success = $this->mMemc->set( $cacheKey, $cache );
		} else {
			$success = true;
		}

		# Save to local cache
		if ( $wgUseLocalMessageCache ) {
			$serialized = serialize( $cache );
			$hash = md5( $serialized );
			$this->mMemc->set( wfMemcKey( 'messages', $code, 'hash' ), $hash );
			$this->saveToLocal( $serialized, $hash, $code );
		}

		wfProfileOut( __METHOD__ );
		return $success;
	}

	/**
	 * Represents a write lock on the messages key.
	 *
	 * Will retry MessageCache::MSG_WAIT_TIMEOUT times, each operations having
	 * a timeout of MessageCache::MSG_LOCK_TIMEOUT.
	 *
	 * @param string $key
	 * @return Boolean: success
	 */
	function lock( $key ) {
		$lockKey = $key . ':lock';
		$acquired = false;
		$testDone = false;
		for ( $i = 0; $i < MSG_WAIT_TIMEOUT && !$acquired; $i++ ) {
			$acquired = $this->mMemc->add( $lockKey, 1, MSG_LOCK_TIMEOUT );
			if ( $acquired ) {
				break;
			}

			# Fail fast if memcached is totally down
			if ( !$testDone ) {
				$testDone = true;
				if ( !$this->mMemc->set( wfMemcKey( 'test' ), 'test', 1 ) ) {
					break;
				}
			}
			sleep( 1 );
		}

		return $acquired;
	}

	function unlock( $key ) {
		$lockKey = $key . ':lock';
		$this->mMemc->delete( $lockKey );
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
	 * @param string $key the message key
	 * @param bool $useDB If true, look for the message in the DB, false
	 *                    to use only the compiled l10n cache.
	 * @param bool|string|object $langcode Code of the language to get the message for.
	 *        - If string and a valid code, will create a standard language object
	 *        - If string but not a valid code, will create a basic language object
	 *        - If boolean and false, create object from the current users language
	 *        - If boolean and true, create object from the wikis content language
	 *        - If language object, use it as given
	 * @param bool $isFullKey specifies whether $key is a two part key
	 *                   "msg/lang".
	 *
	 * @throws MWException when given an invalid key
	 * @return string|bool False if the message doesn't exist, otherwise the message (which can be empty)
	 */
	function get( $key, $useDB = true, $langcode = true, $isFullKey = false ) {
		global $wgContLang;

		$section = new ProfileSection( __METHOD__ );

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

		// For full keys, get the language code from the key
		$pos = strrpos( $key, '/' );
		if ( $isFullKey && $pos !== false ) {
			$langcode = substr( $key, $pos + 1 );
			$key = substr( $key, 0, $pos );
		}

		// Normalise title-case input (with some inlining)
		$lckey = strtr( $key, ' ', '_' );
		if ( ord( $key ) < 128 ) {
			$lckey[0] = strtolower( $lckey[0] );
			$uckey = ucfirst( $lckey );
		} else {
			$lckey = $wgContLang->lcfirst( $lckey );
			$uckey = $wgContLang->ucfirst( $lckey );
		}

		// Loop through each language in the fallback list until we find something useful
		$lang = wfGetLangObj( $langcode );
		$message = $this->getMessageFromFallbackChain( $lang, $lckey, $uckey, !$this->mDisable && $useDB );

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
				array(
					# Fix for trailing whitespace, removed by textarea
					'&#32;',
					# Fix for NBSP, converted to space by firefox
					'&nbsp;',
					'&#160;',
				),
				array(
					' ',
					"\xc2\xa0",
					"\xc2\xa0"
				),
				$message
			);
		}

		return $message;
	}

	/**
	 * Given a language, try and fetch a message from that language, then the
	 * fallbacks of that language, then the site language, then the fallbacks for the
	 * site language.
	 *
	 * @param Language $lang Requested language
	 * @param string $lckey Lowercase key for the message
	 * @param string $uckey Uppercase key for the message
	 * @param bool $useDB Whether to use the database
	 *
	 * @see MessageCache::get
	 * @return string|bool The message, or false if not found
	 */
	protected function getMessageFromFallbackChain( $lang, $lckey, $uckey, $useDB ) {
		global $wgLanguageCode, $wgContLang;

		$langcode = $lang->getCode();
		$message = false;

		// First try the requested language.
		if ( $useDB ) {
			if ( $langcode === $wgLanguageCode ) {
				// Messages created in the content language will not have the /lang extension
				$message = $this->getMsgFromNamespace( $uckey, $langcode );
			} else {
				$message = $this->getMsgFromNamespace( "$uckey/$langcode", $langcode );
			}
		}

		if ( $message !== false ) {
			return $message;
		}

		// Check the CDB cache
		$message = $lang->getMessage( $lckey );
		if ( $message !== null ) {
			return $message;
		}

		list( $fallbackChain, $siteFallbackChain ) = Language::getFallbacksIncludingSiteLanguage( $langcode );

		// Next try checking the database for all of the fallback languages of the requested language.
		if ( $useDB ) {
			foreach ( $fallbackChain as $code ) {
				if ( $code === $wgLanguageCode ) {
					// Messages created in the content language will not have the /lang extension
					$message = $this->getMsgFromNamespace( $uckey, $code );
				} else {
					$message = $this->getMsgFromNamespace( "$uckey/$code", $code );
				}

				if ( $message !== false ) {
					// Found the message.
					return $message;
				}
			}
		}

		// Now try checking the site language.
		if ( $useDB ) {
			$message = $this->getMsgFromNamespace( $uckey, $wgLanguageCode );
			if ( $message !== false ) {
				return $message;
			}
		}

		$message = $wgContLang->getMessage( $lckey );
		if ( $message !== null ) {
			return $message;
		}

		// Finally try the DB for the site language's fallbacks.
		if ( $useDB ) {
			foreach ( $siteFallbackChain as $code ) {
				$message = $this->getMsgFromNamespace( "$uckey/$code", $code );
				if ( $message === false && $code === $wgLanguageCode ) {
					// Messages created in the content language will not have the /lang extension
					$message = $this->getMsgFromNamespace( $uckey, $code );
				}

				if ( $message !== false ) {
					// Found the message.
					return $message;
				}
			}
		}

		return false;
	}

	/**
	 * Get a message from the MediaWiki namespace, with caching. The key must
	 * first be converted to two-part lang/msg form if necessary.
	 *
	 * Unlike self::get(), this function doesn't resolve fallback chains, and
	 * some callers require this behavior. LanguageConverter::parseCachedTable()
	 * and self::get() are some examples in core.
	 *
	 * @param string $title Message cache key with initial uppercase letter.
	 * @param string $code Code denoting the language to try.
	 * @return string|bool The message, or false if it does not exist or on error
	 */
	function getMsgFromNamespace( $title, $code ) {
		$this->load( $code );
		if ( isset( $this->mCache[$code][$title] ) ) {
			$entry = $this->mCache[$code][$title];
			if ( substr( $entry, 0, 1 ) === ' ' ) {
				// The message exists, so make sure a string
				// is returned.
				return (string)substr( $entry, 1 );
			} elseif ( $entry === '!NONEXISTENT' ) {
				return false;
			} elseif ( $entry === '!TOO BIG' ) {
				// Fall through and try invididual message cache below
			}
		} else {
			// XXX: This is not cached in process cache, should it?
			$message = false;
			wfRunHooks( 'MessagesPreLoad', array( $title, &$message ) );
			if ( $message !== false ) {
				return $message;
			}

			return false;
		}

		# Try the individual message cache
		$titleKey = wfMemcKey( 'messages', 'individual', $title );
		$entry = $this->mMemc->get( $titleKey );
		if ( $entry ) {
			if ( substr( $entry, 0, 1 ) === ' ' ) {
				$this->mCache[$code][$title] = $entry;
				// The message exists, so make sure a string
				// is returned.
				return (string)substr( $entry, 1 );
			} elseif ( $entry === '!NONEXISTENT' ) {
				$this->mCache[$code][$title] = '!NONEXISTENT';
				return false;
			} else {
				# Corrupt/obsolete entry, delete it
				$this->mMemc->delete( $titleKey );
			}
		}

		# Try loading it from the database
		$revision = Revision::newFromTitle(
			Title::makeTitle( NS_MEDIAWIKI, $title ), false, Revision::READ_LATEST
		);
		if ( $revision ) {
			$content = $revision->getContent();
			if ( !$content ) {
				// A possibly temporary loading failure.
				wfDebugLog(
					'MessageCache',
					__METHOD__ . ": failed to load message page text for {$title} ($code)"
				);
				$message = null; // no negative caching
			} else {
				// XXX: Is this the right way to turn a Content object into a message?
				// NOTE: $content is typically either WikitextContent, JavaScriptContent or
				//       CssContent. MessageContent is *not* used for storing messages, it's
				//       only used for wrapping them when needed.
				$message = $content->getWikitextForTransclusion();

				if ( $message === false || $message === null ) {
					wfDebugLog(
						'MessageCache',
						__METHOD__ . ": message content doesn't provide wikitext "
							. "(content model: " . $content->getContentHandler() . ")"
					);

					$message = false; // negative caching
				} else {
					$this->mCache[$code][$title] = ' ' . $message;
					$this->mMemc->set( $titleKey, ' ' . $message, $this->mExpiry );
				}
			}
		} else {
			$message = false; // negative caching
		}

		if ( $message === false ) { // negative caching
			$this->mCache[$code][$title] = '!NONEXISTENT';
			$this->mMemc->set( $titleKey, '!NONEXISTENT', $this->mExpiry );
		}

		return $message;
	}

	/**
	 * @param string $message
	 * @param bool $interface
	 * @param string $language Language code
	 * @param Title $title
	 * @return string
	 */
	function transform( $message, $interface = false, $language = null, $title = null ) {
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
	function getParser() {
		global $wgParser, $wgParserConf;
		if ( !$this->mParser && isset( $wgParser ) ) {
			# Do some initialisation so that we don't have to do it twice
			$wgParser->firstCallInit();
			# Clone it and store it
			$class = $wgParserConf['class'];
			if ( $class == 'Parser_DiffTest' ) {
				# Uncloneable
				$this->mParser = new $class( $wgParserConf );
			} else {
				$this->mParser = clone $wgParser;
			}
		}
		return $this->mParser;
	}

	/**
	 * @param string $text
	 * @param Title $title
	 * @param bool $linestart Whether or not this is at the start of a line
	 * @param bool $interface Whether this is an interface message
	 * @param string $language Language code
	 * @return ParserOutput|string
	 */
	public function parse( $text, $title = null, $linestart = true,
		$interface = false, $language = null
	) {
		if ( $this->mInParser ) {
			return htmlspecialchars( $text );
		}

		$parser = $this->getParser();
		$popts = $this->getParserOptions();
		$popts->setInterfaceMessage( $interface );
		$popts->setTargetLanguage( $language );

		wfProfileIn( __METHOD__ );
		if ( !$title || !$title instanceof Title ) {
			global $wgTitle;
			$title = $wgTitle;
		}
		// Sometimes $wgTitle isn't set either...
		if ( !$title ) {
			# It's not uncommon having a null $wgTitle in scripts. See r80898
			# Create a ghost title in such case
			$title = Title::newFromText( 'Dwimmerlaik' );
		}

		$this->mInParser = true;
		$res = $parser->parse( $text, $title, $popts, $linestart );
		$this->mInParser = false;

		wfProfileOut( __METHOD__ );
		return $res;
	}

	function disable() {
		$this->mDisable = true;
	}

	function enable() {
		$this->mDisable = false;
	}

	/**
	 * Clear all stored messages. Mainly used after a mass rebuild.
	 */
	function clear() {
		$langs = Language::fetchLanguageNames( null, 'mw' );
		foreach ( array_keys( $langs ) as $code ) {
			# Global cache
			$this->mMemc->delete( wfMemcKey( 'messages', $code ) );
			# Invalidate all local caches
			$this->mMemc->delete( wfMemcKey( 'messages', $code, 'hash' ) );
		}
		$this->mLoadedLanguages = array();
	}

	/**
	 * @param $key
	 * @return array
	 */
	public function figureMessage( $key ) {
		global $wgLanguageCode;
		$pieces = explode( '/', $key );
		if ( count( $pieces ) < 2 ) {
			return array( $key, $wgLanguageCode );
		}

		$lang = array_pop( $pieces );
		if ( !Language::fetchLanguageName( $lang, null, 'mw' ) ) {
			return array( $key, $wgLanguageCode );
		}

		$message = implode( '/', $pieces );
		return array( $message, $lang );
	}

	/**
	 * Get all message keys stored in the message cache for a given language.
	 * If $code is the content language code, this will return all message keys
	 * for which MediaWiki:msgkey exists. If $code is another language code, this
	 * will ONLY return message keys for which MediaWiki:msgkey/$code exists.
	 * @param string $code Language code
	 * @return array of message keys (strings)
	 */
	public function getAllMessageKeys( $code ) {
		global $wgContLang;
		$this->load( $code );
		if ( !isset( $this->mCache[$code] ) ) {
			// Apparently load() failed
			return null;
		}
		// Remove administrative keys
		$cache = $this->mCache[$code];
		unset( $cache['VERSION'] );
		unset( $cache['EXPIRY'] );
		// Remove any !NONEXISTENT keys
		$cache = array_diff( $cache, array( '!NONEXISTENT' ) );
		// Keys may appear with a capital first letter. lcfirst them.
		return array_map( array( $wgContLang, 'lcfirst' ), array_keys( $cache ) );
	}
}
