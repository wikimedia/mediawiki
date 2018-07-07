<?php
/**
 * A class to check request restrictions expressed as a JSON object
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
 */

/**
 * A class to check request restrictions expressed as a JSON object
 */
class MWRestrictions {

	private $ipAddresses = [ '0.0.0.0/0', '::/0' ];

	/**
	 * @param array|null $restrictions
	 * @throws InvalidArgumentException
	 */
	protected function __construct( array $restrictions = null ) {
		if ( $restrictions !== null ) {
			$this->loadFromArray( $restrictions );
		}
	}

	/**
	 * @return MWRestrictions
	 */
	public static function newDefault() {
		return new self();
	}

	/**
	 * @param array $restrictions
	 * @return MWRestrictions
	 * @throws InvalidArgumentException
	 */
	public static function newFromArray( array $restrictions ) {
		return new self( $restrictions );
	}

	/**
	 * @param string $json JSON representation of the restrictions
	 * @return MWRestrictions
	 * @throws InvalidArgumentException
	 */
	public static function newFromJson( $json ) {
		$restrictions = FormatJson::decode( $json, true );
		if ( !is_array( $restrictions ) ) {
			throw new InvalidArgumentException( 'Invalid restrictions JSON' );
		}
		return new self( $restrictions );
	}

	private function loadFromArray( array $restrictions ) {
		static $validKeys = [ 'IPAddresses' ];
		static $neededKeys = [ 'IPAddresses' ];

		$keys = array_keys( $restrictions );
		$invalidKeys = array_diff( $keys, $validKeys );
		if ( $invalidKeys ) {
			throw new InvalidArgumentException(
				'Array contains invalid keys: ' . implode( ', ', $invalidKeys )
			);
		}
		$missingKeys = array_diff( $neededKeys, $keys );
		if ( $missingKeys ) {
			throw new InvalidArgumentException(
				'Array is missing required keys: ' . implode( ', ', $missingKeys )
			);
		}

		if ( !is_array( $restrictions['IPAddresses'] ) ) {
			throw new InvalidArgumentException( 'IPAddresses is not an array' );
		}
		foreach ( $restrictions['IPAddresses'] as $ip ) {
			if ( !\IP::isIPAddress( $ip ) ) {
				throw new InvalidArgumentException( "Invalid IP address: $ip" );
			}
		}
		$this->ipAddresses = $restrictions['IPAddresses'];
	}

	/**
	 * Return the restrictions as an array
	 * @return array
	 */
	public function toArray() {
		return [
			'IPAddresses' => $this->ipAddresses,
		];
	}

	/**
	 * Return the restrictions as a JSON string
	 * @param bool|string $pretty Pretty-print the JSON output, see FormatJson::encode
	 * @return string
	 */
	public function toJson( $pretty = false ) {
		return FormatJson::encode( $this->toArray(), $pretty, FormatJson::ALL_OK );
	}

	public function __toString() {
		return $this->toJson();
	}

	/**
	 * Test against the passed WebRequest
	 * @param WebRequest $request
	 * @return Status
	 */
	public function check( WebRequest $request ) {
		$ok = [
			'ip' => $this->checkIP( $request->getIP() ),
		];
		$status = Status::newGood();
		$status->setResult( $ok === array_filter( $ok ), $ok );
		return $status;
	}

	/**
	 * Test an IP address
	 * @param string $ip
	 * @return bool
	 */
	public function checkIP( $ip ) {
		foreach ( $this->ipAddresses as $range ) {
			if ( \IP::isInRange( $ip, $range ) ) {
				return true;
			}
		}

		return false;
	}
}
