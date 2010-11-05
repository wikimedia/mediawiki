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
 * because of it's dependence on the functionality of 
 * Title::isValidCssJsSubpage.
 */
abstract class ResourceLoaderWikiModule extends ResourceLoaderModule {
	
	/* Protected Members */
	
	// In-object cache for modified time
	protected $modifiedTime = array();
	
	/* Abstract Protected Methods */
	
	abstract protected function getPages( ResourceLoaderContext $context );
	
	/* Protected Methods */
	
	protected function getContent( $page, $ns ) {
		if ( $ns === NS_MEDIAWIKI ) {
			return wfEmptyMsg( $page ) ? '' : wfMsgExt( $page, 'content' );
		}
		$title = Title::newFromText( $page, $ns );
		if ( !$title || !$title->isValidCssJsSubpage() ) {
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
		foreach ( $this->getPages( $context ) as $page => $options ) {
			if ( $options['type'] !== 'script' ) {
				continue;
			}
			$script = $this->getContent( $page, $options['ns'] );
			if ( $script ) {
				$ns = MWNamespace::getCanonicalName( $options['ns'] );
				$scripts .= "/* $ns:$page */\n$script\n";
			}
		}
		return $scripts;
	}

	public function getStyles( ResourceLoaderContext $context ) {
		
		$styles = array();
		foreach ( $this->getPages( $context ) as $page => $options ) {
			if ( $options['type'] !== 'style' ) {
				continue;
			}
			$media = isset( $options['media'] ) ? $options['media'] : 'all';
			$style = $this->getContent( $page, $options['ns'] );
			if ( !$style ) {
				continue;
			}
			if ( !isset( $styles[$media] ) ) {
				$styles[$media] = '';
			}
			$ns = MWNamespace::getCanonicalName( $options['ns'] );
			$styles[$media] .= "/* $ns:$page */\n$style\n";
		}
		return $styles;
	}

	public function getModifiedTime( ResourceLoaderContext $context ) {
		$hash = $context->getHash();
		if ( isset( $this->modifiedTime[$hash] ) ) {
			return $this->modifiedTime[$hash];
		}

		$titles = array();
		foreach ( $this->getPages( $context ) as $page => $options ) {
			$titles[$options['ns']][$page] = true;
		}

		$modifiedTime = 1; // wfTimestamp() interprets 0 as "now"

		if ( $titles ) {
			$dbr = wfGetDB( DB_SLAVE );
			$latest = $dbr->selectField( 'page', 'MAX(page_touched)',
				$dbr->makeWhereFrom2d( $titles, 'page_namespace', 'page_title' ),
				__METHOD__ );

			if ( $latest ) {
				$modifiedTime = wfTimestamp( TS_UNIX, $latest );
			}
		}

		return $this->modifiedTime[$hash] = $modifiedTime;
	}
}
