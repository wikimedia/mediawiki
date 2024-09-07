<?php

namespace MediaWiki\ResourceLoader;

use MediaWiki\MediaWikiServices;
use MediaWiki\User\Options\UserOptionsLookup;

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
 * Module for per-user private data that is transmitted on all HTML web responses.
 *
 * This module is embedded by ClientHtml and sent to the browser
 * by OutputPage as part of the HTML `<head>`.
 *
 * @ingroup ResourceLoader
 * @internal
 */
class UserOptionsModule extends Module {

	/** @inheritDoc */
	protected $origin = self::ORIGIN_CORE_INDIVIDUAL;

	/**
	 * @param Context $context
	 * @return string JavaScript code
	 */
	public function getScript( Context $context ) {
		$user = $context->getUserObj();

		$tokens = [
			// Replacement is tricky - T287542
			'patrolToken' => $user->getEditToken( 'patrol' ),
			'watchToken' => $user->getEditToken( 'watch' ),
			'csrfToken' => $user->getEditToken(),
		];
		$script = 'mw.user.tokens.set(' . $context->encodeJson( $tokens ) . ');' . "\n";

		$userOptionsLookup = MediaWikiServices::getInstance()->getUserOptionsLookup();

		// Optimisation: Exclude the defaults, which we load separately and allow the browser
		// to cache across page views. The defaults are loaded before this code executes,
		// as part of the "mediawiki.base" module.
		$options = $userOptionsLookup->getOptions( $user, UserOptionsLookup::EXCLUDE_DEFAULTS );

		$keysToExclude = [];
		$this->getHookRunner()->onResourceLoaderExcludeUserOptions( $keysToExclude, $context );
		foreach ( $keysToExclude as $excludedKey ) {
			unset( $options[ $excludedKey ] );
		}

		// Optimisation: Only output this function call if the user has non-default settings.
		if ( $options ) {
			$script .= 'mw.user.options.set(' . $context->encodeJson( $options ) . ');' . "\n";
		}

		return $script;
	}

	/**
	 * @return bool
	 */
	public function supportsURLLoading() {
		return false;
	}

	/**
	 * @return string
	 */
	public function getGroup() {
		return self::GROUP_PRIVATE;
	}
}
