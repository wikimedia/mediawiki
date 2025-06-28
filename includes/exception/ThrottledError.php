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

namespace MediaWiki\Exception;

/**
 * Show an error when the user hits a rate limit.
 *
 * @newable
 * @since 1.18
 * @ingroup Exception
 */
class ThrottledError extends ErrorPageError {

	/**
	 * @stable to call
	 */
	public function __construct() {
		parent::__construct(
			'actionthrottled',
			'actionthrottledtext'
		);
	}

	/** @inheritDoc */
	public function report( $action = ErrorPageError::SEND_OUTPUT ) {
		global $wgOut;
		$wgOut->setStatusCode( 429 );
		parent::report( $action );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ThrottledError::class, 'ThrottledError' );
