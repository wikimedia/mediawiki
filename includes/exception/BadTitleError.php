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

/**
 * Show an error page on a badtitle.
 *
 * Uses BadRequestError to emit a 400 HTTP error code to ensure caching proxies and
 * mobile browsers know not to cache it as valid content. (T35646)
 *
 * @newable
 * @since 1.19
 * @ingroup Exception
 */
class BadTitleError extends BadRequestError {
	/**
	 * @stable to call
	 *
	 * @param string|Message|MalformedTitleException $msg A message key (default: 'badtitletext'), or
	 *     a MalformedTitleException to figure out things from
	 * @param array $params Parameter to wfMessage()
	 */
	public function __construct( $msg = 'badtitletext', $params = [] ) {
		if ( $msg instanceof MalformedTitleException ) {
			$errorMessage = $msg->getErrorMessage();
			if ( !$errorMessage ) {
				parent::__construct( 'badtitle', 'badtitletext', [] );
			} else {
				$errorMessageParams = $msg->getErrorMessageParameters();
				parent::__construct( 'badtitle', $errorMessage, $errorMessageParams );
			}
		} else {
			parent::__construct( 'badtitle', $msg, $params );
		}
	}
}
