<?php

# Message cache
# Performs various useful MediaWiki namespace-related functions

define( "MSG_LOAD_TIMEOUT", 60);
define( "MSG_LOCK_TIMEOUT", 10);
define( "MSG_WAIT_TIMEOUT", 10);

class MessageCache
{
	var $mCache, $mUseCache, $mDisable, $mExpiry;
	var $mMemcKey, $mKeys;
	
	var $mInitialised = false;

	function initialise( $useMemCached, $useDB, $expiry, $memcPrefix ) {
		$this->mUseCache = $useMemCached;
		$this->mDisable = !$useDB;
		$this->mExpiry = $expiry;
		$this->mMemcKey = "$memcPrefix:messages";
		$this->mKeys = false; # initialised on demand
		$this->mInitialised = true;

		$this->load();
	}

	# Loads messages either from memcached or the database, if not disabled
	# On error, quietly switches to a fallback mode
	# Returns false for a reportable error, true otherwise
	function load() {
		global $wgAllMessagesEn, $wgMemc;
		
		if ( $this->mDisable ) {
			return true;
		}
		
		$success = true;
		
		if ( $this->mUseCache ) {
			$this->mCache = $wgMemc->get( $this->mMemcKey );
			
			# If there's nothing in memcached, load all the messages from the database
			if ( !$this->mCache ) {
				$this->lock();
				# Other threads don't need to load the messages if another thread is doing it.
				$wgMemc->set( $this->mMemcKey, "loading", MSG_LOAD_TIMEOUT );
				$this->loadFromDB();
				# Save in memcached
				if ( !$wgMemc->set( $this->mMemcKey, $this->mCache, $this->mExpiry ) ) {
					# Hack for slabs reassignment problem
					$wgMemc->set( $this->mMemcKey, "error" );
					wfDebug( "MemCached set error in MessageCache: restart memcached server!\n" );
				}
				$this->unlock();
			}
			
			if ( !is_array( $this->mCache ) ) {
				# If it is 'loading' or 'error', switch to individual message mode, otherwise disable
				if ( $this->mCache == "loading" ) {
					$this->mUseCache = false;
				} elseif ( $this->mCache == "error" ) {
					$this->mUseCache = false;
					$success = false;
				} else {
					$this->mDisable = true;
					$success = false;
				}
				$this->mCache = false;
			}
		}
		return $success;
	}

	# Loads all cacheable messages from the database
	function loadFromDB()
	{
		$fname = "MessageCache::loadFromDB";
		$sql = "SELECT cur_title,cur_text FROM cur WHERE cur_namespace=" . NS_MEDIAWIKI;
		$sql .= " AND cur_title IN ('";
		$sql .= implode( "','", $this->getKeys() ) . "')";
		wfDebug( "$sql\n" );

		$res = wfQuery( $sql, DB_READ, $fname );
		
		$this->mCache = array();
		for ( $row = wfFetchObject( $res ); $row; $row = wfFetchObject( $res ) ) {
			$this->mCache[$row->cur_title] = $row->cur_text;
		}

		wfDebug( var_export( $this->mCache, true ) );

		wfFreeResult( $res );
	}
	
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
	
	function isCacheable( $key ) {
		global $wgAllMessagesEn, $wgLang;
		return array_key_exists( $wgLang->lcfirst( $key ), $wgAllMessagesEn ) || 
			array_key_exists( $key, $wgAllMessagesEn );
	}

	function replace( $title, $text ) {
		global $wgMemc;
		if ( $this->isCacheable( $title ) ) {
			$this->lock();
			$this->load();
			if ( is_array( $this->mCache ) ) {
				$this->mCache[$title] = $text;
				$wgMemc->set( $this->mMemcKey, $this->mCache, $this->mExpiry );
			}
			$this->unlock();
		}
	}

	# Returns success
	# Represents a write lock on the messages key
	function lock() {
		global $wgMemc;

		if ( !$this->mUseCache ) {
			return true;
		}

		$lockKey = $this->mMemcKey . "lock";
		for ($i=0; $i < MSG_WAIT_TIMEOUT && !$wgMemc->add( $lockKey, 1, MSG_LOCK_TIMEOUT ); $i++ ) {
			sleep(1);
		}
		
		return $i >= MSG_WAIT_TIMEOUT;
	}
	
	function unlock() {
		global $wgMemc;
		
		if ( !$this->mUseCache ) {
			return;
		}

		$lockKey = $this->mMemcKey . "lock";
		$wgMemc->delete( $lockKey );
	}
	
	function get( $key, $useDB ) {
		global $wgLang, $wgLanguageCode;
		
		$fname = "MessageCache::get";
		
		# If uninitialised, someone is trying to call this halfway through Setup.php
		if ( !$this->mInitialised ) {
			return "&lt;$key&gt;";
		}
		
		if ( $this->mDisable ) {
			return $wgLang->getMessage( $key );
		}
		$title = $wgLang->ucfirst( $key );
		
		$message = false;

		# Try the cache
		if ( $this->mUseCache && $this->mCache ) {
			$message = $this->mCache[$title];
		}
		
		# If it wasn't in the cache, load each message from the DB individually
		if ( !$message && $useDB) {
			$sql = "SELECT cur_text FROM cur WHERE cur_namespace=" . NS_MEDIAWIKI . 
				" AND cur_title='$title'";
			$res = wfQuery( $sql, DB_READ, $fname );

			if ( wfNumRows( $res ) ) {
				$obj = wfFetchObject( $res );
				$message = $obj->cur_text;
				wfFreeResult( $res );
			}
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
		return $message;
	}

	function disable() { $this->mDisable = true; }
	function enable() { $this->mDisable = false; }

}
?>
