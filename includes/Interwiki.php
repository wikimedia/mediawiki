<?php
/**
 * @file
 * Interwiki table entry
 */

/**
 * The interwiki class
 * All information is loaded on creation when called by Interwiki::fetch( $prefix ).
 * All work is done on slave, because this should *never* change (except during schema updates etc, which arent wiki-related)
 */
class Interwiki {

	// Cache - removes oldest entry when it hits limit
	protected static $smCache = array();
	const CACHE_LIMIT = 100; // 0 means unlimited, any other value is max number of entries.

	protected $mPrefix, $mURL, $mLocal, $mTrans;

	function __construct( $prefix = null, $url = '', $local = 0, $trans = 0 )
	{
		$this->mPrefix = $prefix;
		$this->mURL = $url;
		$this->mLocal = $local;
		$this->mTrans = $trans;
	}

	/**
	 * Check whether an interwiki prefix exists
	 * 
	 * @return bool Whether it exists
	 * @param $prefix string Interwiki prefix to use
	 */
	static public function isValidInterwiki( $prefix ){
		$result = self::fetch( $prefix );
		return (bool)$result;
	}

	/**
	 * Fetch an Interwiki object
	 * 
	 * @return Interwiki Object, or null if not valid
	 * @param $prefix string Interwiki prefix to use
	 */
	static public function fetch( $prefix ) {
		global $wgContLang;
		if( $prefix == '' ) {
			return null;
		}
		$prefix = $wgContLang->lc( $prefix );
		if( isset( self::$smCache[$prefix] ) ){
			return self::$smCache[$prefix];
		}
		global $wgInterwikiCache;
		if ($wgInterwikiCache) {
			return Interwiki::getInterwikiCached( $key );
		}
		$iw = Interwiki::load( $prefix );
		if( !$iw ){
			$iw = false;
		}
		if( self::CACHE_LIMIT && count( self::$smCache ) >= self::CACHE_LIMIT ){
			reset( self::$smCache );
			unset( self::$smCache[ key( self::$smCache ) ] );
		}
		self::$smCache[$prefix] = $iw;
		return $iw;
	}
	
	/**
	 * Fetch interwiki prefix data from local cache in constant database.
	 *
	 * @note More logic is explained in DefaultSettings.
	 *
	 * @param $key \type{\string} Database key
	 * @return \type{\Interwiki} An interwiki object
	 */
	protected static function getInterwikiCached( $key ) {
		$value = getInterwikiCacheEntry( $key );
		
		$s = new Interwiki( $key );
		if ( $value != '' ) {
			// Split values
			list( $local, $url ) = explode( ' ', $value, 2 );
			$s->mURL = $url;
			$s->mLocal = (int)$local;
		}else{
			$s = false;
		}
		if( self::CACHE_LIMIT && count( self::$smCache ) >= self::CACHE_LIMIT ){
			reset( self::$smCache );
			unset( self::$smCache[ key( self::$smCache ) ] );
		}
		self::$smCache[$prefix] = $s;
		return $s;
	}
	
	/**
	 * Get entry from interwiki cache
	 *
	 * @note More logic is explained in DefaultSettings.
	 *
	 * @param $key \type{\string} Database key
	 * @return \type{\string) The entry
	 */
	protected static function getInterwikiCacheEntry( $key ){
		global $wgInterwikiCache, $wgInterwikiScopes, $wgInterwikiFallbackSite;
		static $db, $site;

		if( !$db ){
			$db = dba_open( $wgInterwikiCache, 'r', 'cdb' );
		}
		/* Resolve site name */
		if( $wgInterwikiScopes>=3 && !$site ) {
			$site = dba_fetch( '__sites:' . wfWikiID(), $db );
			if ( $site == "" ){
				$site = $wgInterwikiFallbackSite;
			}
		}
		
		$value = dba_fetch( wfMemcKey( $key ), $db );
		// Site level
		if ( $value == '' && $wgInterwikiScopes >= 3 ) {
			$value = dba_fetch( "_{$site}:{$key}", $db );
		}
		// Global Level
		if ( $value == '' && $wgInterwikiScopes >= 2 ) {
			$value = dba_fetch( "__global:{$key}", $db );
		}
		if ( $value == 'undef' )
			$value = '';
			
		return $value;
	}

	/**
	 * Load the interwiki, trying first memcached then the DB
	 *
	 * @param $prefix The interwiki prefix
	 * @return bool The prefix is valid
	 * @static
	 *
	 */
	protected static function load( $prefix ) {
		global $wgMemc;
		$key = wfMemcKey( 'interwiki', $prefix );
		$mc = $wgMemc->get( $key );
		$iw = false;
		if( $mc && is_array( $mc ) ){ // is_array is hack for old keys
			$iw = Interwiki::loadFromArray( $mc );
			if( $iw ){
				return $iw;
			}
		}
		
		$db = wfGetDB( DB_SLAVE );
			
		$row = $db->fetchRow( $db->select( 'interwiki', '*', array( 'iw_prefix' => $prefix ),
			__METHOD__ ) );
		$iw = Interwiki::loadFromArray( $row );
		if ( $iw ) {
			$mc = array( 'iw_url' => $iw->mURL, 'iw_local' => $iw->mLocal, 'iw_trans' => $iw->mTrans );
			$wgMemc->add( $key, $mc );
			return $iw;
		}
		
		return false;
	}

	/**
	 * Fill in member variables from an array (e.g. memcached result, Database::fetchRow, etc)
	 *
	 * @return bool Whether everything was there
	 * @param $res ResultWrapper Row from the interwiki table
	 * @static
	 */
	protected static function loadFromArray( $mc ) {
		if( isset( $mc['iw_url'] ) && isset( $mc['iw_local'] ) && isset( $mc['iw_trans'] ) ){
			$iw = new Interwiki();
			$iw->mURL = $mc['iw_url'];
			$iw->mLocal = $mc['iw_local'];
			$iw->mTrans = $mc['iw_trans'];
			return $iw;
		}
		return false;
	}
	
	/** 
	 * Get the URL for a particular title (or with $1 if no title given)
	 * 
	 * @param $title string What text to put for the article name
	 * @return string The URL
	 */
	function getURL( $title = null ){
		$url = $this->mURL;
		if( $title != null ){
			$url = str_replace( "$1", $title, $url );
		}
		return $url;
	}
	
	function isLocal(){
		return $this->mLocal;
	}
	
	function isTranscludable(){
		return $this->mTrans;
	}

}
