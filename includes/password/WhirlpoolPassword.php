<?php
/**
 * Implements the WhirlpoolPassword class for the MediaWiki software.
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
 * A Whirlpool-based password hashing. Implements the Whirlpool bits from Tim's
 * algorithm described in http://www.mail-archive.com/wikitech-l@lists.wikimedia.org/msg08830.html
 *
 * @since 1.23
 */
class WhirlpoolPassword extends ParameterizedPassword {
	function getDefaultParams() {
		return array(
			'complexity' => $this->config['cost'],
		);
	}

	function getDelimiter() {
		return ':';
	}

	function parseHash( $hash ) {
		parent::parseHash( $hash );
		$this->params['complexity'] = (int)$this->params['complexity'];
	}

	function crypt( $password ) {
		// args[0] is the salt
		if ( count( $this->args ) == 0 ) {
			$this->args[] = MWCryptRand::generateHex( 8 );
		}
		$iter = pow( 2, $this->params['complexity'] );
		$h = $password;
		for ( $i = 0; $i < $iter; $i++ ) {
			$h = hash( 'whirlpool', str_repeat( $h . $this->args[0], 100 ), true );
			$h = substr( $h, 7, 32 );
		}
		$this->hash = bin2hex( $h );
	}

	function isHashContextFree() {
		return true;
	}
}
