<?php
/**
 * Contain the HTMLFileCache class
 * @file
 * @ingroup Cache
 */

/**
 * Handles talking to the file cache, putting stuff in and taking it back out.
 * Mostly called from Article.php, also from DatabaseFunctions.php for the
 * emergency abort/fallback to cache.
 *
 * Global options that affect this module:
 * - $wgCachePages
 * - $wgCacheEpoch
 * - $wgUseFileCache
 * - $wgCacheDirectory
 * - $wgFileCacheDirectory
 * - $wgUseGzip
 *
 * @ingroup Cache
 */
class HTMLFileCache {
	var $mTitle, $mFileCache, $mType;

	public function __construct( &$title, $type = 'view' ) {
		$this->mTitle = $title;
		$this->mType = ($type == 'raw' || $type == 'view' ) ? $type : false;
		$this->fileCacheName(); // init name
	}

	public function fileCacheName() {
		if( !$this->mFileCache ) {
			global $wgCacheDirectory, $wgFileCacheDirectory, $wgRequest;

			if ( $wgFileCacheDirectory ) {
				$dir = $wgFileCacheDirectory;
			} elseif ( $wgCacheDirectory ) {
				$dir = "$wgCacheDirectory/html";
			} else {
				throw new MWException( 'Please set $wgCacheDirectory in LocalSettings.php if you wish to use the HTML file cache' );
			}

			# Store raw pages (like CSS hits) elsewhere
			$subdir = ($this->mType === 'raw') ? 'raw/' : '';
			$key = $this->mTitle->getPrefixedDbkey();
			$hash = md5( $key );
			# Avoid extension confusion
			$key = str_replace( '.', '%2E', urlencode( $key ) );
	
			$hash1 = substr( $hash, 0, 1 );
			$hash2 = substr( $hash, 0, 2 );
			$this->mFileCache = "{$wgFileCacheDirectory}/{$subdir}{$hash1}/{$hash2}/{$key}.html";

			if( $this->useGzip() )
				$this->mFileCache .= '.gz';

			wfDebug( __METHOD__ . ": {$this->mFileCache}\n" );
		}
		return $this->mFileCache;
	}

	public function isFileCached() {
		if( $this->mType === false ) return false;
		return file_exists( $this->fileCacheName() );
	}

	public function fileCacheTime() {
		return wfTimestamp( TS_MW, filemtime( $this->fileCacheName() ) );
	}
	
	/**
	 * Check if pages can be cached for this request/user
	 * @return bool
	 */
	public static function useFileCache() {
		global $wgUser, $wgUseFileCache, $wgShowIPinHeader, $wgRequest, $wgLang, $wgContLang;
		if( !$wgUseFileCache ) return false;
		// Get all query values
		$queryVals = $wgRequest->getValues();
		foreach( $queryVals as $query => $val ) {
			if( $query == 'title' || $query == 'curid' ) continue;
			// Normal page view in query form can have action=view.
			// Raw hits for pages also stored, like .css pages for example.
			else if( $query == 'action' && ($val == 'view' || $val == 'raw') ) continue;
			else if( $query == 'usemsgcache' && $val == 'yes' ) continue;
			// Below are header setting params
			else if( $query == 'maxage' || $query == 'smaxage' || $query == 'ctype' || $query == 'gen' )
				continue;
			else
				return false;
		}
		// Check for non-standard user language; this covers uselang,
		// and extensions for auto-detecting user language.
		$ulang = $wgLang->getCode();
		$clang = $wgContLang->getCode();
		// Check that there are no other sources of variation
		return !$wgShowIPinHeader && !$wgUser->getId() && !$wgUser->getNewtalk() && $ulang == $clang;
	}

	/* 
	* Check if up to date cache file exists
	* @param $timestamp string
	*/
	public function isFileCacheGood( $timestamp = '' ) {
		global $wgCacheEpoch;

		if( !$this->isFileCached() ) return false;

		$cachetime = $this->fileCacheTime();
		$good = $timestamp <= $cachetime && $wgCacheEpoch <= $cachetime;

		wfDebug( __METHOD__ . ": cachetime $cachetime, touched '{$timestamp}' epoch {$wgCacheEpoch}, good $good\n");
		return $good;
	}

	public function useGzip() {
		global $wgUseGzip;
		return $wgUseGzip;
	}

	/* In handy string packages */
	public function fetchRawText() {
		return file_get_contents( $this->fileCacheName() );
	}

	public function fetchPageText() {
		if( $this->useGzip() ) {
			/* Why is there no gzfile_get_contents() or gzdecode()? */
			return implode( '', gzfile( $this->fileCacheName() ) );
		} else {
			return $this->fetchRawText();
		}
	}

	/* Working directory to/from output */
	public function loadFromFileCache() {
		global $wgOut, $wgMimeType, $wgOutputEncoding, $wgContLanguageCode;
		wfDebug( __METHOD__ . "()\n");
		$filename = $this->fileCacheName();
		// Raw pages should handle cache control on their own,
		// even when using file cache. This reduces hits from clients.
		if( $this->mType !== 'raw' ) {
			$wgOut->sendCacheControl();
			header( "Content-Type: $wgMimeType; charset={$wgOutputEncoding}" );
			header( "Content-Language: $wgContLanguageCode" );
		}

		if( $this->useGzip() ) {
			if( wfClientAcceptsGzip() ) {
				header( 'Content-Encoding: gzip' );
			} else {
				/* Send uncompressed */
				readgzfile( $filename );
				return;
			}
		}
		readfile( $filename );
		$wgOut->disable(); // tell $wgOut that output is taken care of
	}

	protected function checkCacheDirs() {
		$filename = $this->fileCacheName();
		$mydir2 = substr($filename,0,strrpos($filename,'/')); # subdirectory level 2
		$mydir1 = substr($mydir2,0,strrpos($mydir2,'/')); # subdirectory level 1

		wfMkdirParents( $mydir1 );
		wfMkdirParents( $mydir2 );
	}

	public function saveToFileCache( $text ) {
		global $wgUseFileCache;
		if( !$wgUseFileCache || strlen( $text ) < 512 ) {
			// Disabled or empty/broken output (OOM and PHP errors)
			return $text;
		}

		wfDebug( __METHOD__ . "()\n", false);

		$this->checkCacheDirs();

		$f = fopen( $this->fileCacheName(), 'w' );
		if($f) {
			$now = wfTimestampNow();
			if( $this->useGzip() ) {
				$rawtext = str_replace( '</html>',
					'<!-- Cached/compressed '.$now." -->\n</html>",
					$text );
				$text = gzencode( $rawtext );
			} else {
				$text = str_replace( '</html>',
					'<!-- Cached '.$now." -->\n</html>",
					$text );
			}
			fwrite( $f, $text );
			fclose( $f );
			if( $this->useGzip() ) {
				if( wfClientAcceptsGzip() ) {
					header( 'Content-Encoding: gzip' );
					return $text;
				} else {
					return $rawtext;
				}
			} else {
				return $text;
			}
		}
		return $text;
	}

	public static function clearFileCache( $title ) {
		global $wgUseFileCache;
		if( !$wgUseFileCache ) return false;
		$fc = new self( $title, 'view' );
		@unlink( $fc->fileCacheName() );
		$fc = new self( $title, 'raw' );
		@unlink( $fc->fileCacheName() );
		return true;
	}
}
