<?php
/**
 *
 * @package MediaWiki
 * @subpackage Cache
 */

/**
 *
 */
define( 'MSG_LOAD_TIMEOUT', 60);
define( 'MSG_LOCK_TIMEOUT', 10);
define( 'MSG_WAIT_TIMEOUT', 10);

/**
 * Message cache
 * Performs various useful MediaWiki namespace-related functions
 *
 * @package MediaWiki
 */
class MessageCache {
	var $mCache, $mUseCache, $mDisable, $mExpiry;
	var $mMemcKey, $mKeys, $mParserOptions, $mParser;
	var $mExtensionMessages = array();
	var $mInitialised = false;
	var $mDeferred = true;

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
			$this->mCache = unserialize( $serialized );
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
		$fname = 'MessageCache::load';
		wfProfileIn( $fname );
		$success = true;

		if ( $this->mUseCache ) {
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
				$this->mCache = $this->mMemc->get( $this->mMemcKey );
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
				wfDebug( "MessageCache::load(): loading all messages\n" );
				$this->lock();
				# Other threads don't need to load the messages if another thread is doing it.
				$success = $this->mMemc->add( $this->mMemcKey.'-status', "loading", MSG_LOAD_TIMEOUT );
				if ( $success ) {
					wfProfileIn( $fname.'-load' );
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
					}
				}
				$this->unlock();
			}

			if ( !is_array( $this->mCache ) ) {
				wfDebug( "MessageCache::load(): individual message mode\n" );
				# If it is 'loading' or 'error', switch to individual message mode, otherwise disable
				# Causing too much DB load, disabling -- TS
				$this->mDisable = true;
				/*
				if ( $this->mCache == "loading" ) {
					$this->mUseCache = false;
				} elseif ( $this->mCache == "error" ) {
					$this->mUseCache = false;
					$success = false;
				} else {
					$this->mDisable = true;
					$success = false;
				}*/
				$this->mCache = false;
			}
		}
		wfProfileOut( $fname );
		$this->mDeferred = false;
		return $success;
	}

	/**
	 * Loads all or main part of cacheable messages from the database
	 */
	function loadFromDB() {
		global $wgLang;

		$fname = 'MessageCache::loadFromDB';
		$dbr =& wfGetDB( DB_SLAVE );
		if ( !$dbr ) {
			throw new MWException( 'Invalid database object' );
		}
		$res = $dbr->select( array( 'page', 'revision', 'text' ),
			array( 'page_title', 'old_text', 'old_flags' ),
			'page_is_redirect=0 AND page_namespace='.NS_MEDIAWIKI.' AND page_latest=rev_id AND rev_text_id=old_id',
			$fname
		);

		$this->mCache = array();
		for ( $row = $dbr->fetchObject( $res ); $row; $row = $dbr->fetchObject( $res ) ) {
			$this->mCache[$row->page_title] = Revision::getRevisionText( $row );
		}

		# Negative caching
		# Go through the language array and the extension array and make a note of
		# any keys missing from the cache
		$allMessages = Language::getMessagesFor( 'en' );
		foreach ( array_keys($allMessages) as $key ) {
			$uckey = $wgLang->ucfirst( $key );
			if ( !array_key_exists( $uckey, $this->mCache ) ) {
				$this->mCache[$uckey] = false;
			}
		}

		# Make sure all extension messages are available
		MessageCache::loadAllMessages();

		# Add them to the cache
		foreach ( array_keys($this->mExtensionMessages) as $key ) {
			$uckey = $wgLang->ucfirst( $key );
			if ( !array_key_exists( $uckey, $this->mCache ) &&
			 ( isset( $this->mExtensionMessages[$key][$wgLang->getCode()] ) || isset( $this->mExtensionMessages[$key]['en'] ) )  ) {
				$this->mCache[$uckey] = false;
			}
		}

		$dbr->freeResult( $res );
	}

	/**
	 * Not really needed anymore
	 */
	function getKeys() {
		global $wgContLang;
		if ( !$this->mKeys ) {
			$this->mKeys = array();
			$allMessages = Language::getMessagesFor( 'en' );
			foreach ( array_keys($allMessages) as $key ) {
				$title = $wgContLang->ucfirst( $key );
				array_push( $this->mKeys, $title );
			}
		}
		return $this->mKeys;
	}

	/**
	 * @deprecated
	 */
	function isCacheable( $key ) {
		return true;
	}

	function replace( $title, $text ) {
		global $wgLocalMessageCache, $wgLocalMessageCacheSerialized, $parserMemc;

		$this->lock();
		$this->load();
		$parserMemc->delete(wfMemcKey('sidebar'));
		if ( is_array( $this->mCache ) ) {
			$this->mCache[$title] = $text;
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

	function get( $key, $useDB = true, $forcontent = true, $isfullkey = false ) {
		global $wgContLanguageCode, $wgContLang, $wgLang;
		if( $forcontent ) {
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
		if( !$this->mDisable && $useDB ) {
			$title = $wgContLang->ucfirst( $key );
			if(!$isfullkey && ($langcode != $wgContLanguageCode) ) {
				$title .= '/' . $langcode;
			}
			$message = $this->getFromCache( $title );
		}
		# Try the extension array
		if( $message === false && array_key_exists( $key, $this->mExtensionMessages ) ) {
			if ( isset( $this->mExtensionMessages[$key][$langcode] ) ) {
				$message = $this->mExtensionMessages[$key][$langcode];
			} elseif ( isset( $this->mExtensionMessages[$key]['en'] ) ) {
				$message = $this->mExtensionMessages[$key]['en'];
			}
		}

		# Try the array in the language object
		if( $message === false ) {
			#wfDebug( "Trying language object for message $key\n" );
			wfSuppressWarnings();
			$message = $lang->getMessage( $key );
			wfRestoreWarnings();
			if ( is_null( $message ) ) {
				$message = false;
			}
		}

		# Try the array of another language
		if( $message === false && strpos( $key, '/' ) ) {
			$message = explode( '/', $key );
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
			!$isfullkey && ($langcode != $wgContLanguageCode) ) {
			$message = $this->getFromCache( $wgContLang->ucfirst( $key ) );
		}

		# Final fallback
		if( $message === false ) {
			return '&lt;' . htmlspecialchars($key) . '&gt;';
		}

		# Replace brace tags
		$message = $this->transform( $message );
		return $message;
	}

	function getFromCache( $title ) {
		$message = false;

		# Try the cache
		if( $this->mUseCache && is_array( $this->mCache ) && array_key_exists( $title, $this->mCache ) ) {
			return $this->mCache[$title];
		}

		# Try individual message cache
		if ( $this->mUseCache ) {
			$message = $this->mMemc->get( $this->mMemcKey . ':' . $title );
			if ( $message == '###NONEXISTENT###' ) {
				$this->mCache[$title] = false;
				return false;
			} elseif( !is_null( $message ) ) {
				$this->mCache[$title] = $message;
				return $message;
			} else {
				$message = false;
			}
		}

		# Call message Hooks, in case they are defined
		wfRunHooks('MessagesPreLoad',array($title,&$message));

		# If it wasn't in the cache, load each message from the DB individually
		$revision = Revision::newFromTitle( Title::makeTitle( NS_MEDIAWIKI, $title ) );
		if( $revision ) {
			$message = $revision->getText();
			if ($this->mUseCache) {
				$this->mCache[$title]=$message;
				/* individual messages may be often
				   recached until proper purge code exists
				*/
				$this->mMemc->set( $this->mMemcKey . ':' . $title, $message, 300 );
			}
		} else {
			# Negative caching
			# Use some special text instead of false, because false gets converted to '' somewhere
			$this->mMemc->set( $this->mMemcKey . ':' . $title, '###NONEXISTENT###', $this->mExpiry );
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
		$this->mExtensionMessages[$key][$lang] = $value;
	}

	/**
	 * Add an associative array of message to the cache
	 *
	 * @param array $messages An associative array of key => values to be added
	 * @param string $lang The messages language, English by default
	 */
	function addMessages( $messages, $lang = 'en' ) {
		wfProfileIn( __METHOD__ );
		foreach ( $messages as $key => $value ) {
			$this->addMessage( $key, $value, $lang );
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
		foreach( $this->mExtensionMessages as $key => $message ) {
			if ( isset( $message[$lang] ) ) {
				$messages[$key] = $message[$lang];
			} elseif ( isset( $message['en'] ) ) {
				$messages[$key] = $message['en'];
			}
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

	static function loadAllMessages() {
		# Some extensions will load their messages when you load their class file
		wfLoadAllExtensions();
		# Others will respond to this hook
		wfRunHooks( 'LoadAllMessages' );
		# Still others will respond to neither, they are EVIL. We sometimes need to know!
	}
}
?>
