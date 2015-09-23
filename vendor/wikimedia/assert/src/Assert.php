<?php

namespace Wikimedia\Assert;

/**
 * Assert provides functions for assorting preconditions (such as parameter types) and
 * postconditions. It is intended as a safer alternative to PHP's assert() function.
 *
 * Note that assertions evaluate expressions and add function calls, so using assertions
 * may have a negative impact on performance when used in performance hotspots. The idea
 * if this class is to have a neat tool for assertions if and when they are needed.
 * It is not recommended to place assertions all over the code indiscriminately.
 *
 * For more information, see the the README file.
 *
 * @license MIT
 * @author Daniel Kinzler
 * @copyright Wikimedia Deutschland e.V.
 */
class Assert {

	/**
	 * Checks a precondition, that is, throws a PreconditionException if $condition is false.
	 * For checking call parameters, use Assert::parameter() instead.
	 *
	 * This is provided for completeness, most preconditions should be covered by
	 * Assert::parameter() and related assertions.
	 *
	 * @see parameter()
	 *
	 * @note This is intended mostly for checking preconditions in constructors and setters,
	 * or before using parameters in complex computations.
	 * Checking preconditions in every function call is not recommended, since it may have a
	 * negative impact on performance.
	 *
	 * @param bool $condition
	 * @param string $description The message to include in the exception if the condition fails.
	 *
	 * @throws PreconditionException if $condition is not true.
	 */
	public static function precondition( $condition, $description ) {
		if ( !$condition ) {
			throw new PreconditionException( "Precondition failed: $description" );
		}
	}

	/**
	 * Checks a parameter, that is, throws a ParameterAssertionException if $condition is false.
	 * This is similar to Assert::precondition().
	 *
	 * @note This is intended for checking parameters in constructors and setters.
	 * Checking parameters in every function call is not recommended, since it may have a
	 * negative impact on performance.
	 *
	 * @param bool $condition
	 * @param string $name The name of the parameter that was checked.
	 * @param string $description The message to include in the exception if the condition fails.
	 *
	 * @throws ParameterAssertionException if $condition is not true.
	 */
	public static function parameter( $condition, $name, $description ) {
		if ( !$condition ) {
			throw new ParameterAssertionException( $name, $description );
		}
	}

	/**
	 * Checks an parameter's type, that is, throws a InvalidArgumentException if $condition is false.
	 * This is really a special case of Assert::precondition().
	 *
	 * @note This is intended for checking parameters in constructors and setters.
	 * Checking parameters in every function call is not recommended, since it may have a
	 * negative impact on performance.
	 *
	 * @note If possible, type hints should be used instead of calling this function.
	 * It is intended for cases where type hints to not work, e.g. for checking primitive types.
	 *
	 * @param string $type The parameter's expected type. Can be the name of a native type or a
	 *        class or interface. If multiple types are allowed, they can be given separated by
	 *        a pipe character ("|").
	 * @param mixed $value The parameter's actual value.
	 * @param string $name The name of the parameter that was checked.
	 *
	 * @throws ParameterTypeException if $value is not of type (or, for objects, is not an
	 *         instance of) $type.
	 */
	public static function parameterType( $type, $value, $name ) {
		if ( !self::hasType( $value, explode( '|', $type ) ) ) {
			throw new ParameterTypeException( $name, $type );
		}
	}

	/**
	 * Checks the type of all elements of an parameter, assuming the parameter is an array,
	 * that is, throws a ParameterElementTypeException if $value
	 *
	 * @note This is intended for checking parameters in constructors and setters.
	 * Checking parameters in every function call is not recommended, since it may have a
	 * negative impact on performance.
	 *
	 * @param string $type The elements' expected type. Can be the name of a native type or a
	 *        class or interface. If multiple types are allowed, they can be given separated by
	 *        a pipe character ("|").
	 * @param mixed $value The parameter's actual value. If this is not an array,
	 *        a ParameterTypeException is raised.
	 * @param string $name The name of the parameter that was checked.
	 *
	 * @throws ParameterTypeException If $value is not an array.
	 * @throws ParameterElementTypeException If an element of $value  is not of type
	 *         (or, for objects, is not an instance of) $type.
	 */
	public static function parameterElementType( $type, $value, $name ) {
		self::parameterType( 'array', $value, $name );

		$allowedTypes = explode( '|', $type );

		foreach ( $value as $element ) {
			if ( !self::hasType( $element, $allowedTypes ) ) {
				throw new ParameterElementTypeException( $name, $type );
			}
		}
	}

	/**
	 * Checks a postcondition, that is, throws a PostconditionException if $condition is false.
	 * This is very similar Assert::invariant() but is intended for use only after a computation
	 * is complete.
	 *
	 * @note This is intended for sanity-checks in the implementation of complex algorithms.
	 * Note however that it should not be used in performance hotspots, since evaluating
	 * $condition and calling postcondition() costs time.
	 *
	 * @param bool $condition
	 * @param string $description The message to include in the exception if the condition fails.
	 *
	 * @throws PostconditionException
	 */
	public static function postcondition( $condition, $description ) {
		if ( !$condition ) {
			throw new PostconditionException( "Postcondition failed: $description" );
		}
	}

	/**
	 * Checks an invariant, that is, throws a InvariantException if $condition is false.
	 * This is very similar Assert::postcondition() but is intended for use throughout the code.
	 *
	 * @note This is intended for sanity-checks in the implementation of complex algorithms.
	 * Note however that it should not be used in performance hotspots, since evaluating
	 * $condition and calling postcondition() costs time.
	 *
	 * @param bool $condition
	 * @param string $description The message to include in the exception if the condition fails.
	 *
	 * @throws InvariantException
	 */
	public static function invariant( $condition, $description ) {
		if ( !$condition ) {
			throw new InvariantException( "Invariant failed: $description" );
		}
	}

	/**
	 * @param mixed $value
	 * @param array $allowedTypes
	 *
	 * @return bool
	 */
	private static function hasType( $value, array $allowedTypes ) {
		// Apply strtolower because gettype returns "NULL" for null values.
		$type = strtolower( gettype( $value ) );

		if ( in_array( $type, $allowedTypes ) ) {
			return true;
		}

		if ( is_callable( $value ) && in_array( 'callable', $allowedTypes ) ) {
			return true;
		}

		if ( is_object( $value ) && self::isInstanceOf( $value, $allowedTypes ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @param mixed $value
	 * @param array $allowedTypes
	 *
	 * @return bool
	 */
	private static function isInstanceOf( $value, array $allowedTypes ) {
		foreach ( $allowedTypes as $type ) {
			if ( $value instanceof $type ) {
				return true;
			}
		}

		return false;
	}

}
