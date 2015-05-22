<?php
/**
 * Abstraction for resource loader modules which pull from wiki pages.
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
 * Abstraction for resource loader modules which pull from wiki pages
 *
 * This can only be used for wiki pages in the MediaWiki and User namespaces,
 * because of its dependence on the functionality of
 * Title::isCssJsSubpage.
 */
class ResourceLoaderWikiModule extends ResourceLoaderModule {

	// Origin defaults to users with sitewide authority
	protected $origin = self::ORIGIN_USER_SITEWIDE;

	// In-object cache for title info
	protected $titleInfo = array();

	// List of page names that contain CSS
	protected $styles = array();

	// List of page names that contain JavaScript
	protected $scripts = array();

	// Group of module
	protected $group;

	/**
	 * @param array $options For back-compat, this can be omitted in favour of overwriting getPages.
	 */
	public function __construct( array $options = null ) {
		if ( isset( $options['styles'] ) ) {
			$this->styles = $options['styles'];
		}
		if ( isset( $options['scripts'] ) ) {
			$this->scripts = $options['scripts'];
		}
		if ( isset( $options['group'] ) ) {
			$this->group = $options['group'];
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
		$pages = array();

		// Filter out pages from origins not allowed by the current wiki configuration.
		if ( $config->get( 'UseSiteJs' ) ) {
			foreach ( $this->scripts as $script ) {
				$pages[$script] = array( 'type' => 'script' );
			}
		}

		if ( $config->get( 'UseSiteCss' ) ) {
			foreach ( $this->styles as $style ) {
				$pages[$style] = array( 'type' => 'style' );
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
	 * Get the Database object used in getTitleMTimes(). Defaults to the local slave DB
	 * but subclasses may want to override this to return a remote DB object, or to return
	 * null if getTitleMTimes() shouldn't access the DB at all.
	 *
	 * NOTE: This ONLY works for getTitleMTimes() and getModifiedTime(), NOT FOR ANYTHING ELSE.
	 * In particular, it doesn't work for getting the content of JS and CSS pages. That functionality
	 * will use the local DB irrespective of the return value of this method.
	 *
	 * @return IDatabase|null
	 */
	protected function getDB() {
		return wfGetDB( DB_SLAVE );
	}

	/**
	 * @param Title $title
	 * @return null|string
	 */
	protected function getContent( $title ) {
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
			$title = Title::newFromText( $titleText );
			if ( !$title || $title->isRedirect() ) {
				continue;
			}
			$script = $this->getContent( $title );
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
		$styles = array();
		foreach ( $this->getPages( $context ) as $titleText => $options ) {
			if ( $options['type'] !== 'style' ) {
				continue;
			}
			$title = Title::newFromText( $titleText );
			if ( !$title || $title->isRedirect() ) {
				continue;
			}
			$media = isset( $options['media'] ) ? $options['media'] : 'all';
			$style = $this->getContent( $title );
			if ( strval( $style ) === '' ) {
				continue;
			}
			if ( $this->getFlip( $context ) ) {
				$style = CSSJanus::transform( $style, true, false );
			}
			$style = CSSMin::remap( $style, false, $this->getConfig()->get( 'ScriptPath' ), true );
			if ( !isset( $styles[$media] ) ) {
				$styles[$media] = array();
			}
			$style = ResourceLoader::makeComment( $titleText ) . $style;
			$styles[$media][] = $style;
		}
		return $styles;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return int
	 */
	public function getModifiedTime( ResourceLoaderContext $context ) {
		$modifiedTime = 1;
		$titleInfo = $this->getTitleInfo( $context );
		if ( count( $titleInfo ) ) {
			$mtimes = array_map( function ( $value ) {
				return $value['timestamp'];
			}, $titleInfo );
			$modifiedTime = max( $modifiedTime, max( $mtimes ) );
		}
		$modifiedTime = max(
			$modifiedTime,
			$this->getMsgBlobMtime( $context->getLanguage() ),
			$this->getDefinitionMtime( $context )
		);
		return $modifiedTime;
	}

	/**
	 * Get the definition summary for this module.
	 *
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getDefinitionSummary( ResourceLoaderContext $context ) {
		return array(
			'class' => get_class( $this ),
			'pages' => $this->getPages( $context ),
		);
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return bool
	 */
	public function isKnownEmpty( ResourceLoaderContext $context ) {
		$titleInfo = $this->getTitleInfo( $context );
		// Bug 68488: For modules in the "user" group, we should actually
		// check that the pages are empty (page_len == 0), but for other
		// groups, just check the pages exist so that we don't end up
		// caching temporarily-blank pages without the appropriate
		// <script> or <link> tag.
		if ( $this->getGroup() !== 'user' ) {
			return count( $titleInfo ) === 0;
		}

		foreach ( $titleInfo as $info ) {
			if ( $info['length'] !== 0 ) {
				// At least one non-0-lenth page, not empty
				return false;
			}
		}

		// All pages are 0-length, so it's empty
		return true;
	}

	/**
	 * Get the modification times of all titles that would be loaded for
	 * a given context.
	 * @param ResourceLoaderContext $context Context object
	 * @return array Keyed by page dbkey. Value is an array with 'length' and 'timestamp'
	 *               keys, where the timestamp is a UNIX timestamp
	 */
	protected function getTitleInfo( ResourceLoaderContext $context ) {
		$dbr = $this->getDB();
		if ( !$dbr ) {
			// We're dealing with a subclass that doesn't have a DB
			return array();
		}

		$hash = $context->getHash();
		if ( isset( $this->titleInfo[$hash] ) ) {
			return $this->titleInfo[$hash];
		}

		$this->titleInfo[$hash] = array();
		$batch = new LinkBatch;
		foreach ( $this->getPages( $context ) as $titleText => $options ) {
			$batch->addObj( Title::newFromText( $titleText ) );
		}

		if ( !$batch->isEmpty() ) {
			$res = $dbr->select( 'page',
				array( 'page_namespace', 'page_title', 'page_touched', 'page_len' ),
				$batch->constructSet( 'page', $dbr ),
				__METHOD__
			);
			foreach ( $res as $row ) {
				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				$this->titleInfo[$hash][$title->getPrefixedDBkey()] = array(
					'timestamp' => wfTimestamp( TS_UNIX, $row->page_touched ),
					'length' => $row->page_len,
				);
			}
		}
		return $this->titleInfo[$hash];
	}
}
