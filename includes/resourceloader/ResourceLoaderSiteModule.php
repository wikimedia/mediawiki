<?php
/**
 * Resource loader module for site customizations.
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
 * Module for site customizations
 */
class ResourceLoaderSiteModule extends ResourceLoaderWikiModule {

	/* Protected Methods */

	/**
	 * Gets list of pages used by this module
	 *
	 * @param ResourceLoaderContext $context
	 *
	 * @return array List of pages
	 */
	protected function getPages( ResourceLoaderContext $context ) {
		$pages = array();
		if ( $this->getConfig()->get( 'UseSiteJs' ) ) {
			$pages['MediaWiki:Common.js'] = array( 'type' => 'script' );
			$pages['MediaWiki:' . ucfirst( $context->getSkin() ) . '.js'] = array( 'type' => 'script' );
		}
		if ( $this->getConfig()->get( 'UseSiteCss' ) ) {
			$pages['MediaWiki:Common.css'] = array( 'type' => 'style' );
			$pages['MediaWiki:' . ucfirst( $context->getSkin() ) . '.css'] = array( 'type' => 'style' );

		}
		$pages['MediaWiki:Print.css'] = array( 'type' => 'style', 'media' => 'print' );
		return $pages;
	}

	/* Methods */

	/**
	 * @param $context ResourceLoaderContext
	 * @return array
	 */
	public function getStyles( ResourceLoaderContext $context ) {
		global $wgLogo;

		$styles = parent::getStyles( $context );
		$styles['all'][] = '.mw-wiki-logo { background-image: ' .
			CSSMin::buildUrlValue( $wgLogo ) .
			'; }';

		return $styles;
	}

	/**
	 * @param $context ResourceLoaderContext
	 * @return boolean
	 */
	public function isKnownEmpty( ResourceLoaderContext $context ) {
		// Regardless of whether the wiki page(s) exist, we always
		// provide mw-wiki-logo styles.
		return false;
	}

	/**
	 * @param $context ResourceLoaderContext
	 * @return int|mixed
	 */
	public function getModifiedTime( ResourceLoaderContext $context ) {
		$parentMTime = parent::getModifiedTime( $context );
		return max( $parentMTime, $this->getHashMtime( $context ) );
	}

	/**
	 * @param $context ResourceLoaderContext
	 * @return string: Hash
	 */
	public function getModifiedHash( ResourceLoaderContext $context ) {
		global $wgLogo;
		return md5( parent::getModifiedHash( $context ) . $wgLogo );
	}

	/**
	 * Gets group name
	 *
	 * @return string Name of group
	 */
	public function getGroup() {
		return 'site';
	}
}
