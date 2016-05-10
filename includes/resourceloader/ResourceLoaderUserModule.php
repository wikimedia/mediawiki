<?php
/**
 * ResourceLoader module for user customizations.
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
	protected $targets = [ 'desktop', 'mobile' ];

	/**
	 * Get list of pages used by this module
	 *
	 * @param ResourceLoaderContext $context
	 * @return array List of pages
	 */
	protected function getPages( ResourceLoaderContext $context ) {
		$config = $this->getConfig();
		$user = $context->getUserObj();
		if ( $user->isAnon() ) {
			return [];
		}

		// Use localised/normalised variant to ensure $excludepage matches
		$userPage = $user->getUserPage()->getPrefixedDBkey();
		$pages = [];

		if ( $config->get( 'AllowUserJs' ) ) {
			$pages["$userPage/common.js"] = [ 'type' => 'script' ];
			$pages["$userPage/" . $context->getSkin() . '.js'] = [ 'type' => 'script' ];
		}

		if ( $config->get( 'AllowUserCss' ) ) {
			$pages["$userPage/common.css"] = [ 'type' => 'style' ];
			$pages["$userPage/" . $context->getSkin() . '.css'] = [ 'type' => 'style' ];
		}

		$useSiteJs = $this->getConfig()->get( 'UseSiteJs' );
		$useSiteCss = $this->getConfig()->get( 'UseSiteCss' );
		// User group pages are maintained site-wide and enabled with site JS/CSS.
		if ( $useSiteJs || $useSiteCss ) {
			foreach ( $user->getEffectiveGroups() as $group ) {
				if ( $group == '*' ) {
					continue;
				}
				if ( $useSiteJs ) {
					$pages["MediaWiki:Group-$group.js"] = [ 'type' => 'script' ];
				}
				if ( $useSiteCss ) {
					$pages["MediaWiki:Group-$group.css"] = [ 'type' => 'style' ];
				}
			}
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
