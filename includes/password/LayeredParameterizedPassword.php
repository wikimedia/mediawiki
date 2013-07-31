<?php
/**
 * Implements the RehashedExistingPassword class for the MediaWiki software.
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
  * This password hash type layers one or more parameterized password types
  * on top of each other.
  *
  * The underlying types must be parameterized. This wrapping type accumulates
  * all the parameters and arguments from each hash and then passes the hash of
  * the last layer as the password for the next layer.
  *
  * @since 1.23
  */
class LayeredParameterizedPassword extends ParameterizedPassword {
	function getDelimiter() {
		return '!';
	}

	function getDefaultParams() {
		$params = array();

		$last = count( $this->config['types'] ) - 1;
		foreach ( $this->config['types'] as $i => $type ) {
			$passObj = self::newFromType( $type );

			if ( !$passObj instanceof ParameterizedPassword ) {
				throw new MWException( 'Underlying type must be a parameterized password.' );
			} elseif ( $passObj->getDelimiter() === $this->getDelimiter() ) {
				throw new MWException( 'Underlying type cannot use same delimiter as encapsulating type.' );
			} elseif ( $i != $last && !$passObj->isHashContextFree() ) {
				throw new MWException( 'Underlying type cannot be context-bound unless it is the last type.' );
			}

			$params[] = implode( $passObj->getDelimiter(), $passObj->getDefaultParams() );
		}

		return $params;
	}

	function crypt( $password ) {
		$lastHash = $password;
		foreach ( $this->config['types'] as $i => $type ) {
			// Construct pseudo-hash based on params and arguments
			/** @var ParameterizedPassword $passObj */
			$passObj = self::newFromType( $type );

			$params = '';
			$args = '';
			if ( $this->params[$i] !== '' ) {
				$params = $this->params[$i] . $passObj->getDelimiter();
			}
			if ( isset( $this->args[$i] ) && $this->args[$i] !== '' ) {
				$args = $this->args[$i] . $passObj->getDelimiter();
			}
			$existingHash = ":$type:" . $params . $args . $this->hash;

			// Hash the last hash with the next type in the layer
			$passObj = Password::newFromCiphertext( $existingHash );
			$passObj->crypt( $lastHash );

			// Move over the params and args
			$this->params[$i] = implode( $passObj->getDelimiter(), $passObj->params );
			$this->args[$i] = implode( $passObj->getDelimiter(), $passObj->args );
			$lastHash = $passObj->hash;
		}

		$this->hash = $lastHash;
	}

	function partialCrypt( ParameterizedPassword $passObj ) {
		$type = $passObj->config['type'];
		if ( $type !== $this->config['types'][0] ) {
			throw new MWException( 'Only a hash in the first layer can be finished.' );
		}

		// Gather info from the existing hash
		$this->params[0] = implode( $passObj->getDelimiter(), $passObj->params );
		$this->args[0] = implode( $passObj->getDelimiter(), $passObj->args );
		$lastHash = $passObj->hash;

		// Layer the remaining types
		foreach ( $this->config['types'] as $i => $type ) {
			if ( $i == 0 ) {
				continue;
			};

			// Construct pseudo-hash based on params and arguments
			/** @var ParameterizedPassword $passObj */
			$passObj = self::newFromType( $type );

			$params = '';
			$args = '';
			if ( $this->params[$i] !== '' ) {
				$params = $this->params[$i] . $passObj->getDelimiter();
			}
			if ( isset( $this->args[$i] ) && $this->args[$i] !== '' ) {
				$args = $this->args[$i] . $passObj->getDelimiter();
			}
			$existingHash = ":$type:" . $params . $args . $this->hash;

			// Hash the last hash with the next type in the layer
			$passObj = Password::newFromCiphertext( $existingHash );
			$passObj->crypt( $lastHash );

			// Move over the params and args
			$this->params[$i] = implode( $passObj->getDelimiter(), $passObj->params );
			$this->args[$i] = implode( $passObj->getDelimiter(), $passObj->args );
			$lastHash = $passObj->hash;
			++$i;
		}

		$this->hash = $lastHash;
	}

	function isHashContextFree() {
		$lastIndex = count( $this->config['types'] ) - 1;
		/** @var ParameterizedPassword $last */
		$last = Password::newFromType( $this->config['types'][$lastIndex] );

		return $last->isHashContextFree();
	}
}
