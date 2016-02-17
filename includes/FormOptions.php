<?php
/**
 * Helper class to keep track of options when mixing links and form elements.
 *
 * Copyright © 2008, Niklas Laxström
 * Copyright © 2011, Antoine Musso
 * Copyright © 2013, Bartosz Dziewoński
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
 * @author Niklas Laxström
 * @author Antoine Musso
 */

/**
 * Helper class to keep track of options when mixing links and form elements.
 *
 * @todo This badly needs some examples and tests :) The usage in SpecialRecentchanges class is a
 *     good ersatz in the meantime.
 */
class FormOptions implements ArrayAccess {
	/** @name Type constants
	 * Used internally to map an option value to a WebRequest accessor
	 */
	/* @{ */
	/** Mark value for automatic detection (for simple data types only) */
	const AUTO = -1;
	/** String type, maps guessType() to WebRequest::getText() */
	const STRING = 0;
	/** Integer type, maps guessType() to WebRequest::getInt() */
	const INT = 1;
	/** Float type, maps guessType() to WebRequest::getFloat()
	 * @since 1.23 */
	const FLOAT = 4;
	/** Boolean type, maps guessType() to WebRequest::getBool() */
	const BOOL = 2;
	/** Integer type or null, maps to WebRequest::getIntOrNull()
	 * This is useful for the namespace selector.
	 */
	const INTNULL = 3;
	/* @} */

	/**
	 * Map of known option names to information about them.
	 *
	 * Each value is an array with the following keys:
	 * - 'default' - the default value as passed to add()
	 * - 'value' - current value, start with null, can be set by various functions
	 * - 'consumed' - true/false, whether the option was consumed using
	 *   consumeValue() or consumeValues()
	 * - 'type' - one of the type constants (but never AUTO)
	 */
	protected $options = [];

	# Setting up

	/**
	 * Add an option to be handled by this FormOptions instance.
	 *
	 * @param string $name Request parameter name
	 * @param mixed $default Default value when the request parameter is not present
	 * @param int $type One of the type constants (optional, defaults to AUTO)
	 */
	public function add( $name, $default, $type = self::AUTO ) {
		$option = [];
		$option['default'] = $default;
		$option['value'] = null;
		$option['consumed'] = false;

		if ( $type !== self::AUTO ) {
			$option['type'] = $type;
		} else {
			$option['type'] = self::guessType( $default );
		}

		$this->options[$name] = $option;
	}

	/**
	 * Remove an option being handled by this FormOptions instance. This is the inverse of add().
	 *
	 * @param string $name Request parameter name
	 */
	public function delete( $name ) {
		$this->validateName( $name, true );
		unset( $this->options[$name] );
	}

	/**
	 * Used to find out which type the data is. All types are defined in the 'Type constants' section
	 * of this class.
	 *
	 * Detection of the INTNULL type is not supported; INT will be assumed if the data is an integer,
	 * MWException will be thrown if it's null.
	 *
	 * @param mixed $data Value to guess the type for
	 * @throws MWException If unable to guess the type
	 * @return int Type constant
	 */
	public static function guessType( $data ) {
		if ( is_bool( $data ) ) {
			return self::BOOL;
		} elseif ( is_int( $data ) ) {
			return self::INT;
		} elseif ( is_float( $data ) ) {
			return self::FLOAT;
		} elseif ( is_string( $data ) ) {
			return self::STRING;
		} else {
			throw new MWException( 'Unsupported datatype' );
		}
	}

	# Handling values

	/**
	 * Verify that the given option name exists.
	 *
	 * @param string $name Option name
	 * @param bool $strict Throw an exception when the option doesn't exist instead of returning false
	 * @throws MWException
	 * @return bool True if the option exists, false otherwise
	 */
	public function validateName( $name, $strict = false ) {
		if ( !isset( $this->options[$name] ) ) {
			if ( $strict ) {
				throw new MWException( "Invalid option $name" );
			} else {
				return false;
			}
		}

		return true;
	}

	/**
	 * Use to set the value of an option.
	 *
	 * @param string $name Option name
	 * @param mixed $value Value for the option
	 * @param bool $force Whether to set the value when it is equivalent to the default value for this
	 *     option (default false).
	 */
	public function setValue( $name, $value, $force = false ) {
		$this->validateName( $name, true );

		if ( !$force && $value === $this->options[$name]['default'] ) {
			// null default values as unchanged
			$this->options[$name]['value'] = null;
		} else {
			$this->options[$name]['value'] = $value;
		}
	}

	/**
	 * Get the value for the given option name. Uses getValueReal() internally.
	 *
	 * @param string $name Option name
	 * @return mixed
	 */
	public function getValue( $name ) {
		$this->validateName( $name, true );

		return $this->getValueReal( $this->options[$name] );
	}

	/**
	 * Return current option value, based on a structure taken from $options.
	 *
	 * @param array $option Array structure describing the option
	 * @return mixed Value, or the default value if it is null
	 */
	protected function getValueReal( $option ) {
		if ( $option['value'] !== null ) {
			return $option['value'];
		} else {
			return $option['default'];
		}
	}

	/**
	 * Delete the option value.
	 * This will make future calls to getValue() return the default value.
	 * @param string $name Option name
	 */
	public function reset( $name ) {
		$this->validateName( $name, true );
		$this->options[$name]['value'] = null;
	}

	/**
	 * Get the value of given option and mark it as 'consumed'. Consumed options are not returned
	 * by getUnconsumedValues().
	 *
	 * @see consumeValues()
	 * @throws MWException If the option does not exist
	 * @param string $name Option name
	 * @return mixed Value, or the default value if it is null
	 */
	public function consumeValue( $name ) {
		$this->validateName( $name, true );
		$this->options[$name]['consumed'] = true;

		return $this->getValueReal( $this->options[$name] );
	}

	/**
	 * Get the values of given options and mark them as 'consumed'. Consumed options are not returned
	 * by getUnconsumedValues().
	 *
	 * @see consumeValue()
	 * @throws MWException If any option does not exist
	 * @param array $names Array of option names as strings
	 * @return array Array of option values, or the default values if they are null
	 */
	public function consumeValues( $names ) {
		$out = [];

		foreach ( $names as $name ) {
			$this->validateName( $name, true );
			$this->options[$name]['consumed'] = true;
			$out[] = $this->getValueReal( $this->options[$name] );
		}

		return $out;
	}

	/**
	 * @see validateBounds()
	 */
	public function validateIntBounds( $name, $min, $max ) {
		$this->validateBounds( $name, $min, $max );
	}

	/**
	 * Constrain a numeric value for a given option to a given range. The value will be altered to fit
	 * in the range.
	 *
	 * @since 1.23
	 *
	 * @param string $name Option name
	 * @param int|float $min Minimum value
	 * @param int|float $max Maximum value
	 * @throws MWException If option is not of type INT
	 */
	public function validateBounds( $name, $min, $max ) {
		$this->validateName( $name, true );
		$type = $this->options[$name]['type'];

		if ( $type !== self::INT && $type !== self::FLOAT ) {
			throw new MWException( "Option $name is not of type INT or FLOAT" );
		}

		$value = $this->getValueReal( $this->options[$name] );
		$value = max( $min, min( $max, $value ) );

		$this->setValue( $name, $value );
	}

	/**
	 * Get all remaining values which have not been consumed by consumeValue() or consumeValues().
	 *
	 * @param bool $all Whether to include unchanged options (default: false)
	 * @return array
	 */
	public function getUnconsumedValues( $all = false ) {
		$values = [];

		foreach ( $this->options as $name => $data ) {
			if ( !$data['consumed'] ) {
				if ( $all || $data['value'] !== null ) {
					$values[$name] = $this->getValueReal( $data );
				}
			}
		}

		return $values;
	}

	/**
	 * Return options modified as an array ( name => value )
	 * @return array
	 */
	public function getChangedValues() {
		$values = [];

		foreach ( $this->options as $name => $data ) {
			if ( $data['value'] !== null ) {
				$values[$name] = $data['value'];
			}
		}

		return $values;
	}

	/**
	 * Format options to an array ( name => value )
	 * @return array
	 */
	public function getAllValues() {
		$values = [];

		foreach ( $this->options as $name => $data ) {
			$values[$name] = $this->getValueReal( $data );
		}

		return $values;
	}

	# Reading values

	/**
	 * Fetch values for all options (or selected options) from the given WebRequest, making them
	 * available for accessing with getValue() or consumeValue() etc.
	 *
	 * @param WebRequest $r The request to fetch values from
	 * @param array $optionKeys Which options to fetch the values for (default:
	 *     all of them). Note that passing an empty array will also result in
	 *     values for all keys being fetched.
	 * @throws MWException If the type of any option is invalid
	 */
	public function fetchValuesFromRequest( WebRequest $r, $optionKeys = null ) {
		if ( !$optionKeys ) {
			$optionKeys = array_keys( $this->options );
		}

		foreach ( $optionKeys as $name ) {
			$default = $this->options[$name]['default'];
			$type = $this->options[$name]['type'];

			switch ( $type ) {
				case self::BOOL:
					$value = $r->getBool( $name, $default );
					break;
				case self::INT:
					$value = $r->getInt( $name, $default );
					break;
				case self::FLOAT:
					$value = $r->getFloat( $name, $default );
					break;
				case self::STRING:
					$value = $r->getText( $name, $default );
					break;
				case self::INTNULL:
					$value = $r->getIntOrNull( $name );
					break;
				default:
					throw new MWException( 'Unsupported datatype' );
			}

			if ( $value !== null ) {
				$this->options[$name]['value'] = $value === $default ? null : $value;
			}
		}
	}

	/** @name ArrayAccess functions
	 * These functions implement the ArrayAccess PHP interface.
	 * @see http://php.net/manual/en/class.arrayaccess.php
	 */
	/* @{ */
	/**
	 * Whether the option exists.
	 * @param string $name
	 * @return bool
	 */
	public function offsetExists( $name ) {
		return isset( $this->options[$name] );
	}

	/**
	 * Retrieve an option value.
	 * @param string $name
	 * @return mixed
	 */
	public function offsetGet( $name ) {
		return $this->getValue( $name );
	}

	/**
	 * Set an option to given value.
	 * @param string $name
	 * @param mixed $value
	 */
	public function offsetSet( $name, $value ) {
		$this->setValue( $name, $value );
	}

	/**
	 * Delete the option.
	 * @param string $name
	 */
	public function offsetUnset( $name ) {
		$this->delete( $name );
	}
	/* @} */
}
