<?php
/**
 *
 * @addtogroup Cache
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
 *
 */
class MessageCache {
	var $mCache, $mUseCache, $mDisable, $mExpiry;
	var $mMemcKey, $mKeys, $mParserOptions, $mParser;
	var $mExtensionMessages = array();
	var $mInitialised = false;
	var $mDeferred = true;
	var $mAllMessagesLoaded;

	function __construct( &$memCached, $useDB, $expiry, $memcPrefix) {
		wfProfileIn( __METHOD__ );

		$this->mUseCache = !is_null( $memCached );
		$this->mMemc = &$memCached;
		$this->mDisable = !$useDB;
		$this->mExpiry = $expiry;
		$this->mDisableTransform = false;
		$this->mMemcKey = $memcPrefix.':messages';
		$this->mKeys = false; # initialised on demand
		$this->mInitialised = true;
		$this->mParser = null;

		# When we first get asked for a message,
		# then we'll fill up the cache. If we
		# can return a cache hit, this saves
		# some extra milliseconds
		$this->mDeferred = true;

		wfProfileOut( __METHOD__ );
	}

	function getParserOptions() {
		if ( !$this->mParserOptions ) {
			$this->mParserOptions = new ParserOptions;
		}
		return $this->mParserOptions;
	}

	/**
	 * Try to load the cache from a local file
	 */
	function loadFromLocal( $hash ) {
		global $wgLocalMessageCache;

		$this->mCache = false;
		if ( $wgLocalMessageCache === false ) {
			return;
		}

		$filename = "$wgLocalMessageCache/messages-" . wfWikiID();

		wfSuppressWarnings();
		$file = fopen( $filename, 'r' );
		wfRestoreWarnings();
		if ( !$file ) {
			return;
		}

		// Check to see if the file has the hash specified
		$localHash = fread( $file, 32 );
		if ( $hash == $localHash ) {
			// All good, get the rest of it
			$serialized = fread( $file, 10000000 );
			$this->setCache( unserialize( $serialized ) );
		}
		fclose( $file );
	}

	/**
	 * Save the cache to a local file
	 */
	function saveToLocal( $serialized, $hash ) {
		global $wgLocalMessageCache;

		if ( $wgLocalMessageCache === false ) {
			return;
		}

		$filename = "$wgLocalMessageCache/messages-" . wfWikiID();
		$oldUmask = umask( 0 );
		wfMkdirParents( $wgLocalMessageCache, 0777 );
		umask( $oldUmask );

		$file = fopen( $filename, 'w' );
		if ( !$file ) {
			wfDebug( "Unable to open local cache file for writing\n" );
			return;
		}

		fwrite( $file, $hash . $serialized );
		fclose( $file );
		@chmod( $filename, 0666 );
	}

	function loadFromScript( $hash ) {
		global $wgLocalMessageCache;
		if ( $wgLocalMessageCache === false ) {
			return;
		}
		
		$filename = "$wgLocalMessageCache/messages-" . wfWikiID();
		
		wfSuppressWarnings();
		$file = fopen( $filename, 'r' );
		wfRestoreWarnings();
		if ( !$file ) {
			return;
		}
		$localHash=substr(fread($file,40),8);
		fclose($file);
		if ($hash!=$localHash) {
			return;
		}
		require("$wgLocalMessageCache/messages-" . wfWikiID());
		$this->setCache( $this->mCache);
	}
	
	function saveToScript($array, $hash) {
		global $wgLocalMessageCache;
		if ( $wgLocalMessageCache === false ) {
			return;
		}

		$filename = "$wgLocalMessageCache/messages-" . wfWikiID();
		$oldUmask = umask( 0 );
		wfMkdirParents( $wgLocalMessageCache, 0777 );
		umask( $oldUmask );
		$file = fopen( $filename.'.tmp', 'w');
		fwrite($file,"<?php\n//$hash\n\n \$this->mCache = array(");
		
		foreach ($array as $key => $message) {
			fwrite($file, "'". $this->escapeForScript($key).
				"' => '" . $this->escapeForScript($message). 
				"',\n");
		}
		fwrite($file,");\n?>");
		fclose($file);
		rename($filename.'.tmp',$filename);
	}

	function escapeForScript($string) {
		$string = str_replace( '\\', '\\\\', $string );
		$string = str_replace( '\'', '\\\'', $string );
		return $string;
	}

	/**
	 * Set the cache to $cache, if it is valid. Otherwise set the cache to false.
	 */
	function setCache( $cache ) {
		if ( isset( $cache['VERSION'] ) && $cache['VERSION'] == MSG_CACHE_VERSION ) {
			$this->mCache = $cache;
		} else {
			$this->mCache = false;
		}
	}

	/**
	 * Loads messages either from memcached or the database, if not disabled
	 * On error, quietly switches to a fallback mode
	 * Returns false for a reportable error, true otherwise
	 */
	function load() {
		global $wgLocalMessageCache, $wgLocalMessageCacheSerialized;

		if ( $this->mDisable ) {
			static $shownDisabled = false;
			if ( !$shownDisabled ) {
				wfDebug( "MessageCache::load(): disabled\n" );
				$shownDisabled = true;
			}
			return true;
		}
		if ( !$this->mUseCache ) {
			$this->mDeferred = false;
			return true;
		}

		$fname = 'MessageCache::load';
		wfProfileIn( $fname );
		$success = true;

		$this->mCache = false;

		# Try local cache
		wfProfileIn( $fname.'-fromlocal' );
		$hash = $this->mMemc->get( "{$this->mMemcKey}-hash" );
		if ( $hash ) {
			if ($wgLocalMessageCacheSerialized) {
				$this->loadFromLocal( $hash );
			} else {
				$this->loadFromScript( $hash );
			}
			if ( $this->mCache ) {
				wfDebug( "MessageCache::load(): got from local cache\n" );
			}
		}
		wfProfileOut( $fname.'-fromlocal' );

		# Try memcached
		if ( !$this->mCache ) {
			wfProfileIn( $fname.'-fromcache' );
			$this->setCache( $this->mMemc->get( $this->mMemcKey ) );
			if ( $this->mCache ) {
				wfDebug( "MessageCache::load(): got from global cache\n" );
				# Save to local cache
				if ( $wgLocalMessageCache !== false ) {
					$serialized = serialize( $this->mCache );
					if ( !$hash ) {
						$hash = md5( $serialized );
						$this->mMemc->set( "{$this->mMemcKey}-hash", $hash, $this->mExpiry );
					}
					if ($wgLocalMessageCacheSerialized) {
						$this->saveToLocal( $serialized,$hash );
					} else {
						$this->saveToScript( $this->mCache, $hash );
					}
				}
			}
			wfProfileOut( $fname.'-fromcache' );
		}


		# If there's nothing in memcached, load all the messages from the database
		if ( !$this->mCache ) {
			wfDebug( "MessageCache::load(): cache is empty\n" );
			$this->lock();
			# Other threads don't need to load the messages if another thread is doing it.
			$success = $this->mMemc->add( $this->mMemcKey.'-status', "loading", MSG_LOAD_TIMEOUT );
			if ( $success ) {
				wfProfileIn( $fname.'-load' );
				wfDebug( "MessageCache::load(): loading all messages from DB\n" );
				$this->loadFromDB();
				wfProfileOut( $fname.'-load' );

				# Save in memcached
				# Keep trying if it fails, this is kind of important
				wfProfileIn( $fname.'-save' );
				for ($i=0; $i<20 &&
						   !$this->mMemc->set( $this->mMemcKey, $this->mCache, $this->mExpiry );
					 $i++ ) {
					usleep(mt_rand(500000,1500000));
				}

				# Save to local cache
				if ( $wgLocalMessageCache !== false ) {
					$serialized = serialize( $this->mCache );
					$hash = md5( $serialized );
					$this->mMemc->set( "{$this->mMemcKey}-hash", $hash, $this->mExpiry );
					if ($wgLocalMessageCacheSerialized) {
						$this->saveToLocal( $serialized,$hash );
					} else {
						$this->saveToScript( $this->mCache, $hash );
					}
				}

				wfProfileOut( $fname.'-save' );
				if ( $i == 20 ) {
					$this->mMemc->set( $this->mMemcKey.'-status', 'error', 60*5 );
					wfDebug( "MemCached set error in MessageCache: restart memcached server!\n" );
				} else {
					$this->mMemc->delete( $this->mMemcKey.'-status' );
				}
			}
			$this->unlock();
		}

		if ( !is_array( $this->mCache ) ) {
			wfDebug( "MessageCache::load(): unable to load cache, disabled\n" );
			$this->mDisable = true;
			$this->mCache = false;
		}
		wfProfileOut( $fname );
		$this->mDeferred = false;
		return $success;
	}

	/**
	 * Loads all or main part of cacheable messages from the database
	 */
	function loadFromDB() {
		global $wgMaxMsgCacheEntrySize;

		wfProfileIn( __METHOD__ );
		$dbr = wfGetDB( DB_SLAVE );
		$this->mCache = array();

		# Load titles for all oversized pages in the MediaWiki namespace
		$res = $dbr->select( 'page', 'page_title',
			array( 
				'page_len > ' . intval( $wgMaxMsgCacheEntrySize ),
				'page_is_redirect' => 0,
				'page_namespace' => NS_MEDIAWIKI,
			), 
			__METHOD__ );
		while ( $row = $dbr->fetchObject( $res ) ) {
			$this->mCache[$row->page_title] = '!TOO BIG';
		}
		$dbr->freeResult( $res );

		# Load text for the remaining pages
		$res = $dbr->select( array( 'page', 'revision', 'text' ),
			array( 'page_title', 'old_text', 'old_flags' ),
			array( 
				'page_is_redirect' => 0,
				'page_namespace' => NS_MEDIAWIKI,
				'page_latest=rev_id',
				'rev_text_id=old_id',
				'page_len <= ' . intval( $wgMaxMsgCacheEntrySize ) ), 
			__METHOD__ );

		for ( $row = $dbr->fetchObject( $res ); $row; $row = $dbr->fetchObject( $res ) ) {
			$this->mCache[$row->page_title] = ' ' . Revision::getRevisionText( $row );
		}
		$this->mCache['VERSION'] = MSG_CACHE_VERSION;
		$dbr->freeResult( $res );
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Not really needed anymore
	 */
	function getKeys() {
		global $wgContLang;
		if ( !$this->mKeys ) {
			$this->mKeys = array();
			$allMessages = Language::getMessagesFor( 'en' );
			foreach ( $allMessages as $key => $unused ) {
				$title = $wgContLang->ucfirst( $key );
				array_push( $this->mKeys, $title );
			}
		}
		return $this->mKeys;
	}

	function replace( $title, $text ) {
		global $wgLocalMessageCache, $wgLocalMessageCacheSerialized, $parserMemc;
		global $wgMaxMsgCacheEntrySize;

		wfProfileIn( __METHOD__ );
		$this->lock();
		$this->load();
		$parserMemc->delete(wfMemcKey('sidebar'));
		if ( is_array( $this->mCache ) ) {
			if ( $text === false ) {
				# Article was deleted
				unset( $this->mCache[$title] );
				$this->mMemc->delete( "$this->mMemcKey:{$title}" );
			} elseif ( strlen( $text ) > $wgMaxMsgCacheEntrySize ) {
				$this->mCache[$title] = '!TOO BIG';
				$this->mMemc->set( "$this->mMemcKey:{$title}", ' '.$text, $this->mExpiry );
			} else {
				$this->mCache[$title] = ' ' . $text;
				$this->mMemc->delete( "$this->mMemcKey:{$title}" );
			}
			$this->mMemc->set( $this->mMemcKey, $this->mCache, $this->mExpiry );

			# Save to local cache
			if ( $wgLocalMessageCache !== false ) {
				$serialized = serialize( $this->mCache );
				$hash = md5( $serialized );
				$this->mMemc->set( "{$this->mMemcKey}-hash", $hash, $this->mExpiry );
				if ($wgLocalMessageCacheSerialized) {
					$this->saveToLocal( $serialized,$hash );
				} else {
					$this->saveToScript( $this->mCache, $hash );
				}
			}
		}
		$this->unlock();
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Returns success
	 * Represents a write lock on the messages key
	 */
	function lock() {
		if ( !$this->mUseCache ) {
			return true;
		}

		$lockKey = $this->mMemcKey . 'lock';
		for ($i=0; $i < MSG_WAIT_TIMEOUT && !$this->mMemc->add( $lockKey, 1, MSG_LOCK_TIMEOUT ); $i++ ) {
			sleep(1);
		}

		return $i >= MSG_WAIT_TIMEOUT;
	}

	function unlock() {
		if ( !$this->mUseCache ) {
			return;
		}

		$lockKey = $this->mMemcKey . 'lock';
		$this->mMemc->delete( $lockKey );
	}

	/**
	 * Get a message from either the content language or the user language.
	 *
	 * @param string $key The message cache key
	 * @param bool $useDB Get the message from the DB, false to use only the localisation
	 * @param bool $forContent Get the message from the content language rather than the 
	 *                         user language
	 * @param bool $isFullKey Specifies whether $key is a two part key "lang/msg".
	 */
	function get( $key, $useDB = true, $forContent = true, $isFullKey = false ) {
		global $wgContLanguageCode, $wgContLang, $wgLang;
		if( $forContent ) {
			$lang =& $wgContLang;
		} else {
			$lang =& $wgLang;
		}
		$langcode = $lang->getCode();
		# If uninitialised, someone is trying to call this halfway through Setup.php
		if( !$this->mInitialised ) {
			return '&lt;' . htmlspecialchars($key) . '&gt;';
		}
		# If cache initialization was deferred, start it now.
		if( $this->mDeferred && !$this->mDisable && $useDB ) {
			$this->load();
		}

		$message = false;

		# Normalise title-case input
		$lckey = $wgContLang->lcfirst( $key );
		$lckey = str_replace( ' ', '_', $lckey );

		# Try the MediaWiki namespace
		if( !$this->mDisable && $useDB ) {
			$title = $wgContLang->ucfirst( $lckey );
			if(!$isFullKey && ($langcode != $wgContLanguageCode) ) {
				$title .= '/' . $langcode;
			}
			$message = $this->getMsgFromNamespace( $title );
		}
		# Try the extension array
		if( $message === false && isset( $this->mExtensionMessages[$langcode][$lckey] ) ) {
			$message = $this->mExtensionMessages[$langcode][$lckey];
		}
		if ( $message === false && isset( $this->mExtensionMessages['en'][$lckey] ) ) {
			$message = $this->mExtensionMessages['en'][$lckey];
		}

		# Try the array in the language object
		if( $message === false ) {
			#wfDebug( "Trying language object for message $key\n" );
			wfSuppressWarnings();
			$message = $lang->getMessage( $lckey );
			wfRestoreWarnings();
			if ( is_null( $message ) ) {
				$message = false;
			}
		}

		# Try the array of another language
		if( $message === false && strpos( $lckey, '/' ) ) {
			$message = explode( '/', $lckey );
			if ( $message[1] ) {
				wfSuppressWarnings();
				$message = Language::getMessageFor( $message[0], $message[1] );
				wfRestoreWarnings();
				if ( is_null( $message ) ) {
					$message = false;
				}
			} else {
				$message = false;
			}
		}

		# Is this a custom message? Try the default language in the db...
		if( ($message === false || $message === '-' ) &&
			!$this->mDisable && $useDB &&
			!$isFullKey && ($langcode != $wgContLanguageCode) ) {
			$message = $this->getMsgFromNamespace( $wgContLang->ucfirst( $lckey ) );
		}

		# Final fallback
		if( $message === false ) {
			return '&lt;' . htmlspecialchars($key) . '&gt;';
		}

		# Replace brace tags
		$message = $this->transform( $message );
		return $message;
	}

	/**
	 * Get a message from the MediaWiki namespace, with caching. The key must 
	 * first be converted to two-part lang/msg form if necessary.
	 *
	 * @param string $title Message cache key with initial uppercase letter
	 */
	function getMsgFromNamespace( $title ) {
		$message = false;
		$type = false;

		# Try the cache
		if( $this->mUseCache && isset( $this->mCache[$title] ) ) {
			$entry = $this->mCache[$title];
			$type = substr( $entry, 0, 1 );
			if ( $type == ' ' ) {
				return substr( $entry, 1 );
			}
		}

		# Call message hooks, in case they are defined
		wfRunHooks('MessagesPreLoad', array( $title, &$message ) );
		if ( $message !== false ) {
			return $message;
		}

		# If there is no cache entry and no placeholder, it doesn't exist
		if ( $type != '!' && $message === false ) {
			return false;
		}

		$memcKey = $this->mMemcKey . ':' . $title;

		# Try the individual message cache
		if ( $this->mUseCache ) {
			$entry = $this->mMemc->get( $memcKey );
			if ( $entry ) {
				$type = substr( $entry, 0, 1 );

				if ( $type == ' ' ) {
					$message = substr( $entry, 1 );
					$this->mCache[$title] = $entry;
					return $message;
				} elseif ( $entry == '!NONEXISTENT' ) {
					return false;
				} else {
					# Corrupt/obsolete entry, delete it
					$this->mMemc->delete( $memcKey );
				}

			}
		}

		# Try loading it from the DB
		$revision = Revision::newFromTitle( Title::makeTitle( NS_MEDIAWIKI, $title ) );
		if( $revision ) {
			$message = $revision->getText();
			if ($this->mUseCache) {
				$this->mCache[$title] = ' ' . $message;
				$this->mMemc->set( $memcKey, $message, $this->mExpiry );
			}
		} else {
			# Negative caching
			# Use some special text instead of false, because false gets converted to '' somewhere
			$this->mMemc->set( $memcKey, '!NONEXISTENT', $this->mExpiry );
			$this->mCache[$title] = false;
		}

		return $message;
	}

	function transform( $message ) {
		global $wgParser;
		if ( !$this->mParser && isset( $wgParser ) ) {
			# Do some initialisation so that we don't have to do it twice
			$wgParser->firstCallInit();
			# Clone it and store it
			$this->mParser = clone $wgParser;
		}
		if ( !$this->mDisableTransform && $this->mParser ) {
			if( strpos( $message, '{{' ) !== false ) {
				$message = $this->mParser->transformMsg( $message, $this->getParserOptions() );
			}
		}
		return $message;
	}

	function disable() { $this->mDisable = true; }
	function enable() { $this->mDisable = false; }
	function disableTransform() { $this->mDisableTransform = true; }
	function enableTransform() { $this->mDisableTransform = false; }
	function setTransform( $x ) { $this->mDisableTransform = $x; }
	function getTransform() { return $this->mDisableTransform; }

	/**
	 * Add a message to the cache
	 *
	 * @param mixed $key
	 * @param mixed $value
	 * @param string $lang The messages language, English by default
	 */
	function addMessage( $key, $value, $lang = 'en' ) {
		$this->mExtensionMessages[$lang][$key] = $value;
	}

	/**
	 * Add an associative array of message to the cache
	 *
	 * @param array $messages An associative array of key => values to be added
	 * @param string $lang The messages language, English by default
	 */
	function addMessages( $messages, $lang = 'en' ) {
		wfProfileIn( __METHOD__ );
		if ( isset( $this->mExtensionMessages[$lang] ) ) {
			$this->mExtensionMessages[$lang] = $messages + $this->mExtensionMessages[$lang];
		} else {
			$this->mExtensionMessages[$lang] = $messages;
		}
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Add a 2-D array of messages by lang. Useful for extensions.
	 *
	 * @param array $messages The array to be added
	 */
	function addMessagesByLang( $messages ) {
		wfProfileIn( __METHOD__ );
		foreach ( $messages as $key => $value ) {
			$this->addMessages( $value, $key );
		}
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Get the extension messages for a specific language
	 *
	 * @param string $lang The messages language, English by default
	 */
	function getExtensionMessagesFor( $lang = 'en' ) {
		wfProfileIn( __METHOD__ );
		$messages = array();
		if ( isset( $this->mExtensionMessages[$lang] ) ) {
			$messages = $this->mExtensionMessages[$lang];
		}
		if ( $lang != 'en' ) {
			$messages = $messages + $this->mExtensionMessages['en'];
		}
		wfProfileOut( __METHOD__ );
		return $messages;
	}

	/**
	 * Clear all stored messages. Mainly used after a mass rebuild.
	 */
	function clear() {
		global $wgLocalMessageCache;
		if( $this->mUseCache ) {
			# Global cache
			$this->mMemc->delete( $this->mMemcKey );
			# Invalidate all local caches
			$this->mMemc->delete( "{$this->mMemcKey}-hash" );
		}
	}

	function loadAllMessages() {
		global $wgExtensionMessagesFiles;
		if ( $this->mAllMessagesLoaded ) {
			return;
		}
		$this->mAllMessagesLoaded = true;

		# Some extensions will load their messages when you load their class file
		wfLoadAllExtensions();
		# Others will respond to this hook
		wfRunHooks( 'LoadAllMessages' );
		# Some register their messages in $wgExtensionMessagesFiles
		foreach ( $wgExtensionMessagesFiles as $name => $file ) {
			if ( $file ) {
				$this->loadMessagesFile( $file );
				$wgExtensionMessagesFiles[$name] = false;
			}
		}
		# Still others will respond to neither, they are EVIL. We sometimes need to know!
	}

	/**
	 * Load messages from a given file
	 */
	function loadMessagesFile( $filename ) {
		require( $filename );
		$this->addMessagesByLang( $messages );
	}
}

