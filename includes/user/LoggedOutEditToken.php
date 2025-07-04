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

namespace MediaWiki\User;

use MediaWiki\Session\Token;

/**
 * Value object representing a MediaWiki edit token for logged-out users.
 *
 * This exists so that code generically dealing with MediaWiki\Session\Token
 * (i.e. the API) doesn't have to have so many special cases for anon edit
 * tokens.
 *
 * @newable
 * @since 1.27
 * @ingroup Session
 */
class LoggedOutEditToken extends Token {

	/**
	 * @stable to call
	 */
	public function __construct() {
		parent::__construct( '', '', false );
	}

	/** @inheritDoc */
	protected function toStringAtTimestamp( $timestamp ) {
		return self::SUFFIX;
	}

	/** @inheritDoc */
	public function match( $userToken, $maxAge = null ) {
		return $userToken === self::SUFFIX;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( LoggedOutEditToken::class, 'LoggedOutEditToken' );
