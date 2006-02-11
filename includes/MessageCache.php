<?php
/**
 *
 * @package MediaWiki
 * @subpackage Cache
 */

/** */
require_once( 'Revision.php' );

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

	function initialise( &$memCached, $useDB, $expiry, $memcPrefix) {
		$fname = 'MessageCache::initialise';
		wfProfileIn( $fname );

		$this->mUseCache = !is_null( $memCached );
		$this->mMemc = &$memCached;
		$this->mDisable = !$useDB;
		$this->mExpiry = $expiry;
		$this->mDisableTransform = false;
		$this->mMemcKey = $memcPrefix.':messages';
		$this->mKeys = false; # initialised on demand
		$this->mInitialised = true;

		wfProfileIn( $fname.'-parseropt' );
		$this->mParserOptions = ParserOptions::newFromUser( $u=NULL );
		wfProfileOut( $fname.'-parseropt' );
		wfProfileIn( $fname.'-parser' );
		$this->mParser = new Parser;
		wfProfileOut( $fname.'-parser' );

		# When we first get asked for a message,
		# then we'll fill up the cache. If we
		# can return a cache hit, this saves
		# some extra milliseconds
		$this->mDeferred = true;

		wfProfileOut( $fname );
	}

	/**
	 * Try to load the cache from a local file
	 */
	function loadFromLocal( $hash ) {
		global $wgLocalMessageCache, $wgDBname;

		$this->mCache = false;
		if ( $wgLocalMessageCache === false ) {
			return;
		}

		$filename = "$wgLocalMessageCache/messages-$wgDBname";

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
			$serialized = fread( $file, 1000000 );
			$this->mCache = unserialize( $serialized );
		}
		fclose( $file );
	}

	/**
	 * Save the cache to a local file
	 */
	function saveToLocal( $serialized, $hash ) {
		global $wgLocalMessageCache, $wgDBname;

		if ( $wgLocalMessageCache === false ) {
			return;
		}

		$filename = "$wgLocalMessageCache/messages-$wgDBname";
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


	/**
	 * Loads messages either from memcached or the database, if not disabled
	 * On error, quietly switches to a fallback mode
	 * Returns false for a reportable error, true otherwise
	 */
	function load() {
		global $wgLocalMessageCache;

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
				$this->loadFromLocal( $hash );
			}
			wfProfileOut( $fname.'-fromlocal' );

			# Try memcached
			if ( !$this->mCache ) {
				wfProfileIn( $fname.'-fromcache' );
				$this->mCache = $this->mMemc->get( $this->mMemcKey );

				# Save to local cache
				if ( $wgLocalMessageCache !== false ) {
					$serialized = serialize( $this->mCache );
					if ( !$hash ) {
						$hash = md5( $serialized );
						$this->mMemc->set( "{$this->mMemcKey}-hash", $hash, $this->mExpiry );
					}
					$this->saveToLocal( $serialized, $hash );
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
						$this->saveToLocal( $serialized, $hash );
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
		global $wgAllMessagesEn, $wgLang;

		$fname = 'MessageCache::loadFromDB';
		$dbr =& wfGetDB( DB_SLAVE );
		if ( !$dbr ) {
			wfDebugDieBacktrace( 'Invalid database object' );
		}
		$conditions = array( 'page_is_redirect' => 0,
					'page_namespace' => NS_MEDIAWIKI);
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
		foreach ( $wgAllMessagesEn as $key => $value ) {
			$uckey = $wgLang->ucfirst( $key );
			if ( !array_key_exists( $uckey, $this->mCache ) ) {
				$this->mCache[$uckey] = false;
			}
		}
		foreach ( $this->mExtensionMessages as $key => $value ) {
			$uckey = $wgLang->ucfirst( $key );
			if ( !array_key_exists( $uckey, $this->mCache ) ) {
				$this->mCache[$uckey] = false;
			}
		}

		$dbr->freeResult( $res );
	}

	/**
	 * Not really needed anymore
	 */
	function getKeys() {
		global $wgAllMessagesEn, $wgContLang;
		if ( !$this->mKeys ) {
			$this->mKeys = array();
			foreach ( $wgAllMessagesEn as $key => $value ) {
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
		global $wgLocalMessageCache, $parserMemc, $wgDBname;

		$this->lock();
		$this->load();
		$parserMemc->delete("$wgDBname:sidebar");
		if ( is_array( $this->mCache ) ) {
			$this->mCache[$title] = $text;
			$this->mMemc->set( $this->mMemcKey, $this->mCache, $this->mExpiry );

			# Save to local cache
			if ( $wgLocalMessageCache !== false ) {
				$serialized = serialize( $this->mCache );
				$hash = md5( $serialized );
				$this->mMemc->set( "{$this->mMemcKey}-hash", $hash, $this->mExpiry );
				$this->saveToLocal( $serialized, $hash );
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

	function get( $key, $useDB, $forcontent=true, $isfullkey = false ) {
		global $wgContLanguageCode;
		if( $forcontent ) {
			global $wgContLang;
			$lang =& $wgContLang;
			$langcode = $wgContLanguageCode;
		} else {
			global $wgLang, $wgLanguageCode;
			$lang =& $wgLang;
			$langcode = $wgLanguageCode;
		}
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
			$title = $lang->ucfirst( $key );
			if(!$isfullkey && ($langcode != $wgContLanguageCode) ) {
				$title .= '/' . $langcode;
			}
			$message = $this->getFromCache( $title );
		}
		# Try the extension array
		if( $message === false && array_key_exists( $key, $this->mExtensionMessages ) ) {
			$message = $this->mExtensionMessages[$key];
		}

		# Try the array in the language object
		if( $message === false ) {
			wfSuppressWarnings();
			$message = $lang->getMessage( $key );
			wfRestoreWarnings();
			if ( is_null( $message ) ) {
				$message = false;
			}
		}

		# Try the English array
		if( $message === false && $langcode != 'en' ) {
			wfSuppressWarnings();
			$message = Language::getMessage( $key );
			wfRestoreWarnings();
			if ( is_null( $message ) ) {
				$message = false;
			}
		}

		# Is this a custom message? Try the default language in the db...
		if( $message === false &&
			!$this->mDisable && $useDB &&
			!$isfullkey && ($langcode != $wgContLanguageCode) ) {
			$message = $this->getFromCache( $lang->ucfirst( $key ) );
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
		}

		return $message;
	}

	function transform( $message ) {
		if( !$this->mDisableTransform ) {
			if( strpos( $message, '{{' ) !== false ) {
				$message = $this->mParser->transformMsg( $message, $this->mParserOptions );
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
	 */
	function addMessage( $key, $value ) {
		$this->mExtensionMessages[$key] = $value;
	}

	/**
	 * Add an associative array of message to the cache
	 *
	 * @param array $messages An associative array of key => values to be added
	 */
	function addMessages( $messages ) {
		foreach ( $messages as $key => $value ) {
			$this->addMessage( $key, $value );
		}
	}

	/**
	 * Clear all stored messages. Mainly used after a mass rebuild.
	 */
	function clear() {
		if( $this->mUseCache ) {
			$this->mMemc->delete( $this->mMemcKey );
		}
	}
}
?>
