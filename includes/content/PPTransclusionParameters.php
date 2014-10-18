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
class PPTransclusionParameters extends TransclusionParameters {

	/**
	 * @var array
	 */
	private $parameters;

	/**
	 * @var string[]|null
	 */
	private $parameterStrings = null;

	/**
	 * @var PPFrame
	 */
	private $frame;

	/**
	 * @param PPFrame $frame
	 * @param array|PPNode $parameters List of strings or PPNodes. Keys must be integers > 0 or strings.
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct( PPFrame $frame, $parameters ) {
		if ( $parameters instanceof PPNode ) {
			$node = $parameters;
			$parameters = array();

			for ( $i = 0; $i < $node->getLength(); $i++ ) {
				$funcArgs[$i+1] = $node->item( $i );
			}
		}

		if ( !is_array( $parameters ) ) {
			throw new InvalidArgumentException( '$parameters must be an array or a PPNode' );
		}

		foreach ( $parameters as $key => $value ) {
			if ( !is_int( $key ) && !is_string( $key ) ) {
				throw new InvalidArgumentException( '$parameters must use int or string keys only!' );
			}

			if ( is_int( $key ) && $key < 1 ) {
				throw new InvalidArgumentException( '$parameters indexes nmust be greater that 0 (or strings)!' );
			}

			if ( !is_string( $value ) && !( $value instanceof PPNode ) ) {
				throw new InvalidArgumentException( '$parameters must contain string or PPNode values only!' );
			}
		}

		$this->frame = $frame;
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
	 * @return PPFrame
	 */
	public function getFrame() {
		return $this->frame;
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
	 * @param string|PPNode $v
	 *
	 * @return string
	 */
	private function convertToString( $v ) {
		if ( $v instanceof PPNode ) {
			return trim( $this->frame->expand( $v ) );
		} else {
			return trim( $v );
		}
	}

}
 