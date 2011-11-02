<?php
/**
 * Contain the HTMLFileCache class
 * @file
 * @ingroup Cache
 */
class HTMLFileCache extends FileCacheBase {
	/**
	 * Construct an ObjectFileCache from a Title and an action
	 * @param $title Title|string Title object or prefixed DB key string
	 * @param $action string
	 * @return HTMLFileCache
	 */
	public static function newFromTitle( $title, $action ) {
		$cache = new self();

		$allowedTypes = self::cacheablePageActions();
		if ( !in_array( $action, $allowedTypes ) ) {
			throw new MWException( "Invalid filecache type given." );
		}
		$cache->mKey = ( $title instanceof Title )
			? $title->getPrefixedDBkey()
			: (string)$title;
		$cache->mType = (string)$action;
		$cache->mExt = 'html';

		return $cache;
	}

	/**
	 * Cacheable actions
	 * @return array
	 */
	protected static function cacheablePageActions() {
		return array( 'view', 'history' );
	}

	/**
	 * Get the base file cache directory
	 * @return string
	 */
	protected function cacheDirectory() {
		return $this->baseCacheDirectory(); // no subdir for b/c with old cache files
	}

	/**
	 * Get the cache type subdirectory (with the trailing slash) or the empty string
	 * @return string
	 */
	protected function typeSubdirectory() {
		if ( $this->mType === 'view' ) {
			return ''; //  b/c to not skip existing cache
		} else {
			return $this->mType . '/';
		}
	}

	/**
	 * Check if pages can be cached for this request/user
	 * @param $context IContextSource
	 * @return bool
	 */
	public static function useFileCache( IContextSource $context ) {
		global $wgUseFileCache, $wgShowIPinHeader, $wgContLang;
		if ( !$wgUseFileCache ) {
			return false;
		}
		// Get all query values
		$queryVals = $context->getRequest()->getValues();
		foreach ( $queryVals as $query => $val ) {
			if ( $query === 'title' || $query === 'curid' ) {
				continue; // note: curid sets title
			// Normal page view in query form can have action=view.
			// Raw hits for pages also stored, like .css pages for example.
			} elseif ( $query === 'action' && in_array( $val, self::cacheablePageActions() ) ) {
				continue;
			// Below are header setting params
			} elseif ( $query === 'maxage' || $query === 'smaxage' ) {
				continue;
			}
			return false;
		}
		$user = $context->getUser();
		// Check for non-standard user language; this covers uselang,
		// and extensions for auto-detecting user language.
		$ulang = $context->getLang()->getCode();
		$clang = $wgContLang->getCode();
		// Check that there are no other sources of variation
		return !$wgShowIPinHeader && !$user->getId() && !$user->getNewtalk() && $ulang == $clang;
	}

	/**
	 * Read from cache to context output
	 * @param $context IContextSource
	 * @return void
	 */
	public function loadFromFileCache( IContextSource $context ) {
		global $wgMimeType, $wgLanguageCode;

		wfDebug( __METHOD__ . "()\n");
		$filename = $this->cachePath();
		$context->getOutput()->sendCacheControl();
		header( "Content-Type: $wgMimeType; charset=UTF-8" );
		header( "Content-Language: $wgLanguageCode" );
		if ( $this->useGzip() ) {
			if ( wfClientAcceptsGzip() ) {
				header( 'Content-Encoding: gzip' );
			} else {
				/* Send uncompressed */
				readgzfile( $filename );
				return;
			}
		}
		readfile( $filename );
		$context->getOutput()->disable(); // tell $wgOut that output is taken care of
	}

	/**
	 * Save this cache object with the given text.
	 * Use this as an ob_start() handler.
	 * @param $text string
	 * @return bool Whether $wgUseFileCache is enabled
	 */
	public function saveToFileCache( $text ) {
		global $wgUseFileCache;

		if ( !$wgUseFileCache || strlen( $text ) < 512 ) {
			// Disabled or empty/broken output (OOM and PHP errors)
			return $text;
		}

		wfDebug( __METHOD__ . "()\n", false);

		$now = wfTimestampNow();
		if ( $this->useGzip() ) {
			$text = str_replace(
				'</html>', '<!-- Cached/compressed '.$now." -->\n</html>", $text );
		} else {
			$text = str_replace(
				'</html>', '<!-- Cached '.$now." -->\n</html>", $text );
		}

		// Store text to FS...
		$compressed = $this->saveText( $text );
		if ( $compressed === false ) {
			return $text; // error
		}

		// gzip output to buffer as needed and set headers...
		if ( $this->useGzip() ) {
			// @TODO: ugly wfClientAcceptsGzip() function - use context!
			if ( wfClientAcceptsGzip() ) {
				header( 'Content-Encoding: gzip' );
				return $compressed;
			} else {
				return $text;
			}
		} else {
			return $text;
		}
	}

	/**
	 * Clear the file caches for a page for all actions
	 * @param $title Title
	 * @return bool Whether $wgUseFileCache is enabled
	 */
	public static function clearFileCache( Title $title ) {
		global $wgUseFileCache;

		if ( !$wgUseFileCache ) {
			return false;
		}

		foreach ( self::cacheablePageActions() as $type ) {
			$fc = self::newFromTitle( $title, $type );
			$fc->clearCache();
		}

		return true;
	}
}
