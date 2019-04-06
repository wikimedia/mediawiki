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

use MediaWiki\Linker\LinkTarget;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;
use MediaWiki\MediaWikiServices;

/**
 * Abstraction for ResourceLoader modules which pull from wiki pages
 *
 * This can only be used for wiki pages in the MediaWiki and User namespaces,
 * because of its dependence on the functionality of Title::isUserConfigPage()
 * and Title::isSiteConfigPage().
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

	// In-process cache for title info, structured as an array
	// [
	//  <batchKey> // Pipe-separated list of sorted keys from getPages
	//   => [
	//     <titleKey> => [ // Normalised title key
	//       'page_len' => ..,
	//       'page_latest' => ..,
	//       'page_touched' => ..,
	//     ]
	//   ]
	// ]
	// @see self::fetchTitleInfo()
	// @see self::makeTitleKey()
	protected $titleInfo = [];

	// List of page names that contain CSS
	protected $styles = [];

	// List of page names that contain JavaScript
	protected $scripts = [];

	// Group of module
	protected $group;

	/**
	 * @param array|null $options For back-compat, this can be omitted in favour of overwriting
	 *  getPages.
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
	 * @param ResourceLoaderContext|null $context (but passing null is deprecated)
	 * @return null|string
	 * @since 1.32 added the $context parameter
	 */
	protected function getContent( $titleText, ResourceLoaderContext $context = null ) {
		$title = Title::newFromText( $titleText );
		if ( !$title ) {
			return null; // Bad title
		}

		$content = $this->getContentObj( $title, $context );
		if ( !$content ) {
			return null; // No content found
		}

		$handler = $content->getContentHandler();
		if ( $handler->isSupportedFormat( CONTENT_FORMAT_CSS ) ) {
			$format = CONTENT_FORMAT_CSS;
		} elseif ( $handler->isSupportedFormat( CONTENT_FORMAT_JAVASCRIPT ) ) {
			$format = CONTENT_FORMAT_JAVASCRIPT;
		} else {
			return null; // Bad content model
		}

		return $content->serialize( $format );
	}

	/**
	 * @param Title $title
	 * @param ResourceLoaderContext|null $context (but passing null is deprecated)
	 * @param int|null $maxRedirects Maximum number of redirects to follow. If
	 *  null, uses $wgMaxRedirects
	 * @return Content|null
	 * @since 1.32 added the $context and $maxRedirects parameters
	 */
	protected function getContentObj(
		Title $title, ResourceLoaderContext $context = null, $maxRedirects = null
	) {
		if ( $context === null ) {
			wfDeprecated( __METHOD__ . ' without a ResourceLoader context', '1.32' );
		}

		$overrideCallback = $context ? $context->getContentOverrideCallback() : null;
		$content = $overrideCallback ? call_user_func( $overrideCallback, $title ) : null;
		if ( $content ) {
			if ( !$content instanceof Content ) {
				$this->getLogger()->error(
					'Bad content override for "{title}" in ' . __METHOD__,
					[ 'title' => $title->getPrefixedText() ]
				);
				return null;
			}
		} else {
			$revision = Revision::newKnownCurrent( wfGetDB( DB_REPLICA ), $title );
			if ( !$revision ) {
				return null;
			}
			$content = $revision->getContent( Revision::RAW );

			if ( !$content ) {
				$this->getLogger()->error(
					'Failed to load content of JS/CSS page "{title}" in ' . __METHOD__,
					[ 'title' => $title->getPrefixedText() ]
				);
				return null;
			}
		}

		if ( $content && $content->isRedirect() ) {
			if ( $maxRedirects === null ) {
				$maxRedirects = $this->getConfig()->get( 'MaxRedirects' ) ?: 0;
			}
			if ( $maxRedirects > 0 ) {
				$newTitle = $content->getRedirectTarget();
				return $newTitle ? $this->getContentObj( $newTitle, $context, $maxRedirects - 1 ) : null;
			}
		}

		return $content;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return bool
	 */
	public function shouldEmbedModule( ResourceLoaderContext $context ) {
		$overrideCallback = $context->getContentOverrideCallback();
		if ( $overrideCallback && $this->getSource() === 'local' ) {
			foreach ( $this->getPages( $context ) as $page => $info ) {
				$title = Title::newFromText( $page );
				if ( $title && call_user_func( $overrideCallback, $title ) !== null ) {
					return true;
				}
			}
		}

		return parent::shouldEmbedModule( $context );
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
			$script = $this->getContent( $titleText, $context );
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
			$media = $options['media'] ?? 'all';
			$style = $this->getContent( $titleText, $context );
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

		// If a module has dependencies it cannot be empty. An empty array will be cast to false
		if ( $this->getDependencies() ) {
			return false;
		}
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

	private function setTitleInfo( $batchKey, array $titleInfo ) {
		$this->titleInfo[$batchKey] = $titleInfo;
	}

	private static function makeTitleKey( LinkTarget $title ) {
		// Used for keys in titleInfo.
		return "{$title->getNamespace()}:{$title->getDBkey()}";
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
		$batchKey = implode( '|', $pageNames );
		if ( !isset( $this->titleInfo[$batchKey] ) ) {
			$this->titleInfo[$batchKey] = static::fetchTitleInfo( $dbr, $pageNames, __METHOD__ );
		}

		$titleInfo = $this->titleInfo[$batchKey];

		// Override the title info from the overrides, if any
		$overrideCallback = $context->getContentOverrideCallback();
		if ( $overrideCallback ) {
			foreach ( $pageNames as $page ) {
				$title = Title::newFromText( $page );
				$content = $title ? call_user_func( $overrideCallback, $title ) : null;
				if ( $content !== null ) {
					$titleInfo[$title->getPrefixedText()] = [
						'page_len' => $content->getSize(),
						'page_latest' => 'TBD', // None available
						'page_touched' => wfTimestamp( TS_MW ),
					];
				}
			}
		}

		return $titleInfo;
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
				$title = new TitleValue( (int)$row->page_namespace, $row->page_title );
				$titleInfo[self::makeTitleKey( $title )] = [
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
				if ( $mDB && $mDB->getDomainID() === $db->getDomainID() ) {
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

		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$allInfo = $cache->getWithSetCallback(
			$cache->makeGlobalKey( 'resourceloader', 'titleinfo', $db->getDomainID(), $hash ),
			$cache::TTL_HOUR,
			function ( $curVal, &$ttl, array &$setOpts ) use ( $func, $pageNames, $db, $fname ) {
				$setOpts += Database::getCacheSetOptions( $db );

				return call_user_func( $func, $db, $pageNames, $fname );
			},
			[
				'checkKeys' => [
					$cache->makeGlobalKey( 'resourceloader', 'titleinfo', $db->getDomainID() ) ]
			]
		);

		foreach ( $wikiModules as $wikiModule ) {
			$pages = $wikiModule->getPages( $context );
			// Before we intersect, map the names to canonical form (T145673).
			$intersect = [];
			foreach ( $pages as $pageName => $unused ) {
				$title = Title::newFromText( $pageName );
				if ( $title ) {
					$intersect[ self::makeTitleKey( $title ) ] = 1;
				} else {
					// Page name may be invalid if user-provided (e.g. gadgets)
					$rl->getLogger()->info(
						'Invalid wiki page title "{title}" in ' . __METHOD__,
						[ 'title' => $pageName ]
					);
				}
			}
			$info = array_intersect_key( $allInfo, $intersect );
			$pageNames = array_keys( $pages );
			sort( $pageNames );
			$batchKey = implode( '|', $pageNames );
			$wikiModule->setTitleInfo( $batchKey, $info );
		}
	}

	/**
	 * Clear the preloadTitleInfo() cache for all wiki modules on this wiki on
	 * page change if it was a JS or CSS page
	 *
	 * @param Title $title
	 * @param Revision|null $old Prior page revision
	 * @param Revision|null $new New page revision
	 * @param string $domain Database domain ID
	 * @since 1.28
	 */
	public static function invalidateModuleCache(
		Title $title, Revision $old = null, Revision $new = null, $domain
	) {
		static $formats = [ CONTENT_FORMAT_CSS, CONTENT_FORMAT_JAVASCRIPT ];

		Assert::parameterType( 'string', $domain, '$domain' );

		// TODO: MCR: differentiate between page functionality and content model!
		//       Not all pages containing CSS or JS have to be modules! [PageType]
		if ( $old && in_array( $old->getContentFormat(), $formats ) ) {
			$purge = true;
		} elseif ( $new && in_array( $new->getContentFormat(), $formats ) ) {
			$purge = true;
		} else {
			$purge = ( $title->isSiteConfigPage() || $title->isUserConfigPage() );
		}

		if ( $purge ) {
			$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
			$key = $cache->makeGlobalKey( 'resourceloader', 'titleinfo', $domain );
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
