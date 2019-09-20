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
 * @since 1.18
 * @ingroup Exception
 */
class UserBlockedError extends ErrorPageError {
	/**
	 * @param AbstractBlock $block
	 */
	public function __construct( AbstractBlock $block ) {
		// @todo FIXME: Implement a more proper way to get context here
		// such as passing the user, language and IP from the caller.
		$context = RequestContext::getMain();
		$user = $context->getUser();
		$language = $context->getLanguage();
		$ip = $context->getRequest()->getIp();

		$message = MediaWikiServices::getInstance()->getBlockErrorFormatter()
			->getMessage( $block, $user, $language, $ip );
		parent::__construct( 'blockedtitle', $message );
	}
}
