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
 */

/**
 * Module for user customizations
 */
class ResourceLoaderUserGroupsModule extends ResourceLoaderWikiModule {

	protected $origin = self::ORIGIN_USER_SITEWIDE;
	protected $targets = array( 'desktop', 'mobile' );

	/**
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	protected function getPages( ResourceLoaderContext $context ) {
		$useSiteJs = $this->getConfig()->get( 'UseSiteJs' );
		$useSiteCss = $this->getConfig()->get( 'UseSiteCss' );
		if ( !$useSiteJs && !$useSiteCss ) {
			return array();
		}

		$user = $context->getUserObj();
		if ( !$user || $user->isAnon() ) {
			return array();
		}

		$pages = array();
		foreach ( $user->getEffectiveGroups() as $group ) {
			if ( $group == '*' ) {
				continue;
			}
			if ( $useSiteJs ) {
				$pages["MediaWiki:Group-$group.js"] = array( 'type' => 'script' );
			}
			if ( $useSiteCss ) {
				$pages["MediaWiki:Group-$group.css"] = array( 'type' => 'style' );
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
		return 'user';
	}
}
