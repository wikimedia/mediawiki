<?php

# Message cache
# Performs various useful MediaWiki namespace-related functions

define( "MSG_LOAD_TIMEOUT", 60);
define( "MSG_LOCK_TIMEOUT", 10);
define( "MSG_WAIT_TIMEOUT", 10);

class MessageCache
{
	var $mCache, $mUseCache, $mDisable, $mExpiry;
	var $mMemcKey, $mKeys, $mParserOptions, $mParser;
	var $mExtensionMessages, $mSecondaryDB;

	var $mInitialised = false;

	function initialise( &$memCached, $useDB, $expiry, $memcPrefix, $secondaryDB = false) {
		$fname = "MessageCache::initialise";
		wfProfileIn( $fname );
		$this->mUseCache = !is_null( $memCached );
		$this->mMemc = &$memCached;
		$this->mDisable = !$useDB;
		$this->mExpiry = $expiry;
		$this->mDisableTransform = false;
		$this->mMemcKey = "$memcPrefix:messages";
		$this->mKeys = false; # initialised on demand
		$this->mInitialised = true;
		wfProfileIn( "$fname-parseropt" );
		$this->mParserOptions = ParserOptions::newFromUser( $u=NULL );
		wfProfileIn( "$fname-parseropt" );
		wfProfileOut( "$fname-parser" );
		$this->mParser = new Parser;
		wfProfileOut( "$fname-parser" );
		$this->mSecondaryDB = $secondaryDB;
		
		$this->load();
		wfProfileOut( $fname );
	}

	# Loads messages either from memcached or the database, if not disabled
	# On error, quietly switches to a fallback mode
	# Returns false for a reportable error, true otherwise
	function load() {
		global $wgAllMessagesEn;
		
		if ( $this->mDisable ) {
			return true;
		}
		$fname = "MessageCache::load";
		wfProfileIn( $fname );
		$success = true;
		
		if ( $this->mUseCache ) {
			wfProfileIn( "$fname-fromcache" );
			$this->mCache = $this->mMemc->get( $this->mMemcKey );
			wfProfileOut( "$fname-fromcache" );
			
			# If there's nothing in memcached, load all the messages from the database
			if ( !$this->mCache ) {
				$this->lock();
				# Other threads don't need to load the messages if another thread is doing it.
				if ( $this->mMemc->set( $this->mMemcKey, "loading", MSG_LOAD_TIMEOUT ) ) {
					wfProfileIn( "$fname-load" );
					$this->loadFromDB();
					wfProfileOut( "$fname-load" );
					# Save in memcached
					# Keep trying if it fails, this is kind of important
					wfProfileIn( "$fname-save" );
					for ( $i=0; $i<20 && !$this->mMemc->set( $this->mMemcKey, $this->mCache, $this->mExpiry ); $i++ ) {
						usleep(mt_rand(500000,1500000));
					}
					wfProfileOut( "$fname-save" );
					if ( $i == 20 ) {
						$this->mMemc->set( $this->mMemcKey, "error", 3600 );
						wfDebug( "MemCached set error in MessageCache: restart memcached server!\n" );
					}
					$this->unlock();
				}
			}
			
			if ( !is_array( $this->mCache ) ) {
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
		return $success;
	}

	# Loads all cacheable messages from the database
	function loadFromDB()
	{
		global $wgLoadBalancer;
		$fname = "MessageCache::loadFromDB";
		$wgLoadBalancer->force(-1);
		$this->mCache = array();
		if ( $this->mSecondaryDB ) {
			# Load from fallback
			$sql = "SELECT cur_title,cur_text FROM {$this->mSecondaryDB}.cur WHERE cur_is_redirect=0 AND cur_namespace=" . NS_MEDIAWIKI;
			$res = wfQuery( $sql, DB_READ, $fname );
			$this->mCache = array();
			for ( $row = wfFetchObject( $res ); $row; $row = wfFetchObject( $res ) ) {
				$this->mCache[$row->cur_title] = $row->cur_text;
			}
		}
		$sql = "SELECT cur_title,cur_text FROM cur WHERE cur_is_redirect=0 AND cur_namespace=" . NS_MEDIAWIKI;
		$res = wfQuery( $sql, DB_READ, $fname );
		for ( $row = wfFetchObject( $res ); $row; $row = wfFetchObject( $res ) ) {
			$this->mCache[$row->cur_title] = $row->cur_text;
		}

		$wgLoadBalancer->force(0);
		wfFreeResult( $res );
	}
	
	# Not really needed anymore
	function getKeys() {
		global $wgAllMessagesEn, $wgLang;
		if ( !$this->mKeys ) {
			$this->mKeys = array();
			foreach ( $wgAllMessagesEn as $key => $value ) {
				array_push( $this->mKeys, $wgLang->ucfirst( $key ) );
			}
		}
		return $this->mKeys;
	}
	
	# Obsolete
	function isCacheable( $key ) {
		return true;
		/*
		global $wgAllMessagesEn, $wgLang;
		return array_key_exists( $wgLang->lcfirst( $key ), $wgAllMessagesEn ) || 
			array_key_exists( $key, $wgAllMessagesEn );
		*/
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

	# Returns success
	# Represents a write lock on the messages key
	function lock() {
		if ( !$this->mUseCache ) {
			return true;
		}

		$lockKey = $this->mMemcKey . "lock";
		for ($i=0; $i < MSG_WAIT_TIMEOUT && !$this->mMemc->add( $lockKey, 1, MSG_LOCK_TIMEOUT ); $i++ ) {
			sleep(1);
		}
		
		return $i >= MSG_WAIT_TIMEOUT;
	}
	
	function unlock() {
		if ( !$this->mUseCache ) {
			return;
		}

		$lockKey = $this->mMemcKey . "lock";
		$this->mMemc->delete( $lockKey );
	}
	
	function get( $key, $useDB ) {
		global $wgLang, $wgLanguageCode;
		
		# If uninitialised, someone is trying to call this halfway through Setup.php
		if ( !$this->mInitialised ) {
			return "&lt;$key&gt;";
		}
		
		$message = false;
		if ( !$this->mDisable && $useDB ) {
			$title = $wgLang->ucfirst( $key );
			

			# Try the cache
			if ( $this->mUseCache && $this->mCache && array_key_exists( $title, $this->mCache ) ) {
				$message = $this->mCache[$title];
			}
			
			# If it wasn't in the cache, load each message from the DB individually
			if ( !$message ) {
				$result = wfGetArray( "cur", array("cur_text"), 
				  array( "cur_namespace" => NS_MEDIAWIKI, "cur_title" => $title ),
				  "MessageCache::get" );
				if ( $result ) {
					$message = $result->cur_text;
				}
			}
		}
		# Try the extension array
		if ( !$message ) {
			$message = @$this->mExtensionMessages[$key];
		}

		# Try the array in $wgLang
		if ( !$message ) {
			$message = $wgLang->getMessage( $key );
		} 

		# Try the English array
		if ( !$message && $wgLanguageCode != "en" ) {
			$message = Language::getMessage( $key );
		}
		
		# Final fallback
		if ( !$message ) {
			$message = "&lt;$key&gt;";
		}
		
		# Replace brace tags
		$message = $this->transform( $message );
		return $message;
	}

	function transform( $message ) {
		if( !$this->mDisableTransform ) { 
			if ( strstr( $message, "{{" ) !== false ) {
				$message = $this->mParser->transformMsg( $message, $this->mParserOptions );
			}
		}
		return $message;
	}
	
	function disable() { $this->mDisable = true; }
	function enable() { $this->mDisable = false; }
	function disableTransform() { $this->mDisableTransform = true; }

	function addMessage( $key, $value ) {
		$this->mExtensionMessages[$key] = $value;
	}

	function addMessages( $messages ) {
		foreach ( $messages as $key => $value ) {
			$this->mExtensionMessages[$key] = $value;
		}
	}
	
	# Clear all stored messages. Mainly used after a mass rebuild.
	function clear() {
		if( $this->mUseCache ) {
			$this->mMemc->delete( $this->mMemcKey );
		}
	}
}
?>
