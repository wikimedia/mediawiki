<?php
/**
 * Implementation of TransclusionParameters based on PPNodes.
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
 * @since 1.25
 *
 * @file
 * @ingroup Content
 *
 * @author Daniel Kinzler
 */
class PPTransclusionParameters implements TransclusionParameters {

	/**
	 * @var string[]
	 */
	private $parameters;

	/**
	 * @var string[]|null
	 */
	private $parameterStrings = null;

	/**
	 * @param string[] $parameters
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct( array $parameters ) {
		if ( isset( $parameters[0] ) ) {
			throw new InvalidArgumentException( '$parameters must not use the key 0, parameters are numbered starting from 1' );
		}

		foreach ( $parameters as $key => $value ) {
			if ( !is_int( $key ) && !is_string( $key ) ) {
				throw new InvalidArgumentException( '$parameters must use int or string keys only!' );
			}

			if ( !is_string( $value ) && !( $value instanceof PPNode ) ) {
				throw new InvalidArgumentException( '$parameters must contain string or PPNode values only!' );
			}
		}

		$this->parameters = $parameters;
	}

	/**
	 * Returns the parameters as-is. Parameter values may be strings or PPNode objects.
	 * The array keys follow the rules defined by TransclusionParameters::getParameters().
	 *
	 * @see self::getParameters()
	 *
	 * @return array
	 */
	public function getParameterNodes() {
		return $this->parameters;
	}

	/**
	 * @see TransclusionParameters::getParameters().
	 *
	 * @return string[]
	 */
	public function getParameters() {
		if ( $this->parameterStrings === null ) {
			$this->parameterStrings = array();

			foreach ( $this->parameters as $key => $value ) {
				$this->parameterStrings[$key] = $this->convertToString( $value );
			}
		}

		return $this->parameterStrings;
	}

	/**
	 * @param string|PPNode $value
	 *
	 * @return string
	 */
	private function convertToString( $value ) {
		if ( $value instanceof PPNode ) {
			//XXX: add toString() to the PPNode interface?
			$value = "$value";
		}

		return $value;
	}

}
 