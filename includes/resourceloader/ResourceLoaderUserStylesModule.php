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
 * Module for user customizations styles.
 *
 * @ingroup ResourceLoader
 * @internal
 */
class ResourceLoaderUserStylesModule extends ResourceLoaderWikiModule {

	protected $origin = self::ORIGIN_USER_INDIVIDUAL;
	protected $targets = [ 'desktop', 'mobile' ];

	/**
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

		if ( $config->get( 'AllowUserCss' ) ) {
			$pages["$userPage/common.css"] = [ 'type' => 'style' ];
			$pages["$userPage/" . $context->getSkin() . '.css'] = [ 'type' => 'style' ];
		}

		// User group pages are maintained site-wide and enabled with site JS/CSS.
		if ( $config->get( 'UseSiteCss' ) ) {
			foreach ( $user->getEffectiveGroups() as $group ) {
				if ( $group == '*' ) {
					continue;
				}
				$pages["MediaWiki:Group-$group.css"] = [ 'type' => 'style' ];
			}
		}

		// This is obsolete since 1.32 (T112474). It was formerly used by
		// OutputPage to implement previewing of user CSS and JS.
		// @todo: Remove it once we're sure nothing else is using the parameter
		$excludepage = $context->getRequest()->getVal( 'excludepage' );
		if ( isset( $pages[$excludepage] ) ) {
			unset( $pages[$excludepage] );
		}

		return $pages;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return self::LOAD_STYLES;
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
