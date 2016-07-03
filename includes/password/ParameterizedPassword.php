<?php
/**
 * Implements the ParameterizedPassword class for the MediaWiki software.
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
 * Helper class for password hash types that have a delimited set of parameters
 * inside of the hash.
 *
 * All passwords are in the form of :<TYPE>:... as explained in the main Password
 * class. This class is for hashes in the form of :<TYPE>:<PARAM1>:<PARAM2>:... where
 * <PARAM1>, <PARAM2>, etc. are parameters that determine how the password was hashed.
 * Of course, the internal delimiter (which is : by convention and default), can be
 * changed by overriding the ParameterizedPassword::getDelimiter() function.
 *
 * This class requires overriding an additional function: ParameterizedPassword::getDefaultParams().
 * See the function description for more details on the implementation.
 *
 * @since 1.24
 */
abstract class ParameterizedPassword extends Password {
	/**
	 * Named parameters that have default values for this password type
	 * @var array
	 */
	protected $params = [];

	/**
	 * Extra arguments that were found in the hash. This may or may not make
	 * the hash invalid.
	 * @var array
	 */
	protected $args = [];

	protected function parseHash( $hash ) {
		parent::parseHash( $hash );

		if ( $hash === null ) {
			$this->params = $this->getDefaultParams();
			return;
		}

		$parts = explode( $this->getDelimiter(), $hash );
		$paramKeys = array_keys( $this->getDefaultParams() );

		if ( count( $parts ) < count( $paramKeys ) ) {
			throw new PasswordError( 'Hash is missing required parameters.' );
		}

		if ( $paramKeys ) {
			$this->args = array_splice( $parts, count( $paramKeys ) );
			$this->params = array_combine( $paramKeys, $parts );
		} else {
			$this->args = $parts;
		}

		if ( $this->args ) {
			$this->hash = array_pop( $this->args );
		} else {
			$this->hash = null;
		}
	}

	public function needsUpdate() {
		return parent::needsUpdate() || $this->params !== $this->getDefaultParams();
	}

	public function toString() {
		$str = ':' . $this->config['type'] . ':';

		if ( count( $this->params ) || count( $this->args ) ) {
			$str .= implode( $this->getDelimiter(), array_merge( $this->params, $this->args ) );
			$str .= $this->getDelimiter();
		}

		return $str . $this->hash;
	}

	/**
	 * Returns the delimiter for the parameters inside the hash
	 *
	 * @return string
	 */
	abstract protected function getDelimiter();

	/**
	 * Return an ordered array of default parameters for this password hash
	 *
	 * The keys should be the parameter names and the values should be the default
	 * values. Additionally, the order of the array should be the order in which they
	 * appear in the hash.
	 *
	 * When parsing a password hash, the constructor will split the hash based on
	 * the delimiter, and consume as many parts as it can, matching each to a parameter
	 * in this list. Once all the parameters have been filled, all remaining parts will
	 * be considered extra arguments, except, of course, for the very last part, which
	 * is the hash itself.
	 *
	 * @return array
	 */
	abstract protected function getDefaultParams();
}
