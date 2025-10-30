<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author Brooke Vibber
 * @author <mail@tgries.de>
 * @author Tim Starling
 * @author Luke Welling lwelling@wikimedia.org
 */

namespace MediaWiki\Mail;

use Stringable;

/**
 * Represent and format a single name and email address pair for SMTP.
 *
 * Used by Emailer, e.g. via EmailUser.
 *
 * @newable
 * @since 1.6.0
 * @ingroup Mail
 */
class MailAddress implements Stringable {

	public string $name;
	public string $realName;
	public string $address;

	/**
	 * @stable to call
	 * @since 1.6.0
	 * @param string $address String with an email address
	 * @param string|null $name Human-readable name if a string address is given
	 * @param string|null $realName Human-readable real name if a string address is given
	 */
	public function __construct( $address, $name = null, $realName = null ) {
		$this->address = strval( $address );
		$this->name = strval( $name );
		$this->realName = strval( $realName );
	}

	/**
	 * @since 1.24
	 * @param UserEmailContact $user
	 * @return MailAddress
	 */
	public static function newFromUser( UserEmailContact $user ) {
		return new MailAddress( $user->getEmail(), $user->getUser()->getName(), $user->getRealName() );
	}

	/**
	 * @since 1.40
	 * @param self $other
	 * @return bool
	 */
	public function equals( self $other ): bool {
		return $this->address === $other->address &&
			$this->name === $other->name &&
			$this->realName === $other->realName;
	}

	/**
	 * Format and quote address for insertion in SMTP headers
	 *
	 * @since 1.6.0
	 * @return string
	 */
	public function toString() {
		if ( !$this->address ) {
			return '';
		}

		# PHP's mail() implementation under Windows is somewhat shite, and
		# can't handle "Joe Bloggs <joe@bloggs.com>" format email addresses,
		# so don't bother generating them
		if ( $this->name === '' || wfIsWindows() ) {
			return $this->address;
		}

		global $wgEnotifUseRealName;
		$name = ( $wgEnotifUseRealName && $this->realName !== '' ) ? $this->realName : $this->name;
		$quoted = UserMailer::quotedPrintable( $name );
		// Must only be quoted if string does not use =? encoding (T191931)
		if ( $quoted === $name ) {
			$quoted = '"' . addslashes( $quoted ) . '"';
		}

		return "$quoted <{$this->address}>";
	}

	public function __toString() {
		return $this->toString();
	}
}

/** @deprecated class alias since 1.45 */
class_alias( MailAddress::class, 'MailAddress' );
