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

/**
 * Abstraction for ResourceLoader modules which pull from wiki pages
 *
 * This can only be used for wiki pages in the MediaWiki and User namespaces,
 * because of its dependence on the functionality of Title::isCssJsSubpage.
 *
 * This module supports being used as a placeholder for a module on a remote wiki.
 * To do so, getDB() must be overloaded to return a foreign database object that
 * allows local wikis to query page metadata.
 *
 * Safe for calls on local wikis are:
 * - Option getters:
 *   - getGroup()
 *   - getPosition()
 *   - getPages()
 * - Basic methods that strictly involve the foreign database
 *   - getDB()
 *   - isKnownEmpty()
 *   - getTitleInfo()
 */
class ResourceLoaderWikiModule extends ResourceLoaderModule {
	/** @var string Position on the page to load this module at */
	protected $position = 'bottom';

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
				case 'position':
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
	 * Defaults to the local slave DB. Subclasses may want to override this to return a foreign
	 * database object, or null if getTitleInfo() shouldn't access the database.
	 *
	 * NOTE: This ONLY works for getTitleInfo() and isKnownEmpty(), NOT FOR ANYTHING ELSE.
	 * In particular, it doesn't work for getContent() or getScript() etc.
	 *
	 * @return IDatabase|null
	 */
	protected function getDB() {
		return wfGetDB( DB_SLAVE );
	}

	/**
	 * @param string $title
	 * @return null|string
	 */
	protected function getContent( $titleText ) {
		$title = Title::newFromText( $titleText );
		if ( !$title ) {
			return null;
		}

		$handler = ContentHandler::getForTitle( $title );
		if ( $handler->isSupportedFormat( CONTENT_FORMAT_CSS ) ) {
			$format = CONTENT_FORMAT_CSS;
		} elseif ( $handler->isSupportedFormat( CONTENT_FORMAT_JAVASCRIPT ) ) {
			$format = CONTENT_FORMAT_JAVASCRIPT;
		} else {
			return null;
		}

		$revision = Revision::newFromTitle( $title, false, Revision::READ_NORMAL );
		if ( !$revision ) {
			return null;
		}

		$content = $revision->getContent( Revision::RAW );

		if ( !$content ) {
			wfDebugLog( 'resourceloader', __METHOD__ . ': failed to load content of JS/CSS page!' );
			return null;
		}

		return $content->serialize( $format );
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return string
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
			// Includes SHA1 of content
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
				if ( $revision['rev_len'] > 0 ) {
					// At least one non-empty page, module should be loaded
					return false;
				}
			}
			return true;
		}

		// Bug 68488: For other modules (i.e. ones that are called in cached html output) only check
		// page existance. This ensures that, if some pages in a module are temporarily blanked,
		// we don't end omit the module's script or link tag on some pages.
		return count( $revisions ) === 0;
	}

	/**
	 * Get the information about the wiki pages for a given context.
	 * @param ResourceLoaderContext $context
	 * @return array Keyed by page name. Contains arrays with 'rev_len' and 'rev_sha1' keys
	 */
	protected function getTitleInfo( ResourceLoaderContext $context ) {
		$dbr = $this->getDB();
		if ( !$dbr ) {
			// We're dealing with a subclass that doesn't have a DB
			return [];
		}

		$pages = $this->getPages( $context );
		$key = implode( '|', array_keys( $pages ) );
		if ( !isset( $this->titleInfo[$key] ) ) {
			$this->titleInfo[$key] = [];
			$batch = new LinkBatch;
			foreach ( $pages as $titleText => $options ) {
				$batch->addObj( Title::newFromText( $titleText ) );
			}

			if ( !$batch->isEmpty() ) {
				$res = $dbr->select( [ 'page', 'revision' ],
					// Include page_touched to allow purging if cache is poisoned (T117587, T113916)
					[ 'page_namespace', 'page_title', 'page_touched', 'rev_len', 'rev_sha1' ],
					$batch->constructSet( 'page', $dbr ),
					__METHOD__,
					[],
					[ 'revision' => [ 'INNER JOIN', [ 'page_latest=rev_id' ] ] ]
				);
				foreach ( $res as $row ) {
					// Avoid including ids or timestamps of revision/page tables so
					// that versions are not wasted
					$title = Title::makeTitle( $row->page_namespace, $row->page_title );
					$this->titleInfo[$key][$title->getPrefixedText()] = [
						'rev_len' => $row->rev_len,
						'rev_sha1' => $row->rev_sha1,
						'page_touched' => $row->page_touched,
					];
				}
			}
		}
		return $this->titleInfo[$key];
	}

	public function getPosition() {
		return $this->position;
	}
}
