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
 */

use MediaWiki\Block\AbstractBlock;
use MediaWiki\MediaWikiServices;

/**
 * Show an error when the user tries to do something whilst blocked.
 *
 * @newable
 * @since 1.18
 * @ingroup Exception
 */
class UserBlockedError extends ErrorPageError {
	/**
	 * @stable to call
	 * @param AbstractBlock $block
	 * @param User|null $user
	 * @param Language|null $language
	 * @param string|null $ip
	 */
	public function __construct(
		AbstractBlock $block,
		User $user = null,
		Language $language = null,
		$ip = null
	) {
		if ( $user === null || $language === null || $ip === null ) {
			// If any of these are not passed in, use the global context
			$context = RequestContext::getMain();
			$user = $context->getUser();
			$language = $context->getLanguage();
			$ip = $context->getRequest()->getIP();
		}

		// @todo This should be passed in via the constructor
		$message = MediaWikiServices::getInstance()->getBlockErrorFormatter()
			->getMessage( $block, $user, $language, $ip );
		parent::__construct( 'blockedtitle', $message );
	}
}
