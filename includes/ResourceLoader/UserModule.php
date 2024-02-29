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

namespace MediaWiki\ResourceLoader;

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\TitleValue;

/**
 * Module for user customizations scripts.
 *
 * @ingroup ResourceLoader
 * @internal
 */
class UserModule extends WikiModule {
	protected $origin = self::ORIGIN_USER_INDIVIDUAL;

	/**
	 * @param Context $context
	 * @return array[]
	 */
	protected function getPages( Context $context ) {
		$user = $context->getUserIdentity();
		$tempUserConfig = MediaWikiServices::getInstance()->getTempUserConfig();
		if ( !$user || !$user->isRegistered() || $tempUserConfig->isTempName( $user->getName() ) ) {
			return [];
		}

		$config = $this->getConfig();
		$pages = [];

		if ( $config->get( MainConfigNames::AllowUserJs ) ) {
			$titleFormatter = MediaWikiServices::getInstance()->getTitleFormatter();
			// Use localised/normalised variant to ensure $excludepage matches
			$userPage = $titleFormatter->getPrefixedDBkey( new TitleValue( NS_USER, $user->getName() ) );
			$pages["$userPage/common.js"] = [ 'type' => 'script' ];
			$pages["$userPage/" . $context->getSkin() . '.js'] = [ 'type' => 'script' ];
		}

		// User group pages are maintained site-wide and enabled with site JS/CSS.
		if ( $config->get( MainConfigNames::UseSiteJs ) ) {
			$userGroupManager = MediaWikiServices::getInstance()->getUserGroupManager();
			foreach ( $userGroupManager->getUserEffectiveGroups( $user ) as $group ) {
				if ( $group == '*' ) {
					continue;
				}
				$pages["MediaWiki:Group-$group.js"] = [ 'type' => 'script' ];
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
		return self::GROUP_USER;
	}
}
