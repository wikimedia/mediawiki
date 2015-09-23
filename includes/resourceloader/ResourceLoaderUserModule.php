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

	protected $origin = self::ORIGIN_USER_INDIVIDUAL;

	/**
	 * Get list of pages used by this module
	 *
	 * @param ResourceLoaderContext $context
	 * @return array List of pages
	 */
	protected function getPages( ResourceLoaderContext $context ) {
		$allowUserJs = $this->getConfig()->get( 'AllowUserJs' );
		$allowUserCss = $this->getConfig()->get( 'AllowUserCss' );
		if ( !$allowUserJs && !$allowUserCss ) {
			return array();
		}

		$user = $context->getUserObj();
		if ( !$user || $user->isAnon() ) {
			return array();
		}

		// Needed so $excludepages works
		$userPage = $user->getUserPage()->getPrefixedDBkey();

		$pages = array();
		if ( $allowUserJs ) {
			$pages["$userPage/common.js"] = array( 'type' => 'script' );
			$pages["$userPage/" . $context->getSkin() . '.js'] = array( 'type' => 'script' );
		}
		if ( $allowUserCss ) {
			$pages["$userPage/common.css"] = array( 'type' => 'style' );
			$pages["$userPage/" . $context->getSkin() . '.css'] = array( 'type' => 'style' );
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

	/**
	 * Get group name
	 *
	 * @return string
	 */
	public function getGroup() {
		return 'user';
	}
}
