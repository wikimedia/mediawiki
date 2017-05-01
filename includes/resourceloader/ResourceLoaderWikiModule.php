<?php
/**
 * Abstraction for ResourceLoader modules that pull from wiki pages.
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
 * @author Trevor Parscal
 * @author Roan Kattouw
 */

use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;

/**
 * Abstraction for ResourceLoader modules which pull from wiki pages
 *
 * This can only be used for wiki pages in the MediaWiki and User namespaces,
 * because of its dependence on the functionality of Title::isCssJsSubpage
 * and Title::isCssOrJsPage().
 *
 * This module supports being used as a placeholder for a module on a remote wiki.
 * To do so, getDB() must be overloaded to return a foreign database object that
 * allows local wikis to query page metadata.
 *
 * Safe for calls on local wikis are:
 * - Option getters:
 *   - getGroup()
 *   - getPages()
 * - Basic methods that strictly involve the foreign database
 *   - getDB()
 *   - isKnownEmpty()
 *   - getTitleInfo()
 */
class ResourceLoaderWikiModule extends ResourceLoaderModule {

	// Origin defaults to users with sitewide authority
	protected $origin = self::ORIGIN_USER_SITEWIDE;

	// In-process cache for title info
	protected $titleInfo = [];

	// List of page names that contain CSS
	protected $styles = [];

	// List of page names that contain JavaScript
	protected $scripts = [];

	// Group of module
	protected $group;

	/**
	 * @param array $options For back-compat, this can be omitted in favour of overwriting getPages.
	 */
	public function __construct( array $options = null ) {
		if ( is_null( $options ) ) {
			return;
		}

		foreach ( $options as $member => $option ) {
			switch ( $member ) {
				case 'styles':
				case 'scripts':
				case 'group':
				case 'targets':
					$this->{$member} = $option;
					break;
			}
		}
	}

	/**
	 * Subclasses should return an associative array of resources in the module.
	 * Keys should be the title of a page in the MediaWiki or User namespace.
	 *
	 * Values should be a nested array of options.  The supported keys are 'type' and
	 * (CSS only) 'media'.
	 *
	 * For scripts, 'type' should be 'script'.
	 *
	 * For stylesheets, 'type' should be 'style'.
	 * There is an optional media key, the value of which can be the
	 * medium ('screen', 'print', etc.) of the stylesheet.
	 *
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	protected function getPages( ResourceLoaderContext $context ) {
		$config = $this->getConfig();
		$pages = [];

		// Filter out pages from origins not allowed by the current wiki configuration.
		if ( $config->get( 'UseSiteJs' ) ) {
			foreach ( $this->scripts as $script ) {
				$pages[$script] = [ 'type' => 'script' ];
			}
		}

		if ( $config->get( 'UseSiteCss' ) ) {
			foreach ( $this->styles as $style ) {
				$pages[$style] = [ 'type' => 'style' ];
			}
		}

		return $pages;
	}

	/**
	 * Get group name
	 *
	 * @return string
	 */
	public function getGroup() {
		return $this->group;
	}

	/**
	 * Get the Database object used in getTitleInfo().
	 *
	 * Defaults to the local replica DB. Subclasses may want to override this to return a foreign
	 * database object, or null if getTitleInfo() shouldn't access the database.
	 *
	 * NOTE: This ONLY works for getTitleInfo() and isKnownEmpty(), NOT FOR ANYTHING ELSE.
	 * In particular, it doesn't work for getContent() or getScript() etc.
	 *
	 * @return IDatabase|null
	 */
	protected function getDB() {
		return wfGetDB( DB_REPLICA );
	}

	/**
	 * @param string $titleText
	 * @return null|string
	 */
	protected function getContent( $titleText ) {
		$title = Title::newFromText( $titleText );
		if ( !$title ) {
			return null; // Bad title
		}

		// If the page is a redirect, follow the redirect.
		if ( $title->isRedirect() ) {
			$content = $this->getContentObj( $title );
			$title = $content ? $content->getUltimateRedirectTarget() : null;
			if ( !$title ) {
				return null; // Dead redirect
			}
		}

		$handler = ContentHandler::getForTitle( $title );
		if ( $handler->isSupportedFormat( CONTENT_FORMAT_CSS ) ) {
			$format = CONTENT_FORMAT_CSS;
		} elseif ( $handler->isSupportedFormat( CONTENT_FORMAT_JAVASCRIPT ) ) {
			$format = CONTENT_FORMAT_JAVASCRIPT;
		} else {
			return null; // Bad content model
		}

		$content = $this->getContentObj( $title );
		if ( !$content ) {
			return null; // No content found
		}

		return $content->serialize( $format );
	}

	/**
	 * @param Title $title
	 * @return Content|null
	 */
	protected function getContentObj( Title $title ) {
		$revision = Revision::newKnownCurrent( wfGetDB( DB_REPLICA ), $title->getArticleID(),
			$title->getLatestRevID() );
		if ( !$revision ) {
			return null;
		}
		$revision->setTitle( $title );
		$content = $revision->getContent( Revision::RAW );
		if ( !$content ) {
			wfDebugLog( 'resourceloader', __METHOD__ . ': failed to load content of JS/CSS page!' );
			return null;
		}
		return $content;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return string JavaScript code
	 */
	public function getScript( ResourceLoaderContext $context ) {
		$scripts = '';
		foreach ( $this->getPages( $context ) as $titleText => $options ) {
			if ( $options['type'] !== 'script' ) {
				continue;
			}
			$script = $this->getContent( $titleText );
			if ( strval( $script ) !== '' ) {
				$script = $this->validateScriptFile( $titleText, $script );
				$scripts .= ResourceLoader::makeComment( $titleText ) . $script . "\n";
			}
		}
		return $scripts;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getStyles( ResourceLoaderContext $context ) {
		$styles = [];
		foreach ( $this->getPages( $context ) as $titleText => $options ) {
			if ( $options['type'] !== 'style' ) {
				continue;
			}
			$media = isset( $options['media'] ) ? $options['media'] : 'all';
			$style = $this->getContent( $titleText );
			if ( strval( $style ) === '' ) {
				continue;
			}
			if ( $this->getFlip( $context ) ) {
				$style = CSSJanus::transform( $style, true, false );
			}
			$style = MemoizedCallable::call( 'CSSMin::remap',
				[ $style, false, $this->getConfig()->get( 'ScriptPath' ), true ] );
			if ( !isset( $styles[$media] ) ) {
				$styles[$media] = [];
			}
			$style = ResourceLoader::makeComment( $titleText ) . $style;
			$styles[$media][] = $style;
		}
		return $styles;
	}

	/**
	 * Disable module content versioning.
	 *
	 * This class does not support generating content outside of a module
	 * request due to foreign database support.
	 *
	 * See getDefinitionSummary() for meta-data versioning.
	 *
	 * @return bool
	 */
	public function enableModuleContentVersion() {
		return false;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getDefinitionSummary( ResourceLoaderContext $context ) {
		$summary = parent::getDefinitionSummary( $context );
		$summary[] = [
			'pages' => $this->getPages( $context ),
			// Includes meta data of current revisions
			'titleInfo' => $this->getTitleInfo( $context ),
		];
		return $summary;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return bool
	 */
	public function isKnownEmpty( ResourceLoaderContext $context ) {
		$revisions = $this->getTitleInfo( $context );

		// For user modules, don't needlessly load if there are no non-empty pages
		if ( $this->getGroup() === 'user' ) {
			foreach ( $revisions as $revision ) {
				if ( $revision['page_len'] > 0 ) {
					// At least one non-empty page, module should be loaded
					return false;
				}
			}
			return true;
		}

		// T70488: For other modules (i.e. ones that are called in cached html output) only check
		// page existance. This ensures that, if some pages in a module are temporarily blanked,
		// we don't end omit the module's script or link tag on some pages.
		return count( $revisions ) === 0;
	}

	private function setTitleInfo( $key, array $titleInfo ) {
		$this->titleInfo[$key] = $titleInfo;
	}

	/**
	 * Get the information about the wiki pages for a given context.
	 * @param ResourceLoaderContext $context
	 * @return array Keyed by page name
	 */
	protected function getTitleInfo( ResourceLoaderContext $context ) {
		$dbr = $this->getDB();
		if ( !$dbr ) {
			// We're dealing with a subclass that doesn't have a DB
			return [];
		}

		$pageNames = array_keys( $this->getPages( $context ) );
		sort( $pageNames );
		$key = implode( '|', $pageNames );
		if ( !isset( $this->titleInfo[$key] ) ) {
			$this->titleInfo[$key] = static::fetchTitleInfo( $dbr, $pageNames, __METHOD__ );
		}
		return $this->titleInfo[$key];
	}

	protected static function fetchTitleInfo( IDatabase $db, array $pages, $fname = __METHOD__ ) {
		$titleInfo = [];
		$batch = new LinkBatch;
		foreach ( $pages as $titleText ) {
			$title = Title::newFromText( $titleText );
			if ( $title ) {
				// Page name may be invalid if user-provided (e.g. gadgets)
				$batch->addObj( $title );
			}
		}
		if ( !$batch->isEmpty() ) {
			$res = $db->select( 'page',
				// Include page_touched to allow purging if cache is poisoned (T117587, T113916)
				[ 'page_namespace', 'page_title', 'page_touched', 'page_len', 'page_latest' ],
				$batch->constructSet( 'page', $db ),
				$fname
			);
			foreach ( $res as $row ) {
				// Avoid including ids or timestamps of revision/page tables so
				// that versions are not wasted
				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				$titleInfo[$title->getPrefixedText()] = [
					'page_len' => $row->page_len,
					'page_latest' => $row->page_latest,
					'page_touched' => $row->page_touched,
				];
			}
		}
		return $titleInfo;
	}

	/**
	 * @since 1.28
	 * @param ResourceLoaderContext $context
	 * @param IDatabase $db
	 * @param string[] $moduleNames
	 */
	public static function preloadTitleInfo(
		ResourceLoaderContext $context, IDatabase $db, array $moduleNames
	) {
		$rl = $context->getResourceLoader();
		// getDB() can be overridden to point to a foreign database.
		// For now, only preload local. In the future, we could preload by wikiID.
		$allPages = [];
		/** @var ResourceLoaderWikiModule[] $wikiModules */
		$wikiModules = [];
		foreach ( $moduleNames as $name ) {
			$module = $rl->getModule( $name );
			if ( $module instanceof self ) {
				$mDB = $module->getDB();
				// Subclasses may disable getDB and implement getTitleInfo differently
				if ( $mDB && $mDB->getWikiID() === $db->getWikiID() ) {
					$wikiModules[] = $module;
					$allPages += $module->getPages( $context );
				}
			}
		}

		if ( !$wikiModules ) {
			// Nothing to preload
			return;
		}

		$pageNames = array_keys( $allPages );
		sort( $pageNames );
		$hash = sha1( implode( '|', $pageNames ) );

		// Avoid Zend bug where "static::" does not apply LSB in the closure
		$func = [ static::class, 'fetchTitleInfo' ];
		$fname = __METHOD__;

		$cache = ObjectCache::getMainWANInstance();
		$allInfo = $cache->getWithSetCallback(
			$cache->makeGlobalKey( 'resourceloader', 'titleinfo', $db->getWikiID(), $hash ),
			$cache::TTL_HOUR,
			function ( $curVal, &$ttl, array &$setOpts ) use ( $func, $pageNames, $db, $fname ) {
				$setOpts += Database::getCacheSetOptions( $db );

				return call_user_func( $func, $db, $pageNames, $fname );
			},
			[ 'checkKeys' => [ $cache->makeGlobalKey( 'resourceloader', 'titleinfo', $db->getWikiID() ) ] ]
		);

		foreach ( $wikiModules as $wikiModule ) {
			$pages = $wikiModule->getPages( $context );
			// Before we intersect, map the names to canonical form (T145673).
			$intersect = [];
			foreach ( $pages as $page => $unused ) {
				$title = Title::newFromText( $page );
				if ( $title ) {
					$intersect[ $title->getPrefixedText() ] = 1;
				} else {
					// Page name may be invalid if user-provided (e.g. gadgets)
					$rl->getLogger()->info(
						'Invalid wiki page title "{title}" in ' . __METHOD__,
						[ 'title' => $page ]
					);
				}
			}
			$info = array_intersect_key( $allInfo, $intersect );
			$pageNames = array_keys( $pages );
			sort( $pageNames );
			$key = implode( '|', $pageNames );
			$wikiModule->setTitleInfo( $key, $info );
		}
	}

	/**
	 * Clear the preloadTitleInfo() cache for all wiki modules on this wiki on
	 * page change if it was a JS or CSS page
	 *
	 * @param Title $title
	 * @param Revision|null $old Prior page revision
	 * @param Revision|null $new New page revision
	 * @param string $wikiId
	 * @since 1.28
	 */
	public static function invalidateModuleCache(
		Title $title, Revision $old = null, Revision $new = null, $wikiId
	) {
		static $formats = [ CONTENT_FORMAT_CSS, CONTENT_FORMAT_JAVASCRIPT ];

		if ( $old && in_array( $old->getContentFormat(), $formats ) ) {
			$purge = true;
		} elseif ( $new && in_array( $new->getContentFormat(), $formats ) ) {
			$purge = true;
		} else {
			$purge = ( $title->isCssOrJsPage() || $title->isCssJsSubpage() );
		}

		if ( $purge ) {
			$cache = ObjectCache::getMainWANInstance();
			$key = $cache->makeGlobalKey( 'resourceloader', 'titleinfo', $wikiId );
			$cache->touchCheckKey( $key );
		}
	}

	/**
	 * @since 1.28
	 * @return string
	 */
	public function getType() {
		// Check both because subclasses don't always pass pages via the constructor,
		// they may also override getPages() instead, in which case we should keep
		// defaulting to LOAD_GENERAL and allow them to override getType() separately.
		return ( $this->styles && !$this->scripts ) ? self::LOAD_STYLES : self::LOAD_GENERAL;
	}
}
