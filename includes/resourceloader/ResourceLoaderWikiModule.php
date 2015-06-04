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
	/** @var string Position on the page to load this module at */
	protected $position = 'bottom';

	// Origin defaults to users with sitewide authority
	protected $origin = self::ORIGIN_USER_SITEWIDE;

	// In-object cache for page content
	protected $pageContent = array();

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
		if ( is_null( $options ) ) {
			return;
		}

		foreach ( $options as $member => $option ) {
			switch ( $member ) {
				case 'position':
					$this->isPositionDefined = true;
					// Don't break since we need the member set as well
				case 'styles':
				case 'scripts':
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
	 * @param string $title
	 * @return false|string
	 */
	protected function getContent( $titleText ) {
		$title = Title::newFromText( $titleText );
		if ( !$title || $title->isRedirect() ) {
			return false;
		}

		if ( !isset( $this->pageContent[$titleText] ) ) {
			$this->pageContent[$titleText] = false;

			$handler = ContentHandler::getForTitle( $title );
			if ( $handler->isSupportedFormat( CONTENT_FORMAT_CSS ) ) {
				$format = CONTENT_FORMAT_CSS;
			} elseif ( $handler->isSupportedFormat( CONTENT_FORMAT_JAVASCRIPT ) ) {
				$format = CONTENT_FORMAT_JAVASCRIPT;
			} else {
				return false;
			}

			$revision = Revision::newFromTitle( $title, false, Revision::READ_NORMAL );
			if ( !$revision ) {
				return false;
			}

			$content = $revision->getContent( Revision::RAW );

			if ( !$content ) {
				wfDebugLog( 'resourceloader', __METHOD__ . ': failed to load content of JS/CSS page!' );
				return false;
			}

			$this->pageContent[$titleText] = $content->serialize( $format );
		}

		return $this->pageContent[$titleText];
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
		$styles = array();
		foreach ( $this->getPages( $context ) as $titleText => $options ) {
			if ( $options['type'] !== 'style' ) {
				continue;
			}
			$style = $this->getContent( $titleText );
			$media = isset( $options['media'] ) ? $options['media'] : 'all';
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
	 * @return array
	 */
	public function getDefinitionSummary( ResourceLoaderContext $context ) {
		$summary = parent::getDefinitionSummary( $context );
		$summary[] = array(
			'pages' => $this->getPages( $context ),
			'content' => $this->getPagesContent( $context ),
		);
		return $summary;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return bool
	 */
	public function isKnownEmpty( ResourceLoaderContext $context ) {
		$pages = $this->getPagesContent( $context );

		// For user modules, don't needlessly load if there are no non-empty pages
		if ( $this->getGroup() === 'user' ) {
			foreach ( $pages as $content ) {
				if ( $content !== '' ) {
					// At least one non-empty page, module should be loaded
					return false;
				}
			}
			return true;
		}

		// Bug 68488: For other modules (i.e. ones that are called in cached html output) only check
		// page existance. This ensures that, if some pages in a module are temporarily blanked,
		// we don't end omit the module's script or link tag on some pages.
		return count( $pages ) === 0;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	protected function getPagesContent( ResourceLoaderContext $context ) {
		$pages = array();
		foreach ( $this->getPages( $context ) as $titleText => $options ) {
			if ( $options['type'] === 'style' || $options['type'] === 'script' ) {
				$content = $this->getContent( $titleText );
				if ( $content !== false ) {
					$pages[$titleText] = $content;
				}
			}
		}
		return $pages;
	}

	public function getPosition() {
		return $this->position;
	}
}
