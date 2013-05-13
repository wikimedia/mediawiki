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
 *
 */
define( 'MSG_LOAD_TIMEOUT', 60 );
define( 'MSG_LOCK_TIMEOUT', 10 );
define( 'MSG_WAIT_TIMEOUT', 10 );
define( 'MSG_CACHE_VERSION', 1 );

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

	// Should  mean that database cannot be used, but check
	protected $mDisable;

	/// Lifetime for cache, used by object caching
	protected $mExpiry;

	/**
	 * Message cache has it's own parser which it uses to transform
	 * messages.
	 */
	protected $mParserOptions, $mParser;

	/// Variable for tracking which variables are already loaded
	protected $mLoadedLanguages = array();

	/**
	 * Singleton instance
	 *
	 * @var MessageCache
	 */
	private static $instance;

	/**
	 * @var bool
	 */
	protected $mInParser = false;

	/**
	 * Get the signleton instance of this class
	 *
	 * @since 1.18
	 * @return MessageCache object
	 */
	public static function singleton() {
		if ( is_null( self::$instance ) ) {
			global $wgUseDatabaseMessages, $wgMsgCacheExpiry;
			self::$instance = new self( wfGetMessageCacheStorage(), $wgUseDatabaseMessages, $wgMsgCacheExpiry );
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
	 * Actual format of the file depends on the $wgLocalMessageCacheSerialized
	 * setting.
	 *
	 * @param string $hash the hash of contents, to check validity.
	 * @param $code Mixed: Optional language code, see documenation of load().
	 * @return bool on failure.
	 */
	function loadFromLocal( $hash, $code ) {
		global $wgCacheDirectory, $wgLocalMessageCacheSerialized;

		$filename = "$wgCacheDirectory/messages-" . wfWikiID() . "-$code";

		# Check file existence
		wfSuppressWarnings();
		$file = fopen( $filename, 'r' );
		wfRestoreWarnings();
		if ( !$file ) {
			return false; // No cache file
		}

		if ( $wgLocalMessageCacheSerialized ) {
			// Check to see if the file has the hash specified
			$localHash = fread( $file, 32 );
			if ( $hash === $localHash ) {
				// All good, get the rest of it
				$serialized = '';
				while ( !feof( $file ) ) {
					$serialized .= fread( $file, 100000 );
				}
				fclose( $file );
				return $this->setCache( unserialize( $serialized ), $code );
			} else {
				fclose( $file );
				return false; // Wrong hash
			}
		} else {
			$localHash = substr( fread( $file, 40 ), 8 );
			fclose( $file );
			if ( $hash != $localHash ) {
				return false; // Wrong hash
			}

			# Require overwrites the member variable or just shadows it?
			require( $filename );
			return $this->setCache( $this->mCache, $code );
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

	function saveToScript( $array, $hash, $code ) {
		global $wgCacheDirectory;

		$filename = "$wgCacheDirectory/messages-" . wfWikiID() . "-$code";
		$tempFilename = $filename . '.tmp';
		wfMkdirParents( $wgCacheDirectory, null, __METHOD__ ); // might fail

		wfSuppressWarnings();
		$file = fopen( $tempFilename, 'w' );
		wfRestoreWarnings();

		if ( !$file ) {
			wfDebug( "Unable to open local cache file for writing\n" );
			return;
		}

		fwrite( $file, "<?php\n//$hash\n\n \$this->mCache = array(" );

		foreach ( $array as $key => $message ) {
			$key = $this->escapeForScript( $key );
			$message = $this->escapeForScript( $message );
			fwrite( $file, "'$key' => '$message',\n" );
		}

		fwrite( $file, ");\n?>" );
		fclose( $file);
		rename( $tempFilename, $filename );
	}

	function escapeForScript( $string ) {
		$string = str_replace( '\\', '\\\\', $string );
		$string = str_replace( '\'', '\\\'', $string );
		return $string;
	}

	/**
	 * Set the cache to $cache, if it is valid. Otherwise set the cache to false.
	 *
	 * @return bool
	 */
	function setCache( $cache, $code ) {
		if ( isset( $cache['VERSION'] ) && $cache['VERSION'] == MSG_CACHE_VERSION ) {
			$this->mCache[$code] = $cache;
			return true;
		} else {
			return false;
		}
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
	 * @param bool|String $code String: language to which load messages
	 * @throws MWException
	 * @return bool
	 */
	function load( $code = false ) {
		global $wgUseLocalMessageCache;

		$exception = null; // deferred error

		if( !is_string( $code ) ) {
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
		$where = array(); # Debug info, delayed to avoid spamming debug log too much
		$cacheKey = wfMemcKey( 'messages', $code ); # Key in memc for messages

		# (1) local cache
		# Hash of the contents is stored in memcache, to detect if local cache goes
		# out of date (due to update in other thread?)
		if ( $wgUseLocalMessageCache ) {
			wfProfileIn( __METHOD__ . '-fromlocal' );

			$hash = $this->mMemc->get( wfMemcKey( 'messages', $code, 'hash' ) );
			if ( $hash ) {
				$success = $this->loadFromLocal( $hash, $code );
				if ( $success ) $where[] = 'got from local cache';
			}
			wfProfileOut( __METHOD__ . '-fromlocal' );
		}

		# (2) memcache
		# Fails if nothing in cache, or in the wrong version.
		if ( !$success ) {
			wfProfileIn( __METHOD__ . '-fromcache' );
			$cache = $this->mMemc->get( $cacheKey );
			$success = $this->setCache( $cache, $code );
			if ( $success ) {
				$where[] = 'got from global cache';
				$this->saveToCaches( $cache, false, $code );
			}
			wfProfileOut( __METHOD__ . '-fromcache' );
		}

		# (3)
		# Nothing in caches... so we need create one and store it in caches
		if ( !$success ) {
			$where[] = 'cache is empty';
			$where[] = 'loading from database';

			if ( $this->lock( $cacheKey ) ) {
				$that = $this;
				$osc = new ScopedCallback( function() use ( $that, $cacheKey ) {
					$that->unlock( $cacheKey );
				} );
			}
			# Limit the concurrency of loadFromDB to a single process
			# This prevents the site from going down when the cache expires
			$statusKey = wfMemcKey( 'messages', $code, 'status' );
			$success = $this->mMemc->add( $statusKey, 'loading', MSG_LOAD_TIMEOUT );
			if ( $success ) { // acquired lock
				$cache = $this->mMemc;
				$isc = new ScopedCallback( function() use ( $cache, $statusKey ) {
					$cache->delete( $statusKey );
				} );
				$cache = $this->loadFromDB( $code );
				$success = $this->setCache( $cache, $code );
				if ( $success ) { // messages loaded
					$success = $this->saveToCaches( $cache, true, $code );
					$isc = null; // unlock
					if ( !$success ) {
						$this->mMemc->set( $statusKey, 'error', 60 * 5 );
						wfDebug( __METHOD__ . ": set() error: restart memcached server!\n" );
						$exception = new MWException( "Could not save cache for '$code'." );
					}
				} else {
					$isc = null; // unlock
					$exception = new MWException( "Could not load cache from DB for '$code'." );
				}
			} else {
				$exception = new MWException( "Could not acquire '$statusKey' lock." );
			}
			$osc = null; // unlock
		}

		if ( !$success ) {
			$this->mDisable = true;
			$this->mCache = false;
			// This used to go on, but that led to lots of nasty side
			// effects like gadgets and sidebar getting cached with their
			// default content
			if ( $exception instanceof Exception ) {
				throw $exception;
			} else {
				throw new MWException( "MessageCache failed to load messages" );
			}
		} else {
			# All good, just record the success
			$info = implode( ', ', $where );
			wfDebug( __METHOD__ . ": Loading $code... $info\n" );
			$this->mLoadedLanguages[$code] = true;
		}
		wfProfileOut( __METHOD__ );
		return $success;
	}

	/**
	 * Loads cacheable messages from the database. Messages bigger than
	 * $wgMaxMsgCacheEntrySize are assigned a special value, and are loaded
	 * on-demand from the database later.
	 *
	 * @param string $code language code.
	 * @return Array: loaded messages for storing in caches.
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
			if( $text === false ) {
				// Failed to fetch data; possible ES errors?
				// Store a marker to fetch on-demand as a workaround...
				$entry = '!TOO BIG';
				wfDebugLog( 'MessageCache', __METHOD__ . ": failed to load message page text for {$row->page_title} ($code)" );
			} else {
				$entry = ' ' . $text;
			}
			$cache[$row->page_title] = $entry;
		}

		$cache['VERSION'] = MSG_CACHE_VERSION;
		wfProfileOut( __METHOD__ );
		return $cache;
	}

	/**
	 * Updates cache as necessary when message page is changed
	 *
	 * @param string $title name of the page changed.
	 * @param $text Mixed: new contents of the page.
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
		$this->saveToCaches( $this->mCache[$code], true, $code );
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
	 * Shortcut to update caches.
	 *
	 * @param array $cache cached messages with a version.
	 * @param bool $memc Wether to update or not memcache.
	 * @param string $code Language code.
	 * @return bool on somekind of error.
	 */
	protected function saveToCaches( $cache, $memc = true, $code = false ) {
		wfProfileIn( __METHOD__ );
		global $wgUseLocalMessageCache, $wgLocalMessageCacheSerialized;

		$cacheKey = wfMemcKey( 'messages', $code );

		if ( $memc ) {
			$success = $this->mMemc->set( $cacheKey, $cache, $this->mExpiry );
		} else {
			$success = true;
		}

		# Save to local cache
		if ( $wgUseLocalMessageCache ) {
			$serialized = serialize( $cache );
			$hash = md5( $serialized );
			$this->mMemc->set( wfMemcKey( 'messages', $code, 'hash' ), $hash, $this->mExpiry );
			if ( $wgLocalMessageCacheSerialized ) {
				$this->saveToLocal( $serialized, $hash, $code );
			} else {
				$this->saveToScript( $cache, $hash, $code );
			}
		}

		wfProfileOut( __METHOD__ );
		return $success;
	}

	/**
	 * Represents a write lock on the messages key
	 *
	 * @param $key string
	 *
	 * @return Boolean: success
	 */
	function lock( $key ) {
		$lockKey = $key . ':lock';
		for ( $i = 0; $i < MSG_WAIT_TIMEOUT && !$this->mMemc->add( $lockKey, 1, MSG_LOCK_TIMEOUT ); $i++ ) {
			sleep( 1 );
		}

		return $i >= MSG_WAIT_TIMEOUT;
	}

	function unlock( $key ) {
		$lockKey = $key . ':lock';
		$this->mMemc->delete( $lockKey );
	}

	/**
	 * Get a message from either the content language or the user language.
	 *
	 * @param string $key the message cache key
	 * @param $useDB Boolean: get the message from the DB, false to use only
	 *               the localisation
	 * @param bool|string $langcode Code of the language to get the message for, if
	 *                  it is a valid code create a language for that language,
	 *                  if it is a string but not a valid code then make a basic
	 *                  language object, if it is a false boolean then use the
	 *                  current users language (as a fallback for the old
	 *                  parameter functionality), or if it is a true boolean
	 *                  then use the wikis content language (also as a
	 *                  fallback).
	 * @param $isFullKey Boolean: specifies whether $key is a two part key
	 *                   "msg/lang".
	 *
	 * @throws MWException
	 * @return string|bool
	 */
	function get( $key, $useDB = true, $langcode = true, $isFullKey = false ) {
		global $wgLanguageCode, $wgContLang;

		if ( is_int( $key ) ) {
			// "Non-string key given" exception sometimes happens for numerical strings that become ints somewhere on their way here
			$key = strval( $key );
		}

		if ( !is_string( $key ) ) {
			throw new MWException( 'Non-string key given' );
		}

		if ( strval( $key ) === '' ) {
			# Shortcut: the empty key is always missing
			return false;
		}

		$lang = wfGetLangObj( $langcode );
		if ( !$lang ) {
			throw new MWException( "Bad lang code $langcode given" );
		}

		$langcode = $lang->getCode();

		$message = false;

		# Normalise title-case input (with some inlining)
		$lckey = str_replace( ' ', '_', $key );
		if ( ord( $key ) < 128 ) {
			$lckey[0] = strtolower( $lckey[0] );
			$uckey = ucfirst( $lckey );
		} else {
			$lckey = $wgContLang->lcfirst( $lckey );
			$uckey = $wgContLang->ucfirst( $lckey );
		}

		# Try the MediaWiki namespace
		if( !$this->mDisable && $useDB ) {
			$title = $uckey;
			if( !$isFullKey && ( $langcode != $wgLanguageCode ) ) {
				$title .= '/' . $langcode;
			}
			$message = $this->getMsgFromNamespace( $title, $langcode );
		}

		# Try the array in the language object
		if ( $message === false ) {
			$message = $lang->getMessage( $lckey );
			if ( is_null( $message ) ) {
				$message = false;
			}
		}

		# Try the array of another language
		if( $message === false ) {
			$parts = explode( '/', $lckey );
			# We may get calls for things that are http-urls from sidebar
			# Let's not load nonexistent languages for those
			# They usually have more than one slash.
			if ( count( $parts ) == 2 && $parts[1] !== '' ) {
				$message = Language::getMessageFor( $parts[0], $parts[1] );
				if ( is_null( $message ) ) {
					$message = false;
				}
			}
		}

		# Is this a custom message? Try the default language in the db...
		if( ( $message === false || $message === '-' ) &&
			!$this->mDisable && $useDB &&
			!$isFullKey && ( $langcode != $wgLanguageCode ) ) {
			$message = $this->getMsgFromNamespace( $uckey, $wgLanguageCode );
		}

		# Final fallback
		if( $message === false ) {
			return false;
		}

		# Fix whitespace
		$message = strtr( $message,
			array(
				# Fix for trailing whitespace, removed by textarea
				'&#32;' => ' ',
				# Fix for NBSP, converted to space by firefox
				'&nbsp;' => "\xc2\xa0",
				'&#160;' => "\xc2\xa0",
			) );

		return $message;
	}

	/**
	 * Get a message from the MediaWiki namespace, with caching. The key must
	 * first be converted to two-part lang/msg form if necessary.
	 *
	 * @param string $title Message cache key with initial uppercase letter.
	 * @param string $code code denoting the language to try.
	 *
	 * @return string|bool False on failure
	 */
	function getMsgFromNamespace( $title, $code ) {
		$this->load( $code );
		if ( isset( $this->mCache[$code][$title] ) ) {
			$entry = $this->mCache[$code][$title];
			if ( substr( $entry, 0, 1 ) === ' ' ) {
				return substr( $entry, 1 );
			} elseif ( $entry === '!NONEXISTENT' ) {
				return false;
			} elseif( $entry === '!TOO BIG' ) {
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
				return substr( $entry, 1 );
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
				wfDebugLog( 'MessageCache', __METHOD__ . ": failed to load message page text for {$title} ($code)" );
				$message = null; // no negative caching
			} else {
				// XXX: Is this the right way to turn a Content object into a message?
				// NOTE: $content is typically either WikitextContent, JavaScriptContent or CssContent.
				//       MessageContent is *not* used for storing messages, it's only used for wrapping them when needed.
				$message = $content->getWikitextForTransclusion();

				if ( $message === false || $message === null ) {
					wfDebugLog( 'MessageCache', __METHOD__ . ": message content doesn't provide wikitext "
								. "(content model: " . $content->getContentHandler() . ")" );

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
	 * @param $message string
	 * @param $interface bool
	 * @param $language
	 * @param $title Title
	 * @return string
	 */
	function transform( $message, $interface = false, $language = null, $title = null ) {
		// Avoid creating parser if nothing to transform
		if( strpos( $message, '{{' ) === false ) {
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
	 * @param $text string
	 * @param $title Title
	 * @param $linestart bool
	 * @param $interface bool
	 * @param $language
	 * @return ParserOutput|string
	 */
	public function parse( $text, $title = null, $linestart = true, $interface = false, $language = null  ) {
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
		if( count( $pieces ) < 2 ) {
			return array( $key, $wgLanguageCode );
		}

		$lang = array_pop( $pieces );
		if( !Language::fetchLanguageName( $lang, null, 'mw' ) ) {
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
	 * @param $code string
	 * @return array of message keys (strings)
	 */
	public function getAllMessageKeys( $code ) {
		global $wgContLang;
		$this->load( $code );
		if ( !isset( $this->mCache[$code] ) ) {
			// Apparently load() failed
			return null;
		}
		$cache = $this->mCache[$code]; // Copy the cache
		unset( $cache['VERSION'] ); // Remove the VERSION key
		$cache = array_diff( $cache, array( '!NONEXISTENT' ) ); // Remove any !NONEXISTENT keys
		// Keys may appear with a capital first letter. lcfirst them.
		return array_map( array( $wgContLang, 'lcfirst' ), array_keys( $cache ) );
	}
}
