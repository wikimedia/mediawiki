<?php
/**
 * Classes used to send e-mails
 *
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
 * @author <brion@pobox.com>
 * @author <mail@tgries.de>
 * @author Tim Starling
 * @author Luke Welling lwelling@wikimedia.org
 */

/**
 * Stores a single person's name and email address.
 * These are passed in via the constructor, and will be returned in SMTP
 * header format when requested.
 *
 * @newable
 */
class MailAddress {
	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $realName;

	/**
	 * @var string
	 */
	public $address;

	/**
	 * @stable to call
	 *
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
	 * Create a new MailAddress object for the given user
	 *
	 * @since 1.24
	 * @param User $user
	 * @return MailAddress
	 */
	public static function newFromUser( User $user ) {
		return new MailAddress( $user->getEmail(), $user->getName(), $user->getRealName() );
	}

	/**
	 * Return formatted and quoted address to insert into SMTP headers
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
