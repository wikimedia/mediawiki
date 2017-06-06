<?php
/**
 * Implements the Pbkdf2Password class for the MediaWiki software.
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
 */

/**
 * A PBKDF2-hashed password
 *
 * This is a computationally complex password hash for use in modern applications.
 * The number of rounds can be configured by $wgPasswordConfig['pbkdf2']['cost'].
 *
 * @since 1.24
 */
class Pbkdf2Password extends ParameterizedPassword {
	protected function getDefaultParams() {
		return [
			'algo' => $this->config['algo'],
			'rounds' => $this->config['cost'],
			'length' => $this->config['length']
		];
	}

	protected function getDelimiter() {
		return ':';
	}

	protected function shouldUseHashExtension() {
		return isset( $this->config['use-hash-extension'] ) ?
			$this->config['use-hash-extension'] : function_exists( 'hash_pbkdf2' );
	}

	public function crypt( $password ) {
		if ( count( $this->args ) == 0 ) {
			$this->args[] = base64_encode( MWCryptRand::generate( 16, true ) );
		}

		if ( $this->shouldUseHashExtension() ) {
			$hash = hash_pbkdf2(
				$this->params['algo'],
				$password,
				base64_decode( $this->args[0] ),
				(int)$this->params['rounds'],
				(int)$this->params['length'],
				true
			);
			if ( !is_string( $hash ) ) {
				throw new PasswordError( 'Error when hashing password.' );
			}
		} else {
			$hashLenHash = hash( $this->params['algo'], '', true );
			if ( !is_string( $hashLenHash ) ) {
				throw new PasswordError( 'Error when hashing password.' );
			}
			$hashLen = strlen( $hashLenHash );
			$blockCount = ceil( $this->params['length'] / $hashLen );

			$hash = '';
			$salt = base64_decode( $this->args[0] );
			for ( $i = 1; $i <= $blockCount; ++$i ) {
				$roundTotal = $lastRound = hash_hmac(
					$this->params['algo'],
					$salt . pack( 'N', $i ),
					$password,
					true
				);

				for ( $j = 1; $j < $this->params['rounds']; ++$j ) {
					$lastRound = hash_hmac( $this->params['algo'], $lastRound, $password, true );
					$roundTotal ^= $lastRound;
				}

				$hash .= $roundTotal;
			}

			$hash = substr( $hash, 0, $this->params['length'] );
		}

		$this->hash = base64_encode( $hash );
	}
}
