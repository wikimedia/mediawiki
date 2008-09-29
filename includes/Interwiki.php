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

	// Cache - removed in LRU order when it hits limit
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
	 * Fetch an Interwiki object
	 * 
	 * @return Interwiki Object, or null if not valid
	 * @param $prefix string Interwiki prefix to use
	 */
	static public function fetch( $prefix ) {
		if( isset( self::$smCache[$prefix] ) ){
			return self::$smCache[$prefix];
		}
		global $wgInterwikiCache;
		if ($wgInterwikiCache) {
			return Interwiki::getInterwikiCached( $key );
		}
		$iw = new Interwiki;
		$iw->load( $prefix );
		if( self::CACHE_LIMIT && count( self::$smCache ) >= self::CACHE_LIMIT ){
			array_shift( self::$smCache );
		}
		self::$smCache[$prefix] = &$iw;
		return $iw;
	}
	
	/**
	 * Fetch interwiki prefix data from local cache in constant database.
	 *
	 * @note More logic is explained in DefaultSettings.
	 *
	 * @param $key \type{\string} Database key
	 * @return \type{\string} URL of interwiki site
	 */
	protected static function getInterwikiCached( $key ) {
		global $wgInterwikiCache, $wgInterwikiScopes, $wgInterwikiFallbackSite;
		static $db, $site;

		if (!$db)
			$db=dba_open($wgInterwikiCache,'r','cdb');
		/* Resolve site name */
		if ($wgInterwikiScopes>=3 and !$site) {
			$site = dba_fetch('__sites:' . wfWikiID(), $db);
			if ($site=="")
				$site = $wgInterwikiFallbackSite;
		}
		$value = dba_fetch( wfMemcKey( $key ), $db);
		if ($value=='' and $wgInterwikiScopes>=3) {
			/* try site-level */
			$value = dba_fetch("_{$site}:{$key}", $db);
		}
		if ($value=='' and $wgInterwikiScopes>=2) {
			/* try globals */
			$value = dba_fetch("__global:{$key}", $db);
		}
		if ($value=='undef')
			$value='';
		$s = new Interwiki( $key );
		if ( $value != '' ) {
			list( $local, $url ) = explode( ' ', $value, 2 );
			$s->mURL = $url;
			$s->mLocal = (int)$local;
		}
		if( self::CACHE_LIMIT && count( self::$smCache ) >= self::CACHE_LIMIT ){
			array_shift( self::$smCache );
		}
		self::$smCache[$prefix] = &$s;
		return $s;
	}

	/**
	 * Clear all member variables in the current object. Does not clear
	 * the block from the DB.
	 */
	function clear() {
		$this->mURL = '';
		$this->mLocal = $this->mTrans = 0;
		$this->mPrefix = null;
	}

	/**
	 * Get the DB object
	 *
	 * @return Database
	 */
	function &getDB(){
		$db = wfGetDB( DB_SLAVE );
		return $db;
	}

	/**
	 * Load interwiki from the DB
	 *
	 * @param $prefix The interwiki prefix
	 * @return bool The prefix is valid
	 *
	 */
	function load( $prefix ) {
		global $wgMemc;
		$key = wfMemcKey( 'interwiki', $prefix );
		$mc = $wgMemc->get( $key );
		if( $mc ){
			if( $this->loadFromArray( $mc ) ){
				wfDebug("Succeeded\n");
				return true;
			}
		}else{
			$db =& $this->getDB();
			
			$res = $db->resultObject( $db->select( 'interwiki', '*', array( 'iw_prefix' => $prefix ),
				__METHOD__ ) );
			if ( $this->loadFromResult( $res ) ) {
				$mc = array( 'url' => $this->mURL, 'local' => $this->mLocal, 'trans' => $this->mTrans );
				$wgMemc->add( $key, $mc );
				return true;
			}
		}
		
		# Give up
		$this->clear();
		return false;
	}

	/**
	 * Fill in member variables from an array (e.g. memcached result)
	 *
	 * @return bool Whether everything was there
	 * @param $res ResultWrapper Row from the interwiki table
	 */
	function loadFromArray( $mc ) {
		if( isset( $mc['url'] ) && isset( $mc['local'] ) && isset( $mc['trans'] ) ){
			$this->mURL = $mc['url'];
			$this->mLocal = $mc['local'];
			$this->mTrans = $mc['trans'];
			return true;
		}
		return false;
	}
	
	/**
	 * Fill in member variables from a result wrapper
	 *
	 * @return bool Whether there was a row there
	 * @param $res ResultWrapper Row from the interwiki table
	 */
	function loadFromResult( ResultWrapper $res ) {
		$ret = false;
		if ( 0 != $res->numRows() ) {
			# Get first entry
			$row = $res->fetchObject();
			$this->initFromRow( $row );
			$ret = true;
		}
		$res->free();
		return $ret;
	}

	/**
	 * Given a database row from the interwiki table, initialize
	 * member variables
	 *
	 * @param $row ResultWrapper A row from the interwiki table
	 */
	function initFromRow( $row ) {
		$this->mPrefix = $row->iw_prefix;
		$this->mURL = $row->iw_url;
		$this->mLocal = $row->iw_local;
		$this->mTrans = $row->iw_trans;
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
