<?php

namespace Wikimedia\ParamValidator;

use DomainException;
use InvalidArgumentException;
use Wikimedia\Assert\Assert;
use Wikimedia\ObjectFactory;

/**
 * Service for formatting and validating API parameters
 *
 * A settings array is simply an array with keys being the relevant PARAM_*
 * constants from this class, TypeDef, and its subclasses.
 *
 * As a general overview of the architecture here:
 *  - ParamValidator handles some general validation of the parameter,
 *    then hands off to a TypeDef subclass to validate the specific representation
 *    based on the parameter's type.
 *  - TypeDef subclasses handle conversion between the string representation
 *    submitted by the client and the output PHP data types, validating that the
 *    strings are valid representations of the intended type as they do so.
 *  - ValidationException is used to report fatal errors in the validation back
 *    to the caller, since the return value represents the successful result of
 *    the validation and might be any type or class.
 *  - The Callbacks interface allows ParamValidator to reach out and fetch data
 *    it needs to perform the validation. Currently that includes:
 *    - Fetching the value of the parameter being validated (largely since a generic
 *      caller cannot know whether it needs to fetch a string from $_GET/$_POST or
 *      an array from $_FILES).
 *    - Reporting of non-fatal warnings back to the caller.
 *    - Fetching the "high limits" flag when necessary, to avoid the need for loading
 *      the user unnecessarily.
 *
 * @since 1.34
 * @unstable
 */
class ParamValidator {

	/**
	 * @name Constants for parameter settings arrays
	 * These constants are keys in the settings array that define how the
	 * parameters coming in from the request are to be interpreted.
	 *
	 * If a constant is associated with a ValidationException, the failure code
	 * and data are described. ValidationExceptions are typically thrown, but
	 * those indicated as "non-fatal" are instead passed to
	 * Callbacks::recordCondition().
	 *
	 * Additional constants may be defined by TypeDef subclasses, or by other
	 * libraries for controlling things like auto-generated parameter documentation.
	 * For purposes of namespacing the constants, the values of all constants
	 * defined by this library begin with 'param-'.
	 *
	 * @{
	 */

	/** (mixed) Default value of the parameter. If omitted, null is the default. */
	const PARAM_DEFAULT = 'param-default';

	/**
	 * (string|array) Type of the parameter.
	 * Must be a registered type or an array of enumerated values (in which case the "enum"
	 * type must be registered). If omitted, the default is the PHP type of the default value
	 * (see PARAM_DEFAULT).
	 */
	const PARAM_TYPE = 'param-type';

	/**
	 * (bool) Indicate that the parameter is required.
	 *
	 * ValidationException codes:
	 *  - 'missingparam': The parameter is omitted/empty (and no default was set). No data.
	 */
	const PARAM_REQUIRED = 'param-required';

	/**
	 * (bool) Indicate that the parameter is multi-valued.
	 *
	 * A multi-valued parameter may be submitted in one of several formats. All
	 * of the following result a value of `[ 'a', 'b', 'c' ]`.
	 *  - "a|b|c", i.e. pipe-separated.
	 *  - "\x1Fa\x1Fb\x1Fc", i.e. separated by U+001F, with a signalling U+001F at the start.
	 *  - As a string[], e.g. from a query string like "foo[]=a&foo[]=b&foo[]=c".
	 *
	 * Each of the multiple values is passed individually to the TypeDef.
	 * $options will contain a 'values-list' key holding the entire list.
	 *
	 * By default duplicates are removed from the resulting parameter list. Use
	 * PARAM_ALLOW_DUPLICATES to override that behavior.
	 *
	 * ValidationException codes:
	 *  - 'toomanyvalues': More values were supplied than are allowed. See
	 *    PARAM_ISMULTI_LIMIT1, PARAM_ISMULTI_LIMIT2, and constructor option
	 *    'ismultiLimits'. Data:
	 *     - 'limit': The limit that was exceeded.
	 *  - 'unrecognizedvalues': Non-fatal. Invalid values were passed and
	 *    PARAM_IGNORE_INVALID_VALUES was set. Data:
	 *     - 'values': The unrecognized values.
	 */
	const PARAM_ISMULTI = 'param-ismulti';

	/**
	 * (int) Maximum number of multi-valued parameter values allowed
	 *
	 * PARAM_ISMULTI_LIMIT1 is the normal limit, and PARAM_ISMULTI_LIMIT2 is
	 * the limit when useHighLimits() returns true.
	 *
	 * ValidationException codes:
	 *  - 'toomanyvalues': The limit was exceeded. Data:
	 *     - 'limit': The limit that was exceeded.
	 */
	const PARAM_ISMULTI_LIMIT1 = 'param-ismulti-limit1';

	/**
	 * (int) Maximum number of multi-valued parameter values allowed for users
	 * allowed high limits.
	 *
	 * PARAM_ISMULTI_LIMIT1 is the normal limit, and PARAM_ISMULTI_LIMIT2 is
	 * the limit when useHighLimits() returns true.
	 *
	 * ValidationException codes:
	 *  - 'toomanyvalues': The limit was exceeded. Data:
	 *     - 'limit': The limit that was exceeded.
	 */
	const PARAM_ISMULTI_LIMIT2 = 'param-ismulti-limit2';

	/**
	 * (bool|string) Whether a magic "all values" value exists for multi-valued
	 * enumerated types, and if so what that value is.
	 *
	 * When PARAM_TYPE has a defined set of values and PARAM_ISMULTI is true,
	 * this allows for an asterisk ('*') to be passed in place of a pipe-separated list of
	 * every possible value. If a string is set, it will be used in place of the asterisk.
	 */
	const PARAM_ALL = 'param-all';

	/**
	 * (bool) Allow the same value to be set more than once when PARAM_ISMULTI is true?
	 *
	 * If not truthy, the set of values will be passed through
	 * `array_values( array_unique() )`. The default is falsey.
	 */
	const PARAM_ALLOW_DUPLICATES = 'param-allow-duplicates';

	/**
	 * (bool) Indicate that the parameter's value should not be logged.
	 *
	 * ValidationException codes: (non-fatal)
	 *  - 'param-sensitive': Always recorded.
	 */
	const PARAM_SENSITIVE = 'param-sensitive';

	/**
	 * (bool) Indicate that a deprecated parameter was used.
	 *
	 * ValidationException codes: (non-fatal)
	 *  - 'param-deprecated': Always recorded.
	 */
	const PARAM_DEPRECATED = 'param-deprecated';

	/**
	 * (bool) Whether to ignore invalid values.
	 *
	 * This controls whether certain ValidationExceptions are considered fatal
	 * or non-fatal. The default is false.
	 */
	const PARAM_IGNORE_INVALID_VALUES = 'param-ignore-invalid-values';

	/** @} */

	/** Magic "all values" value when PARAM_ALL is true. */
	const ALL_DEFAULT_STRING = '*';

	/** A list of standard type names and types that may be passed as `$typeDefs` to __construct(). */
	public static $STANDARD_TYPES = [
		'boolean' => [ 'class' => TypeDef\BooleanDef::class ],
		'checkbox' => [ 'class' => TypeDef\PresenceBooleanDef::class ],
		'integer' => [ 'class' => TypeDef\IntegerDef::class ],
		'limit' => [ 'class' => TypeDef\LimitDef::class ],
		'float' => [ 'class' => TypeDef\FloatDef::class ],
		'double' => [ 'class' => TypeDef\FloatDef::class ],
		'string' => [ 'class' => TypeDef\StringDef::class ],
		'password' => [ 'class' => TypeDef\PasswordDef::class ],
		'NULL' => [
			'class' => TypeDef\StringDef::class,
			'args' => [ [
				'allowEmptyWhenRequired' => true,
			] ],
		],
		'timestamp' => [ 'class' => TypeDef\TimestampDef::class ],
		'upload' => [ 'class' => TypeDef\UploadDef::class ],
		'enum' => [ 'class' => TypeDef\EnumDef::class ],
	];

	/** @var Callbacks */
	private $callbacks;

	/** @var ObjectFactory */
	private $objectFactory;

	/** @var (TypeDef|array)[] Map parameter type names to TypeDef objects or ObjectFactory specs */
	private $typeDefs = [];

	/** @var int Default values for PARAM_ISMULTI_LIMIT1 */
	private $ismultiLimit1;

	/** @var int Default values for PARAM_ISMULTI_LIMIT2 */
	private $ismultiLimit2;

	/**
	 * @param Callbacks $callbacks
	 * @param ObjectFactory $objectFactory To turn specs into TypeDef objects
	 * @param array $options Associative array of additional settings
	 *  - 'typeDefs': (array) As for addTypeDefs(). If omitted, self::$STANDARD_TYPES will be used.
	 *    Pass an empty array if you want to start with no registered types.
	 *  - 'ismultiLimits': (int[]) Two ints, being the default values for PARAM_ISMULTI_LIMIT1 and
	 *    PARAM_ISMULTI_LIMIT2. If not given, defaults to `[ 50, 500 ]`.
	 */
	public function __construct(
		Callbacks $callbacks,
		ObjectFactory $objectFactory,
		array $options = []
	) {
		$this->callbacks = $callbacks;
		$this->objectFactory = $objectFactory;

		$this->addTypeDefs( $options['typeDefs'] ?? self::$STANDARD_TYPES );
		$this->ismultiLimit1 = $options['ismultiLimits'][0] ?? 50;
		$this->ismultiLimit2 = $options['ismultiLimits'][1] ?? 500;
	}

	/**
	 * List known type names
	 * @return string[]
	 */
	public function knownTypes() {
		return array_keys( $this->typeDefs );
	}

	/**
	 * Register multiple type handlers
	 *
	 * @see addTypeDef()
	 * @param array $typeDefs Associative array mapping `$name` to `$typeDef`.
	 */
	public function addTypeDefs( array $typeDefs ) {
		foreach ( $typeDefs as $name => $def ) {
			$this->addTypeDef( $name, $def );
		}
	}

	/**
	 * Register a type handler
	 *
	 * To allow code to omit PARAM_TYPE in settings arrays to derive the type
	 * from PARAM_DEFAULT, it is strongly recommended that the following types be
	 * registered: "boolean", "integer", "double", "string", "NULL", and "enum".
	 *
	 * When using ObjectFactory specs, the following extra arguments are passed:
	 * - The Callbacks object for this ParamValidator instance.
	 *
	 * @param string $name Type name
	 * @param TypeDef|array $typeDef Type handler or ObjectFactory spec to create one.
	 */
	public function addTypeDef( $name, $typeDef ) {
		Assert::parameterType(
			implode( '|', [ TypeDef::class, 'array' ] ),
			$typeDef,
			'$typeDef'
		);

		if ( isset( $this->typeDefs[$name] ) ) {
			throw new InvalidArgumentException( "Type '$name' is already registered" );
		}
		$this->typeDefs[$name] = $typeDef;
	}

	/**
	 * Register a type handler, overriding any existing handler
	 * @see addTypeDef
	 * @param string $name Type name
	 * @param TypeDef|array|null $typeDef As for addTypeDef, or null to unregister a type.
	 */
	public function overrideTypeDef( $name, $typeDef ) {
		Assert::parameterType(
			implode( '|', [ TypeDef::class, 'array', 'null' ] ),
			$typeDef,
			'$typeDef'
		);

		if ( $typeDef === null ) {
			unset( $this->typeDefs[$name] );
		} else {
			$this->typeDefs[$name] = $typeDef;
		}
	}

	/**
	 * Test if a type is registered
	 * @param string $name Type name
	 * @return bool
	 */
	public function hasTypeDef( $name ) {
		return isset( $this->typeDefs[$name] );
	}

	/**
	 * Get the TypeDef for a type
	 * @param string|array $type Any array is considered equivalent to the string "enum".
	 * @return TypeDef|null
	 */
	public function getTypeDef( $type ) {
		if ( is_array( $type ) ) {
			$type = 'enum';
		}

		if ( !isset( $this->typeDefs[$type] ) ) {
			return null;
		}

		$def = $this->typeDefs[$type];
		if ( !$def instanceof TypeDef ) {
			$def = $this->objectFactory->createObject( $def, [
				'extraArgs' => [ $this->callbacks ],
				'assertClass' => TypeDef::class,
			] );
			$this->typeDefs[$type] = $def;
		}

		return $def;
	}

	/**
	 * Normalize a parameter settings array
	 * @param array|mixed $settings Default value or an array of settings
	 *  using PARAM_* constants.
	 * @return array
	 */
	public function normalizeSettings( $settings ) {
		// Shorthand
		if ( !is_array( $settings ) ) {
			$settings = [
				self::PARAM_DEFAULT => $settings,
			];
		}

		// When type is not given, determine it from the type of the PARAM_DEFAULT
		if ( !isset( $settings[self::PARAM_TYPE] ) ) {
			$settings[self::PARAM_TYPE] = gettype( $settings[self::PARAM_DEFAULT] ?? null );
		}

		$typeDef = $this->getTypeDef( $settings[self::PARAM_TYPE] );
		if ( $typeDef ) {
			$settings = $typeDef->normalizeSettings( $settings );
		}

		return $settings;
	}

	/**
	 * Fetch and valiate a parameter value using a settings array
	 *
	 * @param string $name Parameter name
	 * @param array|mixed $settings Default value or an array of settings
	 *  using PARAM_* constants.
	 * @param array $options Options array, passed through to the TypeDef and Callbacks.
	 * @return mixed Validated parameter value
	 * @throws ValidationException if the value is invalid
	 */
	public function getValue( $name, $settings, array $options = [] ) {
		$settings = $this->normalizeSettings( $settings );

		$typeDef = $this->getTypeDef( $settings[self::PARAM_TYPE] );
		if ( !$typeDef ) {
			throw new DomainException(
				"Param $name's type is unknown - {$settings[self::PARAM_TYPE]}"
			);
		}

		$value = $typeDef->getValue( $name, $settings, $options );

		if ( $value !== null ) {
			if ( !empty( $settings[self::PARAM_SENSITIVE] ) ) {
				$this->callbacks->recordCondition(
					new ValidationException( $name, $value, $settings, 'param-sensitive', [] ),
					$options
				);
			}

			// Set a warning if a deprecated parameter has been passed
			if ( !empty( $settings[self::PARAM_DEPRECATED] ) ) {
				$this->callbacks->recordCondition(
					new ValidationException( $name, $value, $settings, 'param-deprecated', [] ),
					$options
				);
			}
		} elseif ( isset( $settings[self::PARAM_DEFAULT] ) ) {
			$value = $settings[self::PARAM_DEFAULT];
		}

		return $this->validateValue( $name, $value, $settings, $options );
	}

	/**
	 * Valiate a parameter value using a settings array
	 *
	 * @param string $name Parameter name
	 * @param null|mixed $value Parameter value
	 * @param array|mixed $settings Default value or an array of settings
	 *  using PARAM_* constants.
	 * @param array $options Options array, passed through to the TypeDef and Callbacks.
	 *  - An additional option, 'values-list', will be set when processing the
	 *    values of a multi-valued parameter.
	 * @return mixed Validated parameter value(s)
	 * @throws ValidationException if the value is invalid
	 */
	public function validateValue( $name, $value, $settings, array $options = [] ) {
		$settings = $this->normalizeSettings( $settings );

		$typeDef = $this->getTypeDef( $settings[self::PARAM_TYPE] );
		if ( !$typeDef ) {
			throw new DomainException(
				"Param $name's type is unknown - {$settings[self::PARAM_TYPE]}"
			);
		}

		if ( $value === null ) {
			if ( !empty( $settings[self::PARAM_REQUIRED] ) ) {
				throw new ValidationException( $name, $value, $settings, 'missingparam', [] );
			}
			return null;
		}

		// Non-multi
		if ( empty( $settings[self::PARAM_ISMULTI] ) ) {
			return $typeDef->validate( $name, $value, $settings, $options );
		}

		// Split the multi-value and validate each parameter
		$limit1 = $settings[self::PARAM_ISMULTI_LIMIT1] ?? $this->ismultiLimit1;
		$limit2 = $settings[self::PARAM_ISMULTI_LIMIT2] ?? $this->ismultiLimit2;
		$valuesList = is_array( $value ) ? $value : self::explodeMultiValue( $value, $limit2 + 1 );

		// Handle PARAM_ALL
		$enumValues = $typeDef->getEnumValues( $name, $settings, $options );
		if ( is_array( $enumValues ) && isset( $settings[self::PARAM_ALL] ) &&
			count( $valuesList ) === 1
		) {
			$allValue = is_string( $settings[self::PARAM_ALL] )
				? $settings[self::PARAM_ALL]
				: self::ALL_DEFAULT_STRING;
			if ( $valuesList[0] === $allValue ) {
				return $enumValues;
			}
		}

		// Avoid checking useHighLimits() unless it's actually necessary
		$sizeLimit = count( $valuesList ) > $limit1 && $this->callbacks->useHighLimits( $options )
			? $limit2
			: $limit1;
		if ( count( $valuesList ) > $sizeLimit ) {
			throw new ValidationException( $name, $valuesList, $settings, 'toomanyvalues', [
				'limit' => $sizeLimit
			] );
		}

		$options['values-list'] = $valuesList;
		$validValues = [];
		$invalidValues = [];
		foreach ( $valuesList as $v ) {
			try {
				$validValues[] = $typeDef->validate( $name, $v, $settings, $options );
			} catch ( ValidationException $ex ) {
				if ( empty( $settings[self::PARAM_IGNORE_INVALID_VALUES] ) ) {
					throw $ex;
				}
				$invalidValues[] = $v;
			}
		}
		if ( $invalidValues ) {
			$this->callbacks->recordCondition(
				new ValidationException( $name, $value, $settings, 'unrecognizedvalues', [
					'values' => $invalidValues,
				] ),
				$options
			);
		}

		// Throw out duplicates if requested
		if ( empty( $settings[self::PARAM_ALLOW_DUPLICATES] ) ) {
			$validValues = array_values( array_unique( $validValues ) );
		}

		return $validValues;
	}

	/**
	 * Split a multi-valued parameter string, like explode()
	 *
	 * Note that, unlike explode(), this will return an empty array when given
	 * an empty string.
	 *
	 * @param string $value
	 * @param int $limit
	 * @return string[]
	 */
	public static function explodeMultiValue( $value, $limit ) {
		if ( $value === '' || $value === "\x1f" ) {
			return [];
		}

		if ( substr( $value, 0, 1 ) === "\x1f" ) {
			$sep = "\x1f";
			$value = substr( $value, 1 );
		} else {
			$sep = '|';
		}

		return explode( $sep, $value, $limit );
	}

}
