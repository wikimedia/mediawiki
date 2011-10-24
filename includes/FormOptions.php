<?php
/**
 * Helper class to keep track of options when mixing links and form elements.
 * @todo This badly need some examples and tests :-)
 *
 * Copyright © 2008, Niklas Laxstiröm
 *
 * Copyright © 2011, Antoine Musso
 *
 * @author Niklas Laxström
 * @author Antoine Musso 
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
	/** Boolean type, maps guessType() to WebRequest::getBool() */
	const BOOL = 2;
	/** Integer type or null, maps to WebRequest::getIntOrNull()
	 * This is useful for the namespace selector.
	 */
	const INTNULL = 3;
	/* @} */

	/**
	 * @todo Document!
	 */
	protected $options = array();

	# Setting up

	public function add( $name, $default, $type = self::AUTO ) {
		$option = array();
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

	public function delete( $name ) {
		$this->validateName( $name, true );
		unset( $this->options[$name] );
	}

	/**
	 * Used to find out which type the data is.
	 * All types are defined in the 'Type constants' section of this class
	 * Please note we do not support detection of INTNULL MediaWiki type
	 * which will be assumed as INT if the data is an integer.
	 *
	 * @param $data Mixed: value to guess type for
	 * @exception MWException Unsupported datatype
	 * @return Type constant 
	 */
	public static function guessType( $data ) {
		if ( is_bool( $data ) ) {
			return self::BOOL;
		} elseif ( is_int( $data ) ) {
			return self::INT;
		} elseif ( is_string( $data ) ) {
			return self::STRING;
		} else {
			throw new MWException( 'Unsupported datatype' );
		}
	}

	# Handling values

	/**
	 * Verify the given option name exist.
	 *
	 * @param $name String: option name
	 * @param $strict Boolean: throw an exception when the option does not exist (default false)
	 * @return Boolean: true if option exist, false otherwise
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
	 * @param $name String: option name
	 * @param $value Mixed: value for the option
	 * @param $force Boolean: whether to set the value when it is equivalent to the default value for this option (default false).
	 * @return null
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
	 * Get the value for the given option name.
	 * Internally use getValueReal()
	 *
	 * @param $name String: option name
	 * @return Mixed
	 */
	public function getValue( $name ) {
		$this->validateName( $name, true );

		return $this->getValueReal( $this->options[$name] );
	}

	/**
	 * @todo Document
	 * @param $option Array: array structure describing the option
	 * @return Mixed. Value or the default value if it is null
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
	 * This will make future calls to getValue()  return the default value.
	 * @param $name String: option name
	 * @return null
	 */
	public function reset( $name ) {
		$this->validateName( $name, true );
		$this->options[$name]['value'] = null;
	}

	/**
	 * @todo Document
	 * @param $name String: option name
	 * @return null
	 */
	public function consumeValue( $name ) {
		$this->validateName( $name, true );
		$this->options[$name]['consumed'] = true;

		return $this->getValueReal( $this->options[$name] );
	}

	/**
	 * @todo Document
	 * @param $names Array: array of option names
	 * @return null
	 */
	public function consumeValues( /*Array*/ $names ) {
		$out = array();

		foreach ( $names as $name ) {
			$this->validateName( $name, true );
			$this->options[$name]['consumed'] = true;
			$out[] = $this->getValueReal( $this->options[$name] );
		}

		return $out;
	}

	/**
	 * Validate and set an option integer value
	 * The value will be altered to fit in the range. 
	 *
	 * @param $name String: option name
	 * @param $min Int: minimum value
	 * @param $max Int: maximum value
	 * @exception MWException Option is not of type int
	 * @return null
	 */
	public function validateIntBounds( $name, $min, $max ) {
		$this->validateName( $name, true );

		if ( $this->options[$name]['type'] !== self::INT ) {
			throw new MWException( "Option $name is not of type int" );
		}

		$value = $this->getValueReal( $this->options[$name] );
		$value = max( $min, min( $max, $value ) );

		$this->setValue( $name, $value );
	}

	/**
	 * Getting the data out for use
	 * @param $all Boolean: whether to include unchanged options (default: false)
	 * @return Array
	 */
	public function getUnconsumedValues( $all = false ) {
		$values = array();

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
	 * @return Array
	 */
	public function getChangedValues() {
		$values = array();

		foreach ( $this->options as $name => $data ) {
			if ( $data['value'] !== null ) {
				$values[$name] = $data['value'];
			}
		}

		return $values;
	}

	/**
	 * Format options to an array ( name => value)
	 * @return Array
	 */
	public function getAllValues() {
		$values = array();

		foreach ( $this->options as $name => $data ) {
			$values[$name] = $this->getValueReal( $data );
		}

		return $values;
	}

	# Reading values

	public function fetchValuesFromRequest( WebRequest $r, $values = false ) {
		if ( !$values ) {
			$values = array_keys( $this->options );
		}

		foreach ( $values as $name ) {
			$default = $this->options[$name]['default'];
			$type = $this->options[$name]['type'];

			switch( $type ) {
				case self::BOOL:
					$value = $r->getBool( $name, $default ); break;
				case self::INT:
					$value = $r->getInt( $name, $default ); break;
				case self::STRING:
					$value = $r->getText( $name, $default ); break;
				case self::INTNULL:
					$value = $r->getIntOrNull( $name ); break;
				default:
					throw new MWException( 'Unsupported datatype' );
			}

			if ( $value !== null ) {
				$this->options[$name]['value'] = $value === $default ? null : $value;
			}
		}
	}

	/** @name ArrayAccess functions
	 * Those function implements PHP ArrayAccess interface
	 * @see http://php.net/manual/en/class.arrayaccess.php
	 */
	/* @{ */
	/** Whether option exist*/
	public function offsetExists( $name ) {
		return isset( $this->options[$name] );
	}
	/** Retrieve an option value */
	public function offsetGet( $name ) {
		return $this->getValue( $name );
	}
	/**	Set an option to given value */
	public function offsetSet( $name, $value ) {
		$this->setValue( $name, $value );
	}
	/**	Delete the option */
	public function offsetUnset( $name ) {
		$this->delete( $name );
	}
	/* @} */
}
