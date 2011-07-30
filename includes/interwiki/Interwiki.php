<?php
/**
 * @file
 * Interwiki table entry
 */

/**
 * The interwiki class
 * All information is loaded on creation when called by Interwiki::fetch( $prefix ).
 * All work is done on slave, because this should *never* change (except during
 * schema updates etc, which aren't wiki-related)
 * This class also contains the functions that allow interwiki templates transclusion.
 */
class Interwiki {

	// Cache - removes oldest entry when it hits limit
	protected static $smCache = array();
	const CACHE_LIMIT = 100; // 0 means unlimited, any other value is max number of entries.

	protected $mPrefix, $mURL, $mAPI, $mWikiID, $mLocal, $mTrans;

	public function __construct( $prefix = null, $url = '', $api = '', $wikiId = '', $local = 0, $trans = 0 ) {
		$this->mPrefix = $prefix;
		$this->mURL = $url;
		$this->mAPI = $api;
		$this->mWikiID = $wikiId;
		$this->mLocal = $local;
		$this->mTrans = $trans;
	}

	/**
	 * Check whether an interwiki prefix exists
	 *
	 * @param $prefix String: interwiki prefix to use
	 * @return Boolean: whether it exists
	 */
	static public function isValidInterwiki( $prefix ) {
		$result = self::fetch( $prefix );
		return (bool)$result;
	}

	/**
	 * Fetch an Interwiki object
	 *
	 * @param $prefix String: interwiki prefix to use
	 * @return Interwiki Object, or null if not valid
	 */
	static public function fetch( $prefix ) {
		global $wgContLang;
		if( $prefix == '' ) {
			return null;
		}
		$prefix = $wgContLang->lc( $prefix );
		if( isset( self::$smCache[$prefix] ) ) {
			return self::$smCache[$prefix];
		}
		global $wgInterwikiCache;
		if( $wgInterwikiCache ) {
			$iw = Interwiki::getInterwikiCached( $prefix );
		} else {
			$iw = Interwiki::load( $prefix );
			if( !$iw ) {
				$iw = false;
			}
		}
		if( self::CACHE_LIMIT && count( self::$smCache ) >= self::CACHE_LIMIT ) {
			reset( self::$smCache );
			unset( self::$smCache[key( self::$smCache )] );
		}
		self::$smCache[$prefix] = $iw;
		return $iw;
	}

	/**
	 * Fetch interwiki prefix data from local cache in constant database.
	 *
	 * @note More logic is explained in DefaultSettings.
	 *
	 * @param $prefix String: interwiki prefix
	 * @return Interwiki object
	 */
	protected static function getInterwikiCached( $prefix ) {
		$value = self::getInterwikiCacheEntry( $prefix );

		$s = new Interwiki( $prefix );
		if ( $value != '' ) {
			// Split values
			list( $local, $url ) = explode( ' ', $value, 2 );
			$s->mURL = $url;
			$s->mLocal = (int)$local;
		} else {
			$s = false;
		}
		return $s;
	}

	/**
	 * Get entry from interwiki cache
	 *
	 * @note More logic is explained in DefaultSettings.
	 *
	 * @param $prefix String: database key
	 * @return String: the entry
	 */
	protected static function getInterwikiCacheEntry( $prefix ) {
		global $wgInterwikiCache, $wgInterwikiScopes, $wgInterwikiFallbackSite;
		static $db, $site;

		wfDebug( __METHOD__ . "( $prefix )\n" );
		if( !$db ) {
			$db = CdbReader::open( $wgInterwikiCache );
		}
		/* Resolve site name */
		if( $wgInterwikiScopes >= 3 && !$site ) {
			$site = $db->get( '__sites:' . wfWikiID() );
			if ( $site == '' ) {
				$site = $wgInterwikiFallbackSite;
			}
		}

		$value = $db->get( wfMemcKey( $prefix ) );
		// Site level
		if ( $value == '' && $wgInterwikiScopes >= 3 ) {
			$value = $db->get( "_{$site}:{$prefix}" );
		}
		// Global Level
		if ( $value == '' && $wgInterwikiScopes >= 2 ) {
			$value = $db->get( "__global:{$prefix}" );
		}
		if ( $value == 'undef' ) {
			$value = '';
		}

		return $value;
	}

	/**
	 * Load the interwiki, trying first memcached then the DB
	 *
	 * @param $prefix The interwiki prefix
	 * @return Boolean: the prefix is valid
	 */
	protected static function load( $prefix ) {
		global $wgMemc, $wgInterwikiExpiry;

		$iwData = false;
		if ( !wfRunHooks( 'InterwikiLoadPrefix', array( $prefix, &$iwData ) ) ) {
			return Interwiki::loadFromArray( $iwData );
		}

		if ( !$iwData ) {
			$key = wfMemcKey( 'interwiki', $prefix );
			$iwData = $wgMemc->get( $key );
		}

		if( $iwData && is_array( $iwData ) ) { // is_array is hack for old keys
			$iw = Interwiki::loadFromArray( $iwData );
			if( $iw ) {
				return $iw;
			}
		}

		$db = wfGetDB( DB_SLAVE );

		$row = $db->fetchRow( $db->select( 'interwiki', '*', array( 'iw_prefix' => $prefix ),
			__METHOD__ ) );
		$iw = Interwiki::loadFromArray( $row );
		if ( $iw ) {
			$mc = array(
				'iw_url' => $iw->mURL,
				'iw_api' => $iw->mAPI,
				'iw_wikiid' => $iw->mWikiID,
				'iw_local' => $iw->mLocal,
				'iw_trans' => $iw->mTrans
			);
			$wgMemc->add( $key, $mc, $wgInterwikiExpiry );
			return $iw;
		}

		return false;
	}

	/**
	 * Fill in member variables from an array (e.g. memcached result, Database::fetchRow, etc)
	 *
	 * @param $mc Associative array: row from the interwiki table
	 * @return Boolean: whether everything was there
	 */
	protected static function loadFromArray( $mc ) {
		if( isset( $mc['iw_url'] ) ) {
			$iw = new Interwiki();
			$iw->mURL = $mc['iw_url'];
			$iw->mLocal = isset( $mc['iw_local'] ) ? $mc['iw_local'] : 0;
			$iw->mTrans = isset( $mc['iw_trans'] ) ? $mc['iw_trans'] : 0;
			$iw->mAPI = isset( $mc['iw_api'] ) ? $mc['iw_api'] : 
			$iw->mAPI = isset( $mc['iw_api'] ) ? $mc['iw_api'] : '';
			$iw->mWikiID = isset( $mc['iw_wikiid'] ) ? $mc['iw_wikiid'] : '';
			
			return $iw;
		}
		return false;
	}

	/**
	 * Fetch all interwiki prefixes from interwiki cache
	 *
	 * @param $local If not null, limits output to local/non-local interwikis
	 * @return Array List of prefixes
	 * @since 1.19
	 */
	protected static function getAllPrefixesCached( $local ) {
		global $wgInterwikiCache, $wgInterwikiScopes, $wgInterwikiFallbackSite;
		static $db, $site;

		wfDebug( __METHOD__ . "()\n" );
		if( !$db ) {
			$db = CdbReader::open( $wgInterwikiCache );
		}
		/* Resolve site name */
		if( $wgInterwikiScopes >= 3 && !$site ) {
			$site = $db->get( '__sites:' . wfWikiID() );
			if ( $site == '' ) {
				$site = $wgInterwikiFallbackSite;
			}
		}

		// List of interwiki sources
		$sources = array();
		// Global Level
		if ( $wgInterwikiScopes >= 2 ) {
			$sources[] = '__global';
		}
		// Site level
		if ( $wgInterwikiScopes >= 3 ) {
			$sources[] = '_' . $site;
		}
		$sources[] = wfWikiID();

		$data = array();

		foreach( $sources as $source ) {
			$list = $db->get( "__list:{$source}" );
			foreach ( explode( ' ', $list ) as $iw_prefix ) {
				$row = $db->get( "{$source}:{$iw_prefix}" );
				if( !$row ) {
					continue;
				}

				list( $iw_local, $iw_url ) = explode( ' ', $row );

				if ( $local !== null && $local != $iw_local ) {
					continue;
				}

				$data[$iw_prefix] = array(
					'iw_prefix' => $iw_prefix,
					'iw_url'    => $iw_url,
					'iw_local'  => $iw_local,
				);
			}
		}

		ksort( $data );

		return array_values( $data );
	}

	/**
	 * Fetch all interwiki prefixes from DB
	 *
	 * @param $local If not null, limits output to local/non-local interwikis
	 * @return Array List of prefixes
	 * @since 1.19
	 */
	protected static function getAllPrefixesDB( $local ) {
		$db = wfGetDB( DB_SLAVE );

		$where = array();

		if ( $local !== null ) {
			if ( $local == 1 ) {
				$where['iw_local'] = 1;
			} elseif ( $local == 0 ) {
				$where['iw_local'] = 0;
			}
		}

		$res = $db->select( 'interwiki',
			array( 'iw_prefix', 'iw_url', 'iw_api', 'iw_wikiid', 'iw_local', 'iw_trans' ),
			$where, __METHOD__, array( 'ORDER BY' => 'iw_prefix' )
		);
		$retval = array();
		foreach ( $res as $row ) {
			$retval[] = (array)$row;
		}
		return $retval;
	}

	/**
	 * Returns all interwiki prefixes
	 *
	 * @param $local If set, limits output to local/non-local interwikis
	 * @return Array List of prefixes
	 * @since 1.19
	 */
	public static function getAllPrefixes( $local = null ) {
		global $wgInterwikiCache;

		if ( $wgInterwikiCache ) {
			return self::getAllPrefixesCached( $local );
		} else {
			return self::getAllPrefixesDB( $local );
		}
	}

	/**
	 * Get the URL for a particular title (or with $1 if no title given)
	 *
	 * @param $title String: what text to put for the article name
	 * @return String: the URL
	 */
	public function getURL( $title = null ) {
		$url = $this->mURL;
		if( $title != null ) {
			$url = str_replace( "$1", $title, $url );
		}
		return $url;
	}

	/**
	 * Get the API URL for this wiki
	 *
	 * @return String: the URL
	 */
	public function getAPI() {
		return $this->mAPI;
	}

	/**
	 * Get the DB name for this wiki
	 *
	 * @return String: the DB name
	 */
	public function getWikiID() {
		return $this->mWikiID;
	}

	/**
	 * Is this a local link from a sister project, or is
	 * it something outside, like Google
	 *
	 * @return Boolean
	 */
	public function isLocal() {
		return $this->mLocal;
	}

	/**
	 * Can pages from this wiki be transcluded?
	 * Still requires $wgEnableScaryTransclusion
	 *
	 * @return Boolean
	 */
	public function isTranscludable() {
		return $this->mTrans;
	}

	/**
	 * Get the name for the interwiki site
	 *
	 * @return String
	 */
	public function getName() {
		$msg = wfMessage( 'interwiki-name-' . $this->mPrefix )->inContentLanguage();
		return !$msg->exists() ? '' : $msg;
	}

	/**
	 * Get a description for this interwiki
	 *
	 * @return String
	 */
	public function getDescription() {
		$msg = wfMessage( 'interwiki-desc-' . $this->mPrefix )->inContentLanguage();
		return !$msg->exists() ? '' : $msg;
	}
	

	/**
	 * Transclude an interwiki link.
	 */
	public static function interwikiTransclude( $title ) {
			
		// If we have a wikiID, we will use it to get an access to the remote database
		// if not, we will use the API URL to retrieve the data through a HTTP Get
		
		$wikiID = $title->getTransWikiID( );
		$transAPI = $title->getTransAPI( );
		
		if ( $wikiID !== '') {
		
			$finalText = self::fetchTemplateFromDB( $wikiID, $title );
			return $finalText;

		} else if( $transAPI !== '' ) {
			
			$interwiki = $title->getInterwiki( );
			$fullTitle = $title->getSemiPrefixedText( );
			
			$finalText = self::fetchTemplateFromAPI( $interwiki, $transAPI, $fullTitle );
			
			return $finalText;
			
		}
		return false;
	}
	
	/**
	 * Retrieve the wikitext of a distant page accessing the foreign DB
	 */
	public static function fetchTemplateFromDB ( $wikiID, $title ) {
		
		$revision = Revision::loadFromTitleForeignWiki( $wikiID, $title );
		
		if ( $revision ) {
			$text = $revision->getText();
			return $text;
		}
				
		return false;
	}
	
	/**
	 * Retrieve the wikitext of a distant page using the API of the foreign wiki
	 */
	public static function fetchTemplateFromAPI( $interwiki, $transAPI, $fullTitle ) {
		global $wgMemc, $wgTranscludeCacheExpiry;
		
		$key = wfMemcKey( 'iwtransclustiontext', 'textid', $interwiki, $fullTitle );
		$text = $wgMemc->get( $key );
		if( is_array ( $text ) &&
				isset ( $text['missing'] ) &&
				$text['missing'] === true ) {
			return false;
		} else if ( $text ) {
			return $text;
		}
		
		$url = wfAppendQuery(
			$transAPI,
			array(	'action' => 'query',
					'titles' => $fullTitle,
					'prop' => 'revisions',
					'rvprop' => 'content',
					'format' => 'json'
			)
		);
		
		$get = Http::get( $url );
		$content = FormatJson::decode( $get, true );
			
		if ( isset ( $content['query'] ) &&
				isset ( $content['query']['pages'] ) ) {
			$page = array_pop( $content['query']['pages'] );
			if ( $page && isset( $page['revisions'][0]['*'] ) ) {
				$text = $page['revisions'][0]['*'];
				$wgMemc->set( $key, $text, $wgTranscludeCacheExpiry );

				// When we cache a template, we also retrieve and cache its subtemplates
				$subtemplates = self::getSubtemplatesListFromAPI( $interwiki, $transAPI, $fullTitle );
				self::cacheTemplatesFromAPI( $interwiki, $transAPI, $subtemplates );
				
				return $text;
			} else {
				$wgMemc->set( $key, array ( 'missing' => true ), $wgTranscludeCacheExpiry );
			}
		}
		return false;
	}	

	public static function getSubtemplatesListFromAPI ( $interwiki, $transAPI, $title ) {
		$url = wfAppendQuery( $transAPI,
			array( 'action' => 'query',
			'titles' => $title,
			'prop' => 'templates',
			'format' => 'json'
			)
		);
			
		$get = Http::get( $url );
		$myArray = FormatJson::decode($get, true);

		$templates = array( );
		if ( ! empty( $myArray['query'] )) {
			if ( ! empty( $myArray['query']['pages'] )) {
				$templates = array_pop( $myArray['query']['pages'] );
				if ( ! empty( $templates['templates'] )) {
					$templates = $templates['templates'];
				}
			}
			return $templates;
		}
	}

	public static function cacheTemplatesFromAPI( $interwiki, $transAPI, $titles ){
		global $wgMemc, $wgTranscludeCacheExpiry;
		
		$outdatedTitles = array( );
		
		foreach( $titles as $title ){
			if ( isset ( $title['title'] ) ) {
				$key = wfMemcKey( 'iwtransclustiontext', 'textid', $interwiki, $title['title'] );
				$text = $wgMemc->get( $key );
				if( !$text ){
					$outdatedTitles[] = $title['title'];
				}
			}			
		}
		
		$batches = array_chunk( $outdatedTitles, 50 );
		
		foreach( $batches as $batch ){
			$url = wfAppendQuery(
				$transAPI,
				array(	'action' => 'query',
						'titles' => implode( '|', $batch ),
						'prop' => 'revisions',
						'rvprop' => 'content',
						'format' => 'json'
				)
			);
			$get = Http::get( $url );
			$content = FormatJson::decode( $get, true );
				
			if ( isset ( $content['query'] ) &&
					isset ( $content['query']['pages'] ) ) {
				foreach( $content['query']['pages'] as $page ) {
					$key = wfMemcKey( 'iwtransclustiontext', 'textid', $interwiki, $page['title'] );
					if ( isset ( $page['revisions'][0]['*'] ) ) {
						$text = $page['revisions'][0]['*'];
					} else {
						$text = array ( 'missing' => true );
					}
					$wgMemc->set( $key, $text, $wgTranscludeCacheExpiry );	
				}
			}
		}
	}
}
