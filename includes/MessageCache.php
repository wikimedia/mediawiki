<?php
/**
 *
 * @package MediaWiki
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
class MessageCache
{
	var $mCache, $mUseCache, $mDisable, $mExpiry;
	var $mMemcKey, $mKeys, $mParserOptions, $mParser;
	var $mExtensionMessages;
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
	 * Loads messages either from memcached or the database, if not disabled
	 * On error, quietly switches to a fallback mode
	 * Returns false for a reportable error, true otherwise
	 */
	function load() {
		global $wgAllMessagesEn;

		if ( $this->mDisable ) {
			wfDebug( "MessageCache::load(): disabled\n" );
			return true;
		}
		$fname = 'MessageCache::load';
		wfProfileIn( $fname );
		$success = true;

		if ( $this->mUseCache ) {
			wfProfileIn( $fname.'-fromcache' );
			$this->mCache = $this->mMemc->get( $this->mMemcKey );
			wfProfileOut( $fname.'-fromcache' );

			# If there's nothing in memcached, load all the messages from the database
			if ( !$this->mCache ) {
				wfDebug( "MessageCache::load(): loading all messages\n" );
				$this->lock();
				# Other threads don't need to load the messages if another thread is doing it.
				$success = $this->mMemc->add( $this->mMemcKey, "loading", MSG_LOAD_TIMEOUT );
				if ( $success ) {
					wfProfileIn( $fname.'-load' );
					$this->loadFromDB();
					wfProfileOut( $fname.'-load' );
					# Save in memcached
					# Keep trying if it fails, this is kind of important
					wfProfileIn( $fname.'-save' );
					for ( $i=0; $i<20 && !$this->mMemc->set( $this->mMemcKey, $this->mCache, $this->mExpiry ); $i++ ) {
						usleep(mt_rand(500000,1500000));
					}
					wfProfileOut( $fname.'-save' );
					if ( $i == 20 ) {
						$this->mMemc->set( $this->mMemcKey, 'error', 60*5 );
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
		global $wgPartialMessageCache;
		$fname = 'MessageCache::loadFromDB';
		$dbr =& wfGetDB( DB_SLAVE );
		$conditions = array( 'page_is_redirect' => 0, 
					'page_namespace' => NS_MEDIAWIKI);
		if ($wgPartialMessageCache) {
			wfDebugDieBacktrace( "Confused about how this works." );
			if (is_array($wgPartialMessageCache)) {
				$conditions['page_title']=$wgPartialMessageCache;
			} else {
				require_once("MessageCacheHints.php");
				$conditions['page_title']=MessageCacheHints::get();
			}
		}
		$res = $dbr->select( array( 'page', 'text' ),
			array( 'page_title', 'old_text', 'old_flags' ),
			'page_is_redirect=0 AND page_namespace = '.NS_MEDIAWIKI.' AND page_latest = old_id',
			$fname
		);

		$this->mCache = array();
		for ( $row = $dbr->fetchObject( $res ); $row; $row = $dbr->fetchObject( $res ) ) {
			$this->mCache[$row->page_title] = Revision::getRevisionText( $row );
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
	 * Obsolete
	 */
	function isCacheable( $key ) {
		return true;
	}

	function replace( $title, $text ) {
		$this->lock();
		$this->load();
		if ( is_array( $this->mCache ) ) {
			$this->mCache[$title] = $text;
			$this->mMemc->set( $this->mMemcKey, $this->mCache, $this->mExpiry );
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
			return "&lt;$key&gt;";
		}
		# If cache initialization was deferred, start it now.
		if( $this->mDeferred ) {
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
		if( !$message ) {
			$message = @$this->mExtensionMessages[$key];
		}

		# Try the array in the language object
		if( !$message ) {
			wfSuppressWarnings();
			$message = $lang->getMessage( $key );
			wfRestoreWarnings();
		}

		# Try the English array
		if( !$message && $langcode != 'en' ) {
			wfSuppressWarnings();
			$message = Language::getMessage( $key );
			wfRestoreWarnings();
		}

		# Is this a custom message? Try the default language in the db...
		if( !$message &&
			!$this->mDisable && $useDB &&
			!$isfullkey && ($langcode != $wgContLanguageCode) ) {
			$message = $this->getFromCache( $lang->ucfirst( $key ) );
		}
		
		# Final fallback
		if( !$message ) {
			$message = "&lt;$key&gt;";
		}

		# Replace brace tags
		$message = $this->transform( $message );
		return $message;
	}
	
	function getFromCache( $title ) {
		$message = false;
		
		# Try the cache
		if( $this->mUseCache && is_array( $this->mCache ) && array_key_exists( $title, $this->mCache ) ) {
			$message = $this->mCache[$title];
		}

		if ( !$message && $this->mUseCache ) {
			$message = $this->mMemc->get( $this->mMemcKey . ':' . $title );
			if( $message ) {
				$this->mCache[$title] = $message;
			}
		}

		# If it wasn't in the cache, load each message from the DB individually
		if ( !$message ) {
			$dbr =& wfGetDB( DB_SLAVE );
			$result = $dbr->selectRow( array( 'page', 'text' ),
			  array( 'old_flags', 'old_text' ),
			  'page_namespace=' . NS_MEDIAWIKI .
			  ' AND page_title=' . $dbr->addQuotes( $title ) .
			  ' AND page_latest=old_id',
			  'MessageCache::get' );
			if ( $result ) {
				$message = Revision::getRevisionText( $result );
				if ($this->mUseCache) {
					$this->mCache[$title]=$message;
					/* individual messages may be often 
					   recached until proper purge code exists 
					*/
					$this->mMemc->set( $this->mMemcKey . ':' . $title, $message, 300 );
				}
			}
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

	function addMessage( $key, $value ) {
		$this->mExtensionMessages[$key] = $value;
	}

	function addMessages( $messages ) {
		foreach ( $messages as $key => $value ) {
			$this->mExtensionMessages[$key] = $value;
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
