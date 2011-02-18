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

defined( 'MEDIAWIKI' ) || die( 1 );

/**
 * Abstraction for resource loader modules which pull from wiki pages
 * 
 * This can only be used for wiki pages in the MediaWiki and User namespaces, 
 * because of its dependence on the functionality of 
 * Title::isValidCssJsSubpage.
 */
abstract class ResourceLoaderWikiModule extends ResourceLoaderModule {
	
	/* Protected Members */

	# Origin is user-supplied code
	protected $origin = self::ORIGIN_USER_SITEWIDE;
	
	// In-object cache for modified time
	protected $modifiedTime = array();
	
	/* Abstract Protected Methods */
	
	abstract protected function getPages( ResourceLoaderContext $context );
	
	/* Protected Methods */

	/**
	 * @param $title Title
	 * @return null|string
	 */
	protected function getContent( $title ) {
		if ( $title->getNamespace() === NS_MEDIAWIKI ) {
			$dbkey = $title->getDBkey();
			return wfEmptyMsg( $dbkey ) ? '' : wfMsgExt( $dbkey, 'content' );
		}
		if ( !$title->isCssJsSubpage() ) {
			return null;
		}
		$revision = Revision::newFromTitle( $title );
		if ( !$revision ) {
			return null;
		}
		return $revision->getRawText();
	}
	
	/* Methods */

	public function getScript( ResourceLoaderContext $context ) {
		$scripts = '';
		foreach ( $this->getPages( $context ) as $titleText => $options ) {
			if ( $options['type'] !== 'script' ) {
				continue;
			}
			$title = Title::newFromText( $titleText );
			if ( !$title ) {
				continue;
			}
			$script = $this->getContent( $title );
			if ( strval( $script ) !== '' ) {
				if ( strpos( $titleText, '*/' ) === false ) {
					$scripts .=  "/* $titleText */\n";
				}
				$scripts .= $script . "\n";
			}
		}
		return $scripts;
	}

	public function getStyles( ResourceLoaderContext $context ) {
		global $wgScriptPath;
		
		$styles = array();
		foreach ( $this->getPages( $context ) as $titleText => $options ) {
			if ( $options['type'] !== 'style' ) {
				continue;
			}
			$title = Title::newFromText( $titleText );
			if ( !$title ) {
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
				$styles[$media] = '';
			}
			if ( strpos( $titleText, '*/' ) === false ) {
				$styles[$media] .=  "/* $titleText */\n";
			}
			$styles[$media] .= $style . "\n";
		}
		return $styles;
	}

	public function getModifiedTime( ResourceLoaderContext $context ) {
		$hash = $context->getHash();
		if ( isset( $this->modifiedTime[$hash] ) ) {
			return $this->modifiedTime[$hash];
		}

		$batch = new LinkBatch;
		foreach ( $this->getPages( $context ) as $titleText => $options ) {
			$batch->addObj( Title::newFromText( $titleText ) );
		}

		$modifiedTime = 1; // wfTimestamp() interprets 0 as "now"
		if ( !$batch->isEmpty() ) {
			$dbr = wfGetDB( DB_SLAVE );
			$latest = $dbr->selectField( 'page', 'MAX(page_touched)',
				$batch->constructSet( 'page', $dbr ),
				__METHOD__ );

			if ( $latest ) {
				$modifiedTime = wfTimestamp( TS_UNIX, $latest );
			}
		}

		$this->modifiedTime[$hash] = $modifiedTime;
		return $modifiedTime;
	}

	/**
	 * @param $context ResourceLoaderContext
	 * @return bool
	 */
	public function getFlip( $context ) {
		global $wgContLang;

		return $wgContLang->getDir() !== $context->getDirection();
	}
}
