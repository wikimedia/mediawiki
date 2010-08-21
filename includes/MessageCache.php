<?php
/**
 * @file
 * @ingroup Cache
 */

/**
 *
 */
define( 'MSG_LOAD_TIMEOUT', 60);
define( 'MSG_LOCK_TIMEOUT', 10);
define( 'MSG_WAIT_TIMEOUT', 10);
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
	 * Used for automatic detection of most used messages.
	 */
	protected $mRequestedMessages = array();

	/**
	 * How long the message request counts are stored. Longer period gives
	 * better sample, but also takes longer to adapt changes. The counts
	 * are aggregrated per day, regardless of the value of this variable.
	 */
	protected static $mAdaptiveDataAge = 604800;

	/**
	 * Filter the tail of less used messages that are requested more seldom
	 * than this factor times the number of request of most requested message.
	 * These messages are not loaded in the default set, but are still cached
	 * individually on demand with the normal cache expiry time.
	 */
	protected static $mAdaptiveInclusionThreshold = 0.05;

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
	 */
	function getParserOptions() {
		if ( !$this->mParserOptions ) {
			$this->mParserOptions = new ParserOptions;
		}
		return $this->mParserOptions;
	}

	/**
	 * Try to load the cache from a local file.
	 * Actual format of the file depends on the $wgLocalMessageCacheSerialized
	 * setting.
	 *
	 * @param $hash String: the hash of contents, to check validity.
	 * @param $code Mixed: Optional language code, see documenation of load().
	 * @return false on failure.
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
			$localHash=substr(fread($file,40),8);
			fclose($file);
			if ($hash!=$localHash) {
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
		wfMkdirParents( $wgCacheDirectory ); // might fail

		wfSuppressWarnings();
		$file = fopen( $filename, 'w' );
		wfRestoreWarnings();

		if ( !$file ) {
			wfDebug( "Unable to open local cache file for writing\n" );
			return;
		}

		fwrite( $file, $hash . $serialized );
		fclose( $file );
		@chmod( $filename, 0666 );
	}

	function saveToScript( $array, $hash, $code ) {
		global $wgCacheDirectory;

		$filename = "$wgCacheDirectory/messages-" . wfWikiID() . "-$code";
		$tempFilename = $filename . '.tmp';
  		wfMkdirParents( $wgCacheDirectory ); // might fail

		wfSuppressWarnings();
		$file = fopen( $tempFilename, 'w');
		wfRestoreWarnings();

		if ( !$file ) {
			wfDebug( "Unable to open local cache file for writing\n" );
			return;
		}

		fwrite($file,"<?php\n//$hash\n\n \$this->mCache = array(");

		foreach ($array as $key => $message) {
			$key = $this->escapeForScript($key);
			$messages = $this->escapeForScript($message);
			fwrite($file, "'$key' => '$message',\n");
		}

		fwrite($file,");\n?>");
		fclose($file);
		rename($tempFilename, $filename);
	}

	function escapeForScript($string) {
		$string = str_replace( '\\', '\\\\', $string );
		$string = str_replace( '\'', '\\\'', $string );
		return $string;
	}

	/**
	 * Set the cache to $cache, if it is valid. Otherwise set the cache to false.
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
	 * @param $code String: language to which load messages
	 */
	function load( $code = false ) {
		global $wgUseLocalMessageCache;

		if( !is_string( $code ) ) {
			# This isn't really nice, so at least make a note about it and try to
			# fall back
			wfDebug( __METHOD__ . " called without providing a language code\n" );
			$code = 'en';
		}

		# Don't do double loading...
		if ( isset($this->mLoadedLanguages[$code]) ) return true;

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

			$this->lock($cacheKey);

			# Limit the concurrency of loadFromDB to a single process
			# This prevents the site from going down when the cache expires
			$statusKey = wfMemcKey( 'messages', $code, 'status' );
			$success = $this->mMemc->add( $statusKey, 'loading', MSG_LOAD_TIMEOUT );
			if ( $success ) {
				$cache = $this->loadFromDB( $code );
				$success = $this->setCache( $cache, $code );
			}
			if ( $success ) {
				$success = $this->saveToCaches( $cache, true, $code );
				if ( $success ) {
					$this->mMemc->delete( $statusKey );
				} else {
					$this->mMemc->set( $statusKey, 'error', 60*5 );
					wfDebug( "MemCached set error in MessageCache: restart memcached server!\n" );
				}
			}
			$this->unlock($cacheKey);
		}

		if ( !$success ) {
			# Bad luck... this should not happen
			$where[] = 'loading FAILED - cache is disabled';
			$info = implode( ', ', $where );
			wfDebug( __METHOD__ . ": Loading $code... $info\n" );
			$this->mDisable = true;
			$this->mCache = false;
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
	 * @param $code \string Language code.
	 * @return \array Loaded messages for storing in caches.
	 */
	function loadFromDB( $code ) {
		wfProfileIn( __METHOD__ );
		global $wgMaxMsgCacheEntrySize, $wgContLanguageCode, $wgAdaptiveMessageCache;
		$dbr = wfGetDB( DB_SLAVE );
		$cache = array();

		# Common conditions
		$conds = array(
			'page_is_redirect' => 0,
			'page_namespace' => NS_MEDIAWIKI,
		);

		$mostused = array();
		if ( $wgAdaptiveMessageCache ) {
			$mostused = $this->getMostUsedMessages();
			if ( $code !== $wgContLanguageCode ) {
				foreach ( $mostused as $key => $value ) $mostused[$key] = "$value/$code";
			}
		}

		if ( count( $mostused ) ) {
			$conds['page_title'] = $mostused;
		} elseif ( $code !== $wgContLanguageCode ) {
			$conds[] = 'page_title' . $dbr->buildLike( $dbr->anyString(), "/$code" );
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

		$res = $dbr->select( array( 'page', 'revision', 'text' ),
			array( 'page_title', 'old_text', 'old_flags' ),
			$smallConds, __METHOD__ . "($code)-small" );

		foreach ( $res as $row ) {
			$cache[$row->page_title] = ' ' . Revision::getRevisionText( $row );
		}

		foreach ( $mostused as $key ) {
			if ( !isset( $cache[$key] ) ) {
				$cache[$key] = '!NONEXISTENT';
			}
		}

		$cache['VERSION'] = MSG_CACHE_VERSION;
		wfProfileOut( __METHOD__ );
		return $cache;
	}

	/**
	 * Updates cache as necessary when message page is changed
	 *
	 * @param $title String: name of the page changed.
	 * @param $text Mixed: new contents of the page.
	 */
	public function replace( $title, $text ) {
		global $wgMaxMsgCacheEntrySize;
		wfProfileIn( __METHOD__ );

		if ( $this->mDisable ) {
			return;
		}

		list( , $code ) = $this->figureMessage( $title );

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
		if ( $code === 'en'  ) {
			// Delete all sidebars, like for example on action=purge on the
			// sidebar messages
			$codes = array_keys( Language::getLanguageNames() );
		}

		global $parserMemc;
		foreach ( $codes as $code ) {
			$sidebarKey = wfMemcKey( 'sidebar', $code );
			$parserMemc->delete( $sidebarKey );
		}

		wfRunHooks( "MessageCacheReplace", array( $title, $text ) );

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Shortcut to update caches.
	 *
	 * @param $cache Array: cached messages with a version.
	 * @param $memc Bool: Wether to update or not memcache.
	 * @param $code String: Language code.
	 * @return False on somekind of error.
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
			if ($wgLocalMessageCacheSerialized) {
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
	 * @return Boolean: success
	 */
	function lock($key) {
		$lockKey = $key . ':lock';
		for ($i=0; $i < MSG_WAIT_TIMEOUT && !$this->mMemc->add( $lockKey, 1, MSG_LOCK_TIMEOUT ); $i++ ) {
			sleep(1);
		}

		return $i >= MSG_WAIT_TIMEOUT;
	}

	function unlock($key) {
		$lockKey = $key . ':lock';
		$this->mMemc->delete( $lockKey );
	}

	/**
	 * Get a message from either the content language or the user language.
	 *
	 * @param $key String: the message cache key
	 * @param $useDB Boolean: get the message from the DB, false to use only
	 *               the localisation
	 * @param $langcode String: code of the language to get the message for, if
	 *                  it is a valid code create a language for that language,
	 *                  if it is a string but not a valid code then make a basic
	 *                  language object, if it is a false boolean then use the
	 *                  current users language (as a fallback for the old
	 *                  parameter functionality), or if it is a true boolean
	 *                  then use the wikis content language (also as a
	 *                  fallback).
	 * @param $isFullKey Boolean: specifies whether $key is a two part key
	 *                   "msg/lang".
	 */
	function get( $key, $useDB = true, $langcode = true, $isFullKey = false ) {
		global $wgContLanguageCode, $wgContLang;

		if ( !is_string( $key ) ) {
			throw new MWException( "Non-string key given" );
		}

		if ( strval( $key ) === '' ) {
			# Shortcut: the empty key is always missing
			return false;
		}

		$lang = wfGetLangObj( $langcode );
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

		/* Record each message request, but only once per request.
		 * This information is not used unless $wgAdaptiveMessageCache
		 * is enabled. */
		$this->mRequestedMessages[$uckey] = true;

		# Try the MediaWiki namespace
		if( !$this->mDisable && $useDB ) {
			$title = $uckey;
			if(!$isFullKey && ( $langcode != $wgContLanguageCode ) ) {
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
		if( ($message === false || $message === '-' ) &&
			!$this->mDisable && $useDB &&
			!$isFullKey && ($langcode != $wgContLanguageCode) ) {
			$message = $this->getMsgFromNamespace( $uckey, $wgContLanguageCode );
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
	 * @param $title String: Message cache key with initial uppercase letter.
	 * @param $code String: code denoting the language to try.
	 */
	function getMsgFromNamespace( $title, $code ) {
		global $wgAdaptiveMessageCache;
		$big = false;

		$this->load( $code );
		if ( isset( $this->mCache[$code][$title] ) ) {
			$entry = $this->mCache[$code][$title];
			if ( substr( $entry, 0, 1 ) === ' ' ) {
				return substr( $entry, 1 );
			} elseif ( $entry === '!NONEXISTENT' ) {
				return false;
			} elseif( $entry === '!TOO BIG' ) {
				// Fall through and try invididual message cache below

			} else {
				// XXX: This is not cached in process cache, should it?
				$message = false;
				wfRunHooks('MessagesPreLoad', array( $title, &$message ) );
				if ( $message !== false ) {
					return $message;
				}

				/* If message cache is in normal mode, it is guaranteed
				 * (except bugs) that there is always entry (or placeholder)
				 * in the cache if message exists. Thus we can do minor
				 * performance improvement and return false early.
				 */
				if ( !$wgAdaptiveMessageCache ) {
					return false;
				}
			}
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
		$revision = Revision::newFromTitle( Title::makeTitle( NS_MEDIAWIKI, $title ) );
		if ( $revision ) {
			$message = $revision->getText();
			$this->mCache[$code][$title] = ' ' . $message;
			$this->mMemc->set( $titleKey, ' ' . $message, $this->mExpiry );
		} else {
			$message = false;
			$this->mCache[$code][$title] = '!NONEXISTENT';
			$this->mMemc->set( $titleKey, '!NONEXISTENT', $this->mExpiry );
		}

		return $message;
	}

	function transform( $message, $interface = false, $language = null ) {
		// Avoid creating parser if nothing to transform
		if( strpos( $message, '{{' ) === false ) {
			return $message;
		}

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
			#wfDebug( __METHOD__ . ": following contents triggered transform: $message\n" );
		}
		if ( $this->mParser ) {
			$popts = $this->getParserOptions();
			$popts->setInterfaceMessage( $interface );
			$popts->setTargetLanguage( $language );
			$message = $this->mParser->transformMsg( $message, $popts );
		}
		return $message;
	}

	function disable() { $this->mDisable = true; }
	function enable() { $this->mDisable = false; }

	/** @deprecated */
	function disableTransform(){
		wfDeprecated( __METHOD__ );
	}
	function enableTransform() {
		wfDeprecated( __METHOD__ );
	}
	function setTransform( $x ) {
		wfDeprecated( __METHOD__ );
	}
	function getTransform() {
		wfDeprecated( __METHOD__ );
		return false;
	}

	/**
	 * Clear all stored messages. Mainly used after a mass rebuild.
	 */
	function clear() {
		$langs = Language::getLanguageNames( false );
		foreach ( array_keys($langs) as $code ) {
			# Global cache
			$this->mMemc->delete( wfMemcKey( 'messages', $code ) );
			# Invalidate all local caches
			$this->mMemc->delete( wfMemcKey( 'messages', $code, 'hash' ) );
		}
	}

 	/**
	 * Add a message to the cache
	 * @deprecated Use $wgExtensionMessagesFiles
	 *
	 * @param $key Mixed
	 * @param $value Mixed
	 * @param $lang String: the messages language, English by default
	 */
	function addMessage( $key, $value, $lang = 'en' ) {
		wfDeprecated( __METHOD__ );
		$lc = Language::getLocalisationCache();
		$lc->addLegacyMessages( array( $lang => array( $key => $value ) ) );
	}

	/**
	 * Add an associative array of message to the cache
	 * @deprecated Use $wgExtensionMessagesFiles
	 *
	 * @param $messages Array: an associative array of key => values to be added
	 * @param $lang String: the messages language, English by default
	 */
	function addMessages( $messages, $lang = 'en' ) {
		wfDeprecated( __METHOD__ );
		$lc = Language::getLocalisationCache();
		$lc->addLegacyMessages( array( $lang => $messages ) );
	}

	/**
	 * Add a 2-D array of messages by lang. Useful for extensions.
	 * @deprecated Use $wgExtensionMessagesFiles
	 *
	 * @param $messages Array: the array to be added
	 */
	function addMessagesByLang( $messages ) {
		wfDeprecated( __METHOD__ );
		$lc = Language::getLocalisationCache();
		$lc->addLegacyMessages( $messages );
	}

	/**
	 * Set a hook for addMessagesByLang()
	 */
	function setExtensionMessagesHook( $callback ) {
		$this->mAddMessagesHook = $callback;
	}

	/**
	 * @deprecated
	 */
	function loadAllMessages( $lang = false ) {
	}

	/**
	 * @deprecated
	 */
	function loadMessagesFile( $filename, $langcode = false ) {
	}

	public function figureMessage( $key ) {
		global $wgContLanguageCode;
		$pieces = explode( '/', $key );
		if( count( $pieces ) < 2 )
			return array( $key, $wgContLanguageCode );

		$lang = array_pop( $pieces );
		$validCodes = Language::getLanguageNames();
		if( !array_key_exists( $lang, $validCodes ) )
			return array( $key, $wgContLanguageCode );

		$message = implode( '/', $pieces );
		return array( $message, $lang );
	}

	public static function logMessages() {
		global $wgMessageCache, $wgAdaptiveMessageCache;
		if ( !$wgAdaptiveMessageCache || !$wgMessageCache instanceof MessageCache ) {
			return;
		}

		$cachekey = wfMemckey( 'message-profiling' );
		$cache = wfGetCache( CACHE_DB );
		$data = $cache->get( $cachekey );

		if ( !$data ) $data = array();

		$age = self::$mAdaptiveDataAge;
		$filterDate = substr( wfTimestamp( TS_MW, time()-$age ), 0, 8 );
		foreach ( array_keys( $data ) as $key ) {
			if ( $key < $filterDate ) unset( $data[$key] );
		}

		$index = substr( wfTimestampNow(), 0, 8 );
		if ( !isset( $data[$index] ) ) $data[$index] = array();

		foreach ( $wgMessageCache->mRequestedMessages as $message => $_ ) {
			if ( !isset( $data[$index][$message] ) ) $data[$index][$message] = 0;
			$data[$index][$message]++;
		}

		$cache->set( $cachekey, $data );
	}

	public function getMostUsedMessages() {
		$cachekey = wfMemckey( 'message-profiling' );
		$cache = wfGetCache( CACHE_DB );
		$data = $cache->get( $cachekey );
		if ( !$data ) return array();

		$list = array();

		foreach( $data as $date => $messages ) {
			foreach( $messages as $message => $count ) {
				$key = $message;
				if ( !isset( $list[$key] ) ) $list[$key] = 0;
				$list[$key] += $count;
			}
		}

		$max = max( $list );
		foreach ( $list as $message => $count ) {
			if ( $count < intval( $max * self::$mAdaptiveInclusionThreshold ) ) {
				unset( $list[$message] );
			}
		}

		return array_keys( $list );
	}

}
