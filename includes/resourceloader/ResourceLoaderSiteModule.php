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

/**
 * Module for site customizations
 */
class ResourceLoaderSiteModule extends ResourceLoaderWikiModule {

	/* Protected Methods */

	/**
	 * Gets list of pages used by this module
	 * 
	 * @return Array: List of pages
	 */
	protected function getPages( ResourceLoaderContext $context ) {
		global $wgHandheldStyle;

		$pages = array(
			'MediaWiki:Common.js' => array( 'type' => 'script' ),
			'MediaWiki:Common.css' => array( 'type' => 'style' ),
			'MediaWiki:' . ucfirst( $context->getSkin() ) . '.js' => array( 'type' => 'script' ),
			'MediaWiki:' . ucfirst( $context->getSkin() ) . '.css' => array( 'type' => 'style' ),
			'MediaWiki:Print.css' => array( 'type' => 'style', 'media' => 'print' ),
		);
		if ( $wgHandheldStyle ) {
			$pages['MediaWiki:Handheld.css'] = array( 
				'type' => 'style', 
				'media' => 'handheld' );
		}
		return $pages;
	}

	/* Methods */

	/**
	 * Gets group name
	 * 
	 * @return String: Name of group
	 */
	public function getGroup() {
		return 'site';
	}
}
