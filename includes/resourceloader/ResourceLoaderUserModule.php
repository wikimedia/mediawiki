<?php
/**
 * Resource loader module for user customizations.
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
 * Module for user customizations
 */
class ResourceLoaderUserModule extends ResourceLoaderWikiModule {

	/* Protected Methods */
	protected $origin = self::ORIGIN_USER_INDIVIDUAL;

	/**
	 * @param $context ResourceLoaderContext
	 * @return array
	 */
	protected function getPages( ResourceLoaderContext $context ) {
		global $wgAllowUserJs, $wgAllowUserCss;
		$username = $context->getUser();

		if ( $username === null ) {
			return array();
		}
		if ( !$wgAllowUserJs && !$wgAllowUserCss ) {
			return array();
		}

		// Get the normalized title of the user's user page
		$userpageTitle = Title::makeTitleSafe( NS_USER, $username );

		if ( !$userpageTitle instanceof Title ) {
			return array();
		}

		$userpage = $userpageTitle->getPrefixedDBkey(); // Needed so $excludepages works

		$pages = array();
		if ( $wgAllowUserJs ) {
			$pages["$userpage/common.js"] = array( 'type' => 'script' );
			$pages["$userpage/" . $context->getSkin() . '.js'] = array( 'type' => 'script' );
		}
		if ( $wgAllowUserCss ) {
			$pages["$userpage/common.css"] = array( 'type' => 'style' );
			$pages["$userpage/" . $context->getSkin() . '.css'] = array( 'type' => 'style' );
		}

		// Hack for bug 26283: if we're on a preview page for a CSS/JS page,
		// we need to exclude that page from this module. In that case, the excludepage
		// parameter will be set to the name of the page we need to exclude.
		$excludepage = $context->getRequest()->getVal( 'excludepage' );
		if ( isset( $pages[$excludepage] ) ) {
			// This works because $excludepage is generated with getPrefixedDBkey(),
			// just like the keys in $pages[] above
			unset( $pages[$excludepage] );
		}
		return $pages;
	}

	/* Methods */

	/**
	 * @return string
	 */
	public function getGroup() {
		return 'user';
	}
}
