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

namespace MediaWiki\Cache;

use InvalidArgumentException;
use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;

/**
 * Page view caching in the file system.
 * The only cacheable actions are "view" and "history". Also special pages
 * will not be cached.
 *
 * @ingroup Cache
 */
class HTMLFileCache extends FileCacheBase {
	public const MODE_NORMAL = 0; // normal cache mode
	public const MODE_OUTAGE = 1; // fallback cache for DB outages
	public const MODE_REBUILD = 2; // background cache rebuild mode

	private const CACHEABLE_ACTIONS = [
		'view',
		'history',
	];

	/**
	 * @param PageIdentity|string $page PageIdentity object or prefixed DB key string
	 * @param string $action
	 */
	public function __construct( $page, $action ) {
		parent::__construct();

		if ( !in_array( $action, self::CACHEABLE_ACTIONS ) ) {
			throw new InvalidArgumentException( 'Invalid file cache type given.' );
		}

		$this->mKey = CacheKeyHelper::getKeyForPage( $page );
		$this->mType = (string)$action;
		$this->mExt = 'html';
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
			return ''; // b/c to not skip existing cache
		} else {
			return $this->mType . '/';
		}
	}

	/**
	 * Check if pages can be cached for this request/user
	 * @param IContextSource $context
	 * @param int $mode One of the HTMLFileCache::MODE_* constants (since 1.28)
	 * @return bool
	 */
	public static function useFileCache( IContextSource $context, $mode = self::MODE_NORMAL ) {
		$services = MediaWikiServices::getInstance();
		$config = $services->getMainConfig();

		if ( !$config->get( MainConfigNames::UseFileCache ) && $mode !== self::MODE_REBUILD ) {
			return false;
		}

		// Get all query values
		$queryVals = $context->getRequest()->getValues();
		foreach ( $queryVals as $query => $val ) {
			if ( $query === 'title' || $query === 'curid' ) {
				continue; // note: curid sets title
			// Normal page view in query form can have action=view.
			} elseif ( $query === 'action' && in_array( $val, self::CACHEABLE_ACTIONS ) ) {
				continue;
			// Below are header setting params
			} elseif ( $query === 'maxage' || $query === 'smaxage' ) {
				continue;
			// Uselang value is checked below
			} elseif ( $query === 'uselang' ) {
				continue;
			}

			return false;
		}

		$user = $context->getUser();
		// Check for non-standard user language; this covers uselang,
		// and extensions for auto-detecting user language.
		$ulang = $context->getLanguage();

		// Check that there are no other sources of variation
		if ( $user->isRegistered() ||
			!$ulang->equals( $services->getContentLanguage() ) ) {
			return false;
		}

		$userHasNewMessages = $services->getTalkPageNotificationManager()->userHasNewMessages( $user );
		if ( ( $mode === self::MODE_NORMAL ) && $userHasNewMessages ) {
			return false;
		}

		// Allow extensions to disable caching
		return ( new HookRunner( $services->getHookContainer() ) )->onHTMLFileCache__useFileCache( $context );
	}

	/**
	 * Read from cache to context output
	 * @param IContextSource $context
	 * @param int $mode One of the HTMLFileCache::MODE_* constants
	 * @return void
	 */
	public function loadFromFileCache( IContextSource $context, $mode = self::MODE_NORMAL ) {
		wfDebug( __METHOD__ . "()" );
		$filename = $this->cachePath();

		if ( $mode === self::MODE_OUTAGE ) {
			// Avoid DB errors for queries in sendCacheControl()
			$context->getTitle()->resetArticleID( 0 );
		}

		$context->getOutput()->sendCacheControl();
		header( "Content-Type: {$this->options->get( MainConfigNames::MimeType )}; charset=UTF-8" );
		header( 'Content-Language: ' .
			MediaWikiServices::getInstance()->getContentLanguage()->getHtmlCode() );
		if ( $this->useGzip() ) {
			if ( wfClientAcceptsGzip() ) {
				header( 'Content-Encoding: gzip' );
				readfile( $filename );
			} else {
				/* Send uncompressed */
				wfDebug( __METHOD__ . " uncompressing cache file and sending it" );
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
	 * Normally this is only registered as a handler if $wgUseFileCache is on.
	 * If can be explicitly called by rebuildFileCache.php when it takes over
	 * handling file caching itself, disabling any automatic handling the
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
		// @todo Ugly wfClientAcceptsGzip() function - use context!
		if ( $this->useGzip() && wfClientAcceptsGzip() ) {
			header( 'Content-Encoding: gzip' );

			return $compressed;
		}

		return $text;
	}

	/**
	 * Clear the file caches for a page for all actions
	 *
	 * @param PageIdentity|string $page PageIdentity object or prefixed DB key string
	 * @return bool Whether $wgUseFileCache is enabled
	 */
	public static function clearFileCache( $page ) {
		$config = MediaWikiServices::getInstance()->getMainConfig();
		if ( !$config->get( MainConfigNames::UseFileCache ) ) {
			return false;
		}

		foreach ( self::CACHEABLE_ACTIONS as $type ) {
			$fc = new self( $page, $type );
			$fc->clearCache();
		}

		return true;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( HTMLFileCache::class, 'HTMLFileCache' );
