<?php
/**
 * Page view caching in the file system.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Cache
 */

/**
 * Page view caching in the file system.
 * The only cacheable actions are "view" and "history". Also special pages
 * will not be cached.
 *
 * @ingroup Cache
 */
class HTMLFileCache extends FileCacheBase {
	/**
	 * Construct an ObjectFileCache from a Title and an action
	 * @param Title|string $title Title object or prefixed DB key string
	 * @param string $action
	 * @throws MWException
	 * @return HTMLFileCache
	 *
	 * @deprecated Since 1.24, instantiate this class directly
	 */
	public static function newFromTitle( $title, $action ) {
		return new self( $title, $action );
	}

	/**
	 * @param Title|string $title Title object or prefixed DB key string
	 * @param string $action
	 * @throws MWException
	 */
	public function __construct( $title, $action ) {
		$allowedTypes = self::cacheablePageActions();
		if ( !in_array( $action, $allowedTypes ) ) {
			throw new MWException( 'Invalid file cache type given.' );
		}
		$this->mKey = ( $title instanceof Title )
			? $title->getPrefixedDBkey()
			: (string)$title;
		$this->mType = (string)$action;
		$this->mExt = 'html';
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
	 * Alter the type -> directory mapping to put action=view cache at the root.
	 *
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
	 * @param IContextSource $context
	 * @return bool
	 */
	public static function useFileCache( IContextSource $context ) {
		global $wgUseFileCache, $wgShowIPinHeader, $wgDebugToolbar, $wgContLang;
		if ( !$wgUseFileCache ) {
			return false;
		}
		if ( $wgShowIPinHeader || $wgDebugToolbar ) {
			wfDebug( "HTML file cache skipped. Either \$wgShowIPinHeader and/or \$wgDebugToolbar on\n" );

			return false;
		}

		// Get all query values
		$queryVals = $context->getRequest()->getValues();
		foreach ( $queryVals as $query => $val ) {
			if ( $query === 'title' || $query === 'curid' ) {
				continue; // note: curid sets title
			// Normal page view in query form can have action=view.
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
		$ulang = $context->getLanguage()->getCode();
		$clang = $wgContLang->getCode();

		// Check that there are no other sources of variation
		if ( $user->getId() || $user->getNewtalk() || $ulang != $clang ) {
			return false;
		}
		// Allow extensions to disable caching
		return Hooks::run( 'HTMLFileCache::useFileCache', array( $context ) );
	}

	/**
	 * Read from cache to context output
	 * @param IContextSource $context
	 * @return void
	 */
	public function loadFromFileCache( IContextSource $context ) {
		global $wgMimeType, $wgLanguageCode;

		wfDebug( __METHOD__ . "()\n" );
		$filename = $this->cachePath();

		$context->getOutput()->sendCacheControl();
		header( "Content-Type: $wgMimeType; charset=UTF-8" );
		header( "Content-Language: $wgLanguageCode" );
		if ( $this->useGzip() ) {
			if ( wfClientAcceptsGzip() ) {
				header( 'Content-Encoding: gzip' );
				readfile( $filename );
			} else {
				/* Send uncompressed */
				wfDebug( __METHOD__ . " uncompressing cache file and sending it\n" );
				readgzfile( $filename );
			}
		} else {
			readfile( $filename );
		}
		$context->getOutput()->disable(); // tell $wgOut that output is taken care of
	}

	/**
	 * Save this cache object with the given text.
	 * Use this as an ob_start() handler.
	 * @param string $text
	 * @return bool Whether $wgUseFileCache is enabled
	 */
	public function saveToFileCache( $text ) {
		global $wgUseFileCache;

		if ( !$wgUseFileCache || strlen( $text ) < 512 ) {
			// Disabled or empty/broken output (OOM and PHP errors)
			return $text;
		}

		wfDebug( __METHOD__ . "()\n", 'log' );

		$now = wfTimestampNow();
		if ( $this->useGzip() ) {
			$text = str_replace(
				'</html>', '<!-- Cached/compressed ' . $now . " -->\n</html>", $text );
		} else {
			$text = str_replace(
				'</html>', '<!-- Cached ' . $now . " -->\n</html>", $text );
		}

		// Store text to FS...
		$compressed = $this->saveText( $text );
		if ( $compressed === false ) {
			return $text; // error
		}

		// gzip output to buffer as needed and set headers...
		if ( $this->useGzip() ) {
			// @todo Ugly wfClientAcceptsGzip() function - use context!
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
	 * @param Title $title
	 * @return bool Whether $wgUseFileCache is enabled
	 */
	public static function clearFileCache( Title $title ) {
		global $wgUseFileCache;

		if ( !$wgUseFileCache ) {
			return false;
		}

		foreach ( self::cacheablePageActions() as $type ) {
			$fc = new self( $title, $type );
			$fc->clearCache();
		}

		return true;
	}
}
