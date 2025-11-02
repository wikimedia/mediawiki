<?php
/**
 * @license GPL-2.0-or-later
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
