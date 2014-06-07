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
abstract class ResourceLoaderWikiModule extends ResourceLoaderModule {

	/* Protected Members */

	# Origin is user-supplied code
	protected $origin = self::ORIGIN_USER_SITEWIDE;

	// In-object cache for title mtimes
	protected $titleMtimes = array();

	/* Abstract Protected Methods */

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
	 * @param $context ResourceLoaderContext
	 * @return array
	 */
	abstract protected function getPages( ResourceLoaderContext $context );

	/* Protected Methods */

	/**
	 * Get the Database object used in getTitleMTimes(). Defaults to the local slave DB
	 * but subclasses may want to override this to return a remote DB object, or to return
	 * null if getTitleMTimes() shouldn't access the DB at all.
	 *
	 * NOTE: This ONLY works for getTitleMTimes() and getModifiedTime(), NOT FOR ANYTHING ELSE.
	 * In particular, it doesn't work for getting the content of JS and CSS pages. That functionality
	 * will use the local DB irrespective of the return value of this method.
	 *
	 * @return DatabaseBase|null
	 */
	protected function getDB() {
		return wfGetDB( DB_SLAVE );
	}

	/**
	 * @param $title Title
	 * @return null|string
	 */
	protected function getContent( $title ) {
		if ( !$title->isCssJsSubpage() && !$title->isCssOrJsPage() ) {
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

		$model = $content->getModel();

		if ( $model !== CONTENT_MODEL_CSS && $model !== CONTENT_MODEL_JAVASCRIPT ) {
			wfDebugLog( 'resourceloader', __METHOD__ . ': bad content model $model for JS/CSS page!' );
			return null;
		}

		return $content->getNativeData(); //NOTE: this is safe, we know it's JS or CSS
	}

	/* Methods */

	/**
	 * @param $context ResourceLoaderContext
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
				if ( strpos( $titleText, '*/' ) === false ) {
					$scripts .= "/* $titleText */\n";
				}
				$scripts .= $script . "\n";
			}
		}
		return $scripts;
	}

	/**
	 * @param $context ResourceLoaderContext
	 * @return array
	 */
	public function getStyles( ResourceLoaderContext $context ) {
		global $wgScriptPath;

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
			$style = CSSMin::remap( $style, false, $wgScriptPath, true );
			if ( !isset( $styles[$media] ) ) {
				$styles[$media] = array();
			}
			if ( strpos( $titleText, '*/' ) === false ) {
				$style = "/* $titleText */\n" . $style;
			}
			$styles[$media][] = $style;
		}
		return $styles;
	}

	/**
	 * @param $context ResourceLoaderContext
	 * @return int|mixed
	 */
	public function getModifiedTime( ResourceLoaderContext $context ) {
		$modifiedTime = 1; // wfTimestamp() interprets 0 as "now"
		$mtimes = $this->getTitleMtimes( $context );
		if ( count( $mtimes ) ) {
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
	 * @return Array
	 */
	public function getDefinitionSummary( ResourceLoaderContext $context ) {
		return array(
			'class' => get_class( $this ),
			'pages' => $this->getPages( $context ),
		);
	}

	/**
	 * @param $context ResourceLoaderContext
	 * @return bool
	 */
	public function isKnownEmpty( ResourceLoaderContext $context ) {
		return count( $this->getTitleMtimes( $context ) ) == 0;
	}

	/**
	 * Get the modification times of all titles that would be loaded for
	 * a given context.
	 * @param $context ResourceLoaderContext: Context object
	 * @return array( prefixed DB key => UNIX timestamp ), nonexistent titles are dropped
	 */
	protected function getTitleMtimes( ResourceLoaderContext $context ) {
		$dbr = $this->getDB();
		if ( !$dbr ) {
			// We're dealing with a subclass that doesn't have a DB
			return array();
		}

		$hash = $context->getHash();
		if ( isset( $this->titleMtimes[$hash] ) ) {
			return $this->titleMtimes[$hash];
		}

		$this->titleMtimes[$hash] = array();
		$batch = new LinkBatch;
		foreach ( $this->getPages( $context ) as $titleText => $options ) {
			$batch->addObj( Title::newFromText( $titleText ) );
		}

		if ( !$batch->isEmpty() ) {
			$res = $dbr->select( 'page',
				array( 'page_namespace', 'page_title', 'page_touched' ),
				$batch->constructSet( 'page', $dbr ),
				__METHOD__
			);
			foreach ( $res as $row ) {
				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				$this->titleMtimes[$hash][$title->getPrefixedDBkey()] =
					wfTimestamp( TS_UNIX, $row->page_touched );
			}
		}
		return $this->titleMtimes[$hash];
	}
}
