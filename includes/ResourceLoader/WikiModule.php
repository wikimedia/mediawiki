<?php
/**
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

namespace MediaWiki\ResourceLoader;

use CSSJanus;
use InvalidArgumentException;
use MediaWiki\Content\Content;
use MediaWiki\Json\FormatJson;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MemoizedCallable;
use Wikimedia\Minify\CSSMin;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Timestamp\ConvertibleTimestamp;

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
 *
 * @ingroup ResourceLoader
 * @since 1.17
 */
class WikiModule extends Module {
	/** @var string Origin defaults to users with sitewide authority */
	protected $origin = self::ORIGIN_USER_SITEWIDE;

	/**
	 * In-process cache for title info, structured as an array
	 * [
	 *  <batchKey> // Pipe-separated list of sorted keys from getPages
	 *   => [
	 *     <titleKey> => [ // Normalised title key
	 *       'page_len' => ..,
	 *       'page_latest' => ..,
	 *       'page_touched' => ..,
	 *     ]
	 *   ]
	 * ]
	 * @see self::fetchTitleInfo()
	 * @see self::makeTitleKey()
	 * @var array
	 */
	protected $titleInfo = [];

	/** @var array List of page names that contain CSS */
	protected $styles = [];

	/** @var array List of page names that contain JavaScript */
	protected $scripts = [];

	/** @var array List of page names that contain JSON */
	protected $datas = [];

	/** @var string|null Group of module */
	protected $group;

	/**
	 * @param array|null $options For back-compat, this can be omitted in favour of overwriting
	 *  getPages.
	 */
	public function __construct( ?array $options = null ) {
		if ( $options === null ) {
			return;
		}

		foreach ( $options as $member => $option ) {
			switch ( $member ) {
				case 'styles':
				case 'scripts':
				case 'datas':
				case 'group':
					$this->{$member} = $option;
					break;
			}
		}
	}

	/**
	 * Subclasses should return an associative array of resources in the module.
	 * Keys should be the title of a page in the MediaWiki or User namespace.
	 *
	 * Values should be a nested array of options.
	 * The supported keys are 'type' and (CSS only) 'media'.
	 *
	 * For scripts, 'type' should be 'script'.
	 * For JSON files, 'type' should be 'data'.
	 * For stylesheets, 'type' should be 'style'.
	 *
	 * There is an optional 'media' key, the value of which can be the
	 * medium ('screen', 'print', etc.) of the stylesheet.
	 *
	 * @param Context $context
	 * @return array[]
	 * @phan-return array<string,array{type:string,media?:string}>
	 */
	protected function getPages( Context $context ) {
		$config = $this->getConfig();
		$pages = [];

		// Filter out pages from origins not allowed by the current wiki configuration.
		if ( $config->get( MainConfigNames::UseSiteJs ) ) {
			foreach ( $this->scripts as $script ) {
				$pages[$script] = [ 'type' => 'script' ];
			}
			foreach ( $this->datas as $data ) {
				$pages[$data] = [ 'type' => 'data' ];
			}
		}

		if ( $config->get( MainConfigNames::UseSiteCss ) ) {
			foreach ( $this->styles as $style ) {
				$pages[$style] = [ 'type' => 'style' ];
			}
		}

		return $pages;
	}

	/**
	 * Get group name
	 *
	 * @return string|null
	 */
	public function getGroup() {
		return $this->group;
	}

	/**
	 * Get the Database handle used for computing the module version.
	 *
	 * Subclasses may override this to return a foreign database, which would
	 * allow them to register a module on wiki A that fetches wiki pages from
	 * wiki B.
	 *
	 * The way this works is that the local module is a placeholder that can
	 * only computer a module version hash. The 'source' of the module must
	 * be set to the foreign wiki directly. Methods getScript() and getContent()
	 * will not use this handle and are not valid on the local wiki.
	 *
	 * @return IReadableDatabase
	 */
	protected function getDB() {
		return MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase();
	}

	/**
	 * @param string $titleText
	 * @param Context $context
	 * @return null|string
	 * @since 1.32 added the $context parameter
	 */
	protected function getContent( $titleText, Context $context ) {
		$pageStore = MediaWikiServices::getInstance()->getPageStore();
		$title = $pageStore->getPageByText( $titleText );
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
		} elseif ( $handler->isSupportedFormat( CONTENT_FORMAT_JSON ) ) {
			$format = CONTENT_FORMAT_JSON;
		} elseif ( $handler->isSupportedFormat( CONTENT_FORMAT_VUE ) ) {
			$format = CONTENT_FORMAT_VUE;
		} else {
			return null; // Bad content model
		}

		return $content->serialize( $format );
	}

	/**
	 * @param PageIdentity $page
	 * @param Context $context
	 * @param int $maxRedirects Maximum number of redirects to follow.
	 *        Either 0 or 1.
	 * @return Content|null
	 * @since 1.32 added the $context and $maxRedirects parameters
	 * @internal for testing
	 */
	protected function getContentObj(
		PageIdentity $page, Context $context, $maxRedirects = 1
	) {
		$overrideCallback = $context->getContentOverrideCallback();
		$content = $overrideCallback ? $overrideCallback( $page ) : null;
		if ( $content ) {
			if ( !$content instanceof Content ) {
				$this->getLogger()->error(
					'Bad content override for "{title}" in ' . __METHOD__,
					[ 'title' => (string)$page ]
				);
				return null;
			}
		} else {
			$revision = MediaWikiServices::getInstance()
				->getRevisionLookup()
				->getKnownCurrentRevision( $page );
			if ( !$revision ) {
				return null;
			}
			$content = $revision->getMainContentRaw();

			if ( !$content ) {
				$this->getLogger()->error(
					'Failed to load content of CSS/JS/JSON page "{title}" in ' . __METHOD__,
					[ 'title' => (string)$page ]
				);
				return null;
			}
		}

		if ( $maxRedirects > 0 ) {
			$newTitle = $content->getRedirectTarget();
			if ( $newTitle ) {
				return $this->getContentObj( $newTitle, $context, 0 );
			}
		}

		return $content;
	}

	/**
	 * @param Context $context
	 * @return bool
	 */
	public function shouldEmbedModule( Context $context ) {
		$overrideCallback = $context->getContentOverrideCallback();
		if ( $overrideCallback && $this->getSource() === 'local' ) {
			foreach ( $this->getPages( $context ) as $page => $info ) {
				$title = Title::newFromText( $page );
				if ( $title && $overrideCallback( $title ) !== null ) {
					return true;
				}
			}
		}

		return parent::shouldEmbedModule( $context );
	}

	/**
	 * @param Context $context
	 * @return string|array JavaScript code, or a package files array
	 */
	public function getScript( Context $context ) {
		if ( $this->isPackaged() ) {
			$packageFiles = $this->getPackageFiles( $context );
			// TODO deduplicate this from FileModule, move up to Module?
			foreach ( $packageFiles['files'] as &$file ) {
				if ( $file['type'] === 'script+style' ) {
					$file['content'] = $file['content']['script'];
					$file['type'] = 'script';
				}
			}
			return $packageFiles;
		} else {
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
	}

	/**
	 * Get whether this module is a packaged module.
	 *
	 * If false (the default), JavaScript pages are concatenated and executed as a single
	 * script. JSON pages are not supported.
	 *
	 * If true, the pages are bundled such that each page gets a virtual file name, where only
	 * the "main" script will be executed at first, and other JS or JSON pages may be be imported
	 * in client-side code through the `require()` function.
	 *
	 * @stable to override
	 * @since 1.38
	 * @return bool
	 */
	protected function isPackaged(): bool {
		// Packaged mode is disabled by default for backwards compatibility.
		// Subclasses may opt-in to this feature.
		return false;
	}

	/**
	 * @return bool
	 */
	public function supportsURLLoading() {
		// If package files are involved, don't support URL loading
		return !$this->isPackaged();
	}

	/**
	 * Convert a namespace-formatted page title to a virtual package file name.
	 *
	 * This determines how the page may be imported in client-side code via `require()`.
	 *
	 * @stable to override
	 * @since 1.38
	 * @param string $titleText
	 * @return string
	 */
	protected function getRequireKey( string $titleText ): string {
		return $titleText;
	}

	/**
	 * @param Context $context
	 * @return array{main:?string,files:array<string,array>}
	 */
	private function getPackageFiles( Context $context ): array {
		$main = null;

		$files = [];
		foreach ( $this->getPages( $context ) as $titleText => $options ) {

			if (
				$options['type'] !== 'script' &&
				$options['type'] !== 'script-vue' &&
				$options['type'] !== 'data'
			) {
				continue;
			}
			$content = $this->getContent( $titleText, $context );
			if ( strval( $content ) !== '' ) {
				$fileKey = $this->getRequireKey( $titleText );
				if ( $options['type'] === 'script' ) {
					$script = $this->validateScriptFile( $titleText, $content );
					$files[$fileKey] = [
						'type' => 'script',
						'content' => $script,
					];
					// First script becomes the "main" script
					$main ??= $fileKey;

				} elseif ( $options['type'] === 'script-vue' ) {
					try {
						$files[$fileKey]['content'] = $this->parseVueContent( $context, $content );
					} catch ( InvalidArgumentException $e ) {
						$message = "Failed to parse vue component in $titleText: {$e->getMessage()}";
						$files[$fileKey]['content'] = [
							'script' => 'mw.log.error( ' . $context->encodeJson( $message ) . ' )',
							'style' => ''
						];
					}
					if ( $files[$fileKey]['content']['styleLang'] === 'less' ) {
						$message = "Failed to parse Vue component in $titleText: Use of LESS styles is not supported.";
						$files[$fileKey]['content'] = [
							'script' => 'mw.log.error( ' . $context->encodeJson( $message ) . ' )',
							'style' => ''
						];
					}
					$files[$fileKey]['content']['titleText'] = $titleText;
					$files[$fileKey]['type'] = 'script+style';

				} elseif ( $options['type'] === 'data' ) {
					$data = FormatJson::decode( $content );
					if ( $data == null ) {
						// This is unlikely to happen since we only load JSON from
						// wiki pages with a JSON content model, which are validated
						// during edit save.
						$data = [ 'error' => 'Invalid JSON' ];
					}
					$files[$fileKey] = [
						'type' => 'data',
						'content' => $data,
					];
				}
			}
		}

		return [
			'main' => $main,
			'files' => $files,
		];
	}

	/**
	 * @param Context $context
	 * @return array
	 */
	public function getStyles( Context $context ) {
		$remoteDir = $this->getConfig()->get( MainConfigNames::ScriptPath );
		if ( $remoteDir === '' ) {
			// When the site is configured with the script path at the
			// document root, MediaWiki uses an empty string but that is
			// not a valid URI path. Expand to a slash to avoid fatals
			// later in CSSMin::resolveUrl().
			// See also FilePath::extractBasePaths, T282280.
			$remoteDir = '/';
		}

		$styles = [];
		foreach ( $this->getPages( $context ) as $titleText => $options ) {
			if ( $options['type'] !== 'style' ) {
				continue;
			}
			$style = $this->getContent( $titleText, $context );
			if ( strval( $style ) === '' ) {
				continue;
			}
			if ( $this->getFlip( $context ) ) {
				$style = CSSJanus::transform( $style, true, false );
			}

			$style = MemoizedCallable::call(
				[ CSSMin::class, 'remap' ],
				[ $style, false, $remoteDir, true ]
			);
			$media = $options['media'] ?? 'all';
			$style = ResourceLoader::makeComment( $titleText ) . $style;
			$styles[$media][] = $style;
		}

		if ( $this->isPackaged() ) {
			$packageFiles = $this->getPackageFiles( $context );
			foreach ( $packageFiles['files'] as $fileName => $file ) {
				if ( $file['type'] === 'script+style' ) {
					$style = $file['content']['style'];
					if ( $this->getFlip( $context ) ) {
						$style = CSSJanus::transform( $style, true, false );
					}

					$style = MemoizedCallable::call(
						[ CSSMin::class, 'remap' ],
						[ $style, false, $remoteDir, true ]
					);

					$style = ResourceLoader::makeComment( $file['content']['titleText'] ) . $style;
					$styles['all'][] = $style;
				}
			}
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
	 * @param Context $context
	 * @return array
	 */
	public function getDefinitionSummary( Context $context ) {
		$summary = parent::getDefinitionSummary( $context );
		$summary[] = [
			'pages' => $this->getPages( $context ),
			// Includes meta data of current revisions
			'titleInfo' => $this->getTitleInfo( $context ),
		];
		return $summary;
	}

	/**
	 * @param Context $context
	 * @return bool
	 */
	public function isKnownEmpty( Context $context ) {
		// If a module has dependencies it cannot be empty. An empty array will be cast to false
		if ( $this->getDependencies() ) {
			return false;
		}

		// Optimisation: For user modules, don't needlessly load if there are no non-empty pages
		// This is worthwhile because unlike most modules, user modules require their own
		// separate embedded request (managed by ClientHtml).
		$revisions = $this->getTitleInfo( $context );
		if ( $this->getGroup() === self::GROUP_USER ) {
			foreach ( $revisions as $revision ) {
				if ( $revision['page_len'] > 0 ) {
					// At least one non-empty page, module should be loaded
					return false;
				}
			}
			return true;
		}

		// T70488: For non-user modules (i.e. ones that are called in cached HTML output) only check
		// page existence. This ensures that, if some pages in a module are temporarily blanked,
		// we don't stop embedding the module's script or link tag on newly cached pages.
		return count( $revisions ) === 0;
	}

	private function setTitleInfo( string $batchKey, array $titleInfo ) {
		$this->titleInfo[$batchKey] = $titleInfo;
	}

	private static function makeTitleKey( LinkTarget $title ): string {
		// Used for keys in titleInfo.
		return "{$title->getNamespace()}:{$title->getDBkey()}";
	}

	/**
	 * Get the information about the wiki pages for a given context.
	 * @param Context $context
	 * @return array[] Keyed by page name
	 */
	protected function getTitleInfo( Context $context ) {
		$pageNames = array_keys( $this->getPages( $context ) );
		sort( $pageNames );
		$batchKey = implode( '|', $pageNames );
		if ( !isset( $this->titleInfo[$batchKey] ) ) {
			$this->titleInfo[$batchKey] = static::fetchTitleInfo( $this->getDB(), $pageNames, __METHOD__ );
		}

		$titleInfo = $this->titleInfo[$batchKey];

		// Override the title info from the overrides, if any
		$overrideCallback = $context->getContentOverrideCallback();
		if ( $overrideCallback ) {
			foreach ( $pageNames as $page ) {
				$title = Title::newFromText( $page );
				$content = $title ? $overrideCallback( $title ) : null;
				if ( $content !== null ) {
					$titleInfo[$title->getPrefixedText()] = [
						'page_len' => $content->getSize(),
						'page_latest' => 'TBD', // None available
						'page_touched' => ConvertibleTimestamp::now( TS_MW ),
					];
				}
			}
		}

		return $titleInfo;
	}

	/**
	 * @param IReadableDatabase $db
	 * @param string[] $pages
	 * @param string $fname @phan-mandatory-param
	 * @return array
	 */
	protected static function fetchTitleInfo( IReadableDatabase $db, array $pages, $fname = __METHOD__ ) {
		$titleInfo = [];
		$linkBatchFactory = MediaWikiServices::getInstance()->getLinkBatchFactory();
		$batch = $linkBatchFactory->newLinkBatch();
		foreach ( $pages as $titleText ) {
			$title = Title::newFromText( $titleText );
			if ( $title ) {
				// Page name may be invalid if user-provided (e.g. gadgets)
				$batch->addObj( $title );
			}
		}
		if ( !$batch->isEmpty() ) {
			$res = $db->newSelectQueryBuilder()
				// Include page_touched to allow purging if cache is poisoned (T117587, T113916)
				->select( [ 'page_namespace', 'page_title', 'page_touched', 'page_len', 'page_latest' ] )
				->from( 'page' )
				->where( $batch->constructSet( 'page', $db ) )
				->caller( $fname )->fetchResultSet();
			foreach ( $res as $row ) {
				// Avoid including ids or timestamps of revision/page tables so
				// that versions are not wasted
				$title = new TitleValue( (int)$row->page_namespace, $row->page_title );
				$titleInfo[self::makeTitleKey( $title )] = [
					'page_len' => $row->page_len,
					'page_latest' => $row->page_latest,
					'page_touched' => ConvertibleTimestamp::convert( TS_MW, $row->page_touched ),
				];
			}
		}
		return $titleInfo;
	}

	/**
	 * Batched version of WikiModule::getTitleInfo
	 *
	 * Title info for the passed modules is cached together. On index.php, OutputPage improves
	 * cache use by having one batch shared between all users (site-wide modules) and a batch
	 * for current-user modules.
	 *
	 * @since 1.28
	 * @internal For use by ResourceLoader and OutputPage only
	 * @param Context $context
	 * @param string[] $moduleNames
	 */
	public static function preloadTitleInfo(
		Context $context, array $moduleNames, ?string $ns = null
	) {
		$rl = $context->getResourceLoader();
		// getDB() can be overridden to point to a foreign database.
		// Group pages by database to ensure we fetch titles from the correct database.
		// By preloading both local and foreign titles, this method doesn't depend
		// on knowing the local database.

		/** @var array<string,array{db:IReadableDatabase,pages:string[],modules:WikiModule[]}> $byDomain */
		$byDomain = [];
		foreach ( $moduleNames as $name ) {
			$module = $rl->getModule( $name );
			if ( $module instanceof self ) {
				// Subclasses may implement getDB differently
				$db = $module->getDB();
				$domain = $db->getDomainID();

				$byDomain[ $domain ] ??= [ 'db' => $db, 'pages' => [], 'modules' => [] ];
				$byDomain[ $domain ]['pages'] = array_merge(
					$byDomain[ $domain ]['pages'],
					array_keys( $module->getPages( $context ) )
				);
				$byDomain[ $domain ]['modules'][] = $module;
			}
		}

		if ( !$byDomain ) {
			// Nothing to preload
			return;
		}

		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$fname = __METHOD__;

		foreach ( $byDomain as $domainId => $batch ) {
			// Fetch title info
			sort( $batch['pages'] );
			$pagesHash = sha1( implode( '|', $batch['pages'] ) );
			$allInfo = $cache->getWithSetCallback(
				$cache->makeGlobalKey( $ns === 'user' ? 'resourceloader-titleinfo-user' : 'resourceloader-titleinfo',
					$domainId, $pagesHash ),
				$cache::TTL_HOUR,
				static function ( $curVal, &$ttl, array &$setOpts ) use ( $batch, $fname ) {
					$setOpts += Database::getCacheSetOptions( $batch['db'] );
					return static::fetchTitleInfo( $batch['db'], $batch['pages'], $fname );
				},
				[
					'checkKeys' => [ $cache->makeGlobalKey( 'resourceloader-titleinfo', $domainId ) ]
				]
			);

			// Inject to WikiModule objects
			foreach ( $batch['modules'] as $wikiModule ) {
				$pages = $wikiModule->getPages( $context );
				$info = [];
				foreach ( $pages as $pageName => $unused ) {
					// Map page name to canonical form (T145673).
					$title = Title::newFromText( $pageName );
					if ( !$title ) {
						// Page name may be invalid if user-provided (e.g. gadgets)
						$rl->getLogger()->info(
							'Invalid wiki page title "{title}" in ' . __METHOD__,
							[ 'title' => $pageName ]
						);
						continue;
					}
					$infoKey = self::makeTitleKey( $title );
					if ( isset( $allInfo[$infoKey] ) ) {
						$info[$infoKey] = $allInfo[$infoKey];
					}
				}
				$pageNames = array_keys( $pages );
				sort( $pageNames );
				$batchKey = implode( '|', $pageNames );
				$wikiModule->setTitleInfo( $batchKey, $info );
			}
		}
	}

	/**
	 * Clear the preloadTitleInfo() cache for all wiki modules on this wiki on
	 * page change if it was a JS or CSS page
	 *
	 * @internal
	 * @param PageIdentity $page
	 * @param RevisionRecord|null $old Prior page revision
	 * @param RevisionRecord|null $new New page revision
	 * @param string $domain Database domain ID
	 */
	public static function invalidateModuleCache(
		PageIdentity $page,
		?RevisionRecord $old,
		?RevisionRecord $new,
		string $domain
	) {
		static $models = [ CONTENT_MODEL_CSS, CONTENT_MODEL_JAVASCRIPT, CONTENT_MODEL_VUE ];

		$purge = false;
		// TODO: MCR: differentiate between page functionality and content model!
		//       Not all pages containing CSS or JS have to be modules! [PageType]
		if ( $old ) {
			$oldModel = $old->getMainContentModel();
			if ( in_array( $oldModel, $models ) ) {
				$purge = true;
			}
		}

		if ( !$purge && $new ) {
			$newModel = $new->getMainContentModel();
			if ( in_array( $newModel, $models ) ) {
				$purge = true;
			}
		}

		if ( !$purge ) {
			$title = Title::newFromPageIdentity( $page );
			$purge = ( $title->isSiteConfigPage() || $title->isUserConfigPage() );
		}

		if ( $purge ) {
			$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
			$key = $cache->makeGlobalKey( 'resourceloader-titleinfo', $domain );
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
