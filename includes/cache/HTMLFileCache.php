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

use MediaWiki\MediaWikiServices;

/**
 * Page view caching in the file system.
 * The only cacheable actions are "view" and "history". Also special pages
 * will not be cached.
 *
 * @ingroup Cache
 */
class HTMLFileCache extends FileCacheBase {
	const MODE_NORMAL = 0; // normal cache mode
	const MODE_OUTAGE = 1; // fallback cache for DB outages
	const MODE_REBUILD = 2; // background cache rebuild mode

	/**
	 * @param Title|string $title Title object or prefixed DB key string
	 * @param string $action
	 * @throws MWException
	 */
	public function __construct( $title, $action ) {
		parent::__construct();

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
		return [ 'view', 'history' ];
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
	 * @param integer $mode One of the HTMLFileCache::MODE_* constants (since 1.28)
	 * @return bool
	 */
	public static function useFileCache( IContextSource $context, $mode = self::MODE_NORMAL ) {
		$config = MediaWikiServices::getInstance()->getMainConfig();

		if ( !$config->get( 'UseFileCache' ) && $mode !== self::MODE_REBUILD ) {
			return false;
		} elseif ( $config->get( 'DebugToolbar' ) ) {
			wfDebug( "HTML file cache skipped. \$wgDebugToolbar on\n" );

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
		$ulang = $context->getLanguage();

		// Check that there are no other sources of variation
		if ( $user->getId() || $ulang->getCode() !== $config->get( 'LanguageCode' ) ) {
			return false;
		}

		if ( $mode === self::MODE_NORMAL ) {
			if ( $user->getNewtalk() ) {
				return false;
			}
		}

		// Allow extensions to disable caching
		return Hooks::run( 'HTMLFileCache::useFileCache', [ $context ] );
	}

	/**
	 * Read from cache to context output
	 * @param IContextSource $context
	 * @param integer $mode One of the HTMLFileCache::MODE_* constants
	 * @return void
	 */
	public function loadFromFileCache( IContextSource $context, $mode = self::MODE_NORMAL ) {
		global $wgContLang;
		$config = MediaWikiServices::getInstance()->getMainConfig();

		wfDebug( __METHOD__ . "()\n" );
		$filename = $this->cachePath();

		if ( $mode === self::MODE_OUTAGE ) {
			// Avoid DB errors for queries in sendCacheControl()
			$context->getTitle()->resetArticleID( 0 );
		}

		$context->getOutput()->sendCacheControl();
		header( "Content-Type: {$config->get( 'MimeType' )}; charset=UTF-8" );
		header( "Content-Language: {$wgContLang->getHtmlCode()}" );
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
	 *
	 * Normally this is only registed as a handler if $wgUseFileCache is on.
	 * If can be explicitly called by rebuildFileCache.php when it takes over
	 * handling file caching itself, disabling any automatic handling the the
	 * process.
	 *
	 * @param string $text
	 * @return string|bool The annotated $text or false on error
	 */
	public function saveToFileCache( $text ) {
		if ( strlen( $text ) < 512 ) {
			// Disabled or empty/broken output (OOM and PHP errors)
			return $text;
		}

		wfDebug( __METHOD__ . "()\n", 'private' );

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
		$config = MediaWikiServices::getInstance()->getMainConfig();

		if ( !$config->get( 'UseFileCache' ) ) {
			return false;
		}

		foreach ( self::cacheablePageActions() as $type ) {
			$fc = new self( $title, $type );
			$fc->clearCache();
		}

		return true;
	}
}
