<?php

namespace Wikimedia\ParamValidator;

use DomainException;
use InvalidArgumentException;
use Wikimedia\Assert\Assert;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;
use Wikimedia\ObjectFactory\ObjectFactory;

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

	// region    Constants for parameter settings arrays
	/** @name    Constants for parameter settings arrays
	 * These constants are keys in the settings array that define how the
	 * parameters coming in from the request are to be interpreted.
	 *
	 * If a constant is associated with a failure code, the failure code
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

	/**
	 * (mixed) Default value of the parameter. If omitted, null is the default.
	 *
	 * TypeDef::validate() will be informed when the default value was used by the presence of
	 * 'is-default' in $options.
	 */
	public const PARAM_DEFAULT = 'param-default';

	/**
	 * (string|array) Type of the parameter.
	 * Must be a registered type or an array of enumerated values (in which case the "enum"
	 * type must be registered). If omitted, the default is the PHP type of the default value
	 * (see PARAM_DEFAULT).
	 */
	public const PARAM_TYPE = 'param-type';

	/**
	 * (bool) Indicate that the parameter is required.
	 *
	 * Failure codes:
	 *  - 'missingparam': The parameter is omitted/empty (and no default was set). No data.
	 */
	public const PARAM_REQUIRED = 'param-required';

	/**
	 * (bool) Indicate that the parameter is multi-valued.
	 *
	 * A multi-valued parameter may be submitted in one of several formats. All
	 * of the following result in a value of `[ 'a', 'b', 'c' ]`.
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
	 * Failure codes:
	 *  - 'toomanyvalues': More values were supplied than are allowed. See
	 *    PARAM_ISMULTI_LIMIT1, PARAM_ISMULTI_LIMIT2, and constructor option
	 *    'ismultiLimits'. Data:
	 *     - 'limit': The limit currently in effect.
	 *     - 'lowlimit': The limit when high limits are not allowed.
	 *     - 'highlimit': The limit when high limits are allowed.
	 *  - 'unrecognizedvalues': Non-fatal. Invalid values were passed and
	 *    PARAM_IGNORE_UNRECOGNIZED_VALUES was set. Data:
	 *     - 'values': The unrecognized values.
	 */
	public const PARAM_ISMULTI = 'param-ismulti';

	/**
	 * (int) Maximum number of multi-valued parameter values allowed
	 *
	 * @see PARAM_ISMULTI
	 */
	public const PARAM_ISMULTI_LIMIT1 = 'param-ismulti-limit1';

	/**
	 * (int) Maximum number of multi-valued parameter values allowed for users
	 * allowed high limits.
	 *
	 * @see PARAM_ISMULTI
	 */
	public const PARAM_ISMULTI_LIMIT2 = 'param-ismulti-limit2';

	/**
	 * (bool|string) Whether a magic "all values" value exists for multi-valued
	 * enumerated types, and if so what that value is.
	 *
	 * When PARAM_TYPE has a defined set of values and PARAM_ISMULTI is true,
	 * this allows for an asterisk ('*') to be passed in place of a pipe-separated list of
	 * every possible value. If a string is set, it will be used in place of the asterisk.
	 */
	public const PARAM_ALL = 'param-all';

	/**
	 * (bool) Allow the same value to be set more than once when PARAM_ISMULTI is true?
	 *
	 * If not truthy, the set of values will be passed through
	 * `array_values( array_unique() )`. The default is falsey.
	 */
	public const PARAM_ALLOW_DUPLICATES = 'param-allow-duplicates';

	/**
	 * (bool) Indicate that the parameter's value should not be logged.
	 *
	 * Failure codes: (non-fatal)
	 *  - 'param-sensitive': Always recorded when the parameter is used.
	 */
	public const PARAM_SENSITIVE = 'param-sensitive';

	/**
	 * (bool) Indicate that a deprecated parameter was used.
	 *
	 * Failure codes: (non-fatal)
	 *  - 'param-deprecated': Always recorded when the parameter is used.
	 */
	public const PARAM_DEPRECATED = 'param-deprecated';

	/**
	 * (bool) Whether to downgrade "badvalue" errors to non-fatal when validating multi-valued
	 * parameters.
	 * @see PARAM_ISMULTI
	 */
	public const PARAM_IGNORE_UNRECOGNIZED_VALUES = 'param-ignore-unrecognized-values';

	/** @} */
	// endregion -- end of Constants for parameter settings arrays

	/**
	 * @see TypeDef::OPT_ENFORCE_JSON_TYPES
	 */
	public const OPT_ENFORCE_JSON_TYPES = TypeDef::OPT_ENFORCE_JSON_TYPES;

	/** Magic "all values" value when PARAM_ALL is true. */
	public const ALL_DEFAULT_STRING = '*';

	/** A list of standard type names and types that may be passed as `$typeDefs` to __construct(). */
	public const STANDARD_TYPES = [
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
				TypeDef\StringDef::OPT_ALLOW_EMPTY => true,
			] ],
		],
		'timestamp' => [ 'class' => TypeDef\TimestampDef::class ],
		'upload' => [ 'class' => TypeDef\UploadDef::class ],
		'enum' => [ 'class' => TypeDef\EnumDef::class ],
		'expiry' => [ 'class' => TypeDef\ExpiryDef::class ],
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
	 *  - 'typeDefs': (array) As for addTypeDefs(). If omitted, self::STANDARD_TYPES will be used.
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

		$this->addTypeDefs( $options['typeDefs'] ?? self::STANDARD_TYPES );
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
			[ TypeDef::class, 'array' ],
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
			[ TypeDef::class, 'array', 'null' ],
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
	 * Logic shared by normalizeSettings() and checkSettings()
	 * @param array|mixed $settings
	 * @return array
	 */
	private function normalizeSettingsInternal( $settings ) {
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

		return $settings;
	}

	/**
	 * Normalize a parameter settings array
	 * @param array|mixed $settings Default value or an array of settings
	 *  using PARAM_* constants.
	 * @return array
	 */
	public function normalizeSettings( $settings ) {
		$settings = $this->normalizeSettingsInternal( $settings );

		$typeDef = $this->getTypeDef( $settings[self::PARAM_TYPE] );
		if ( $typeDef ) {
			$settings = $typeDef->normalizeSettings( $settings );
		}

		return $settings;
	}

	/**
	 * Validate a parameter settings array
	 *
	 * This is intended for validation of parameter settings during unit or
	 * integration testing, and should implement strict checks.
	 *
	 * The rest of the code should generally be more permissive.
	 *
	 * @param string $name Parameter name
	 * @param array|mixed $settings Default value or an array of settings
	 *  using PARAM_* constants.
	 * @param array $options Options array, passed through to the TypeDef and Callbacks.
	 * @return array
	 *  - 'issues': (string[]) Errors detected in $settings, as English text. If the settings
	 *    are valid, this will be the empty array.
	 *  - 'allowedKeys': (string[]) ParamValidator keys that are allowed in `$settings`.
	 *  - 'messages': (MessageValue[]) Messages to be checked for existence.
	 */
	public function checkSettings( string $name, $settings, array $options ): array {
		$settings = $this->normalizeSettingsInternal( $settings );
		$issues = [];
		$allowedKeys = [
			self::PARAM_TYPE, self::PARAM_DEFAULT, self::PARAM_REQUIRED, self::PARAM_ISMULTI,
			self::PARAM_SENSITIVE, self::PARAM_DEPRECATED, self::PARAM_IGNORE_UNRECOGNIZED_VALUES,
		];
		$messages = [];

		$type = $settings[self::PARAM_TYPE];
		$typeDef = null;
		if ( !is_string( $type ) && !is_array( $type ) ) {
			$issues[self::PARAM_TYPE] = 'PARAM_TYPE must be a string or array, got ' . gettype( $type );
		} else {
			$typeDef = $this->getTypeDef( $settings[self::PARAM_TYPE] );
			if ( !$typeDef ) {
				if ( is_array( $type ) ) {
					$type = 'enum';
				}
				$issues[self::PARAM_TYPE] = "Unknown/unregistered PARAM_TYPE \"$type\"";
			}
		}

		if ( isset( $settings[self::PARAM_DEFAULT] ) ) {
			try {
				$this->validateValue(
					$name, $settings[self::PARAM_DEFAULT], $settings, [ 'is-default' => true ] + $options
				);
			} catch ( ValidationException $ex ) {
				$issues[self::PARAM_DEFAULT] = 'Value for PARAM_DEFAULT does not validate (code '
					. $ex->getFailureMessage()->getCode() . ')';
			}
		}

		if ( !is_bool( $settings[self::PARAM_REQUIRED] ?? false ) ) {
			$issues[self::PARAM_REQUIRED] = 'PARAM_REQUIRED must be boolean, got '
				. gettype( $settings[self::PARAM_REQUIRED] );
		}

		if ( !is_bool( $settings[self::PARAM_ISMULTI] ?? false ) ) {
			$issues[self::PARAM_ISMULTI] = 'PARAM_ISMULTI must be boolean, got '
				. gettype( $settings[self::PARAM_ISMULTI] );
		}

		if ( !empty( $settings[self::PARAM_ISMULTI] ) ) {
			$allowedKeys = array_merge( $allowedKeys, [
				self::PARAM_ISMULTI_LIMIT1, self::PARAM_ISMULTI_LIMIT2,
				self::PARAM_ALL, self::PARAM_ALLOW_DUPLICATES
			] );

			$limit1 = $settings[self::PARAM_ISMULTI_LIMIT1] ?? $this->ismultiLimit1;
			$limit2 = $settings[self::PARAM_ISMULTI_LIMIT2] ?? $this->ismultiLimit2;
			if ( !is_int( $limit1 ) ) {
				$issues[self::PARAM_ISMULTI_LIMIT1] = 'PARAM_ISMULTI_LIMIT1 must be an integer, got '
					. gettype( $settings[self::PARAM_ISMULTI_LIMIT1] );
			} elseif ( $limit1 <= 0 ) {
				$issues[self::PARAM_ISMULTI_LIMIT1] =
					"PARAM_ISMULTI_LIMIT1 must be greater than 0, got $limit1";
			}
			if ( !is_int( $limit2 ) ) {
				$issues[self::PARAM_ISMULTI_LIMIT2] = 'PARAM_ISMULTI_LIMIT2 must be an integer, got '
					. gettype( $settings[self::PARAM_ISMULTI_LIMIT2] );
			} elseif ( $limit2 < $limit1 ) {
				$issues[self::PARAM_ISMULTI_LIMIT2] =
					'PARAM_ISMULTI_LIMIT2 must be greater than or equal to PARAM_ISMULTI_LIMIT1, but '
					. "$limit2 < $limit1";
			}

			$all = $settings[self::PARAM_ALL] ?? false;
			if ( !is_string( $all ) && !is_bool( $all ) ) {
				$issues[self::PARAM_ALL] = 'PARAM_ALL must be a string or boolean, got ' . gettype( $all );
			} elseif ( $all !== false && $typeDef ) {
				if ( $all === true ) {
					$all = self::ALL_DEFAULT_STRING;
				}
				$values = $typeDef->getEnumValues( $name, $settings, $options );
				if ( !is_array( $values ) ) {
					$issues[self::PARAM_ALL] = 'PARAM_ALL cannot be used with non-enumerated types';
				} elseif ( in_array( $all, $values, true ) ) {
					$issues[self::PARAM_ALL] = 'Value for PARAM_ALL conflicts with an enumerated value';
				}
			}

			if ( !is_bool( $settings[self::PARAM_ALLOW_DUPLICATES] ?? false ) ) {
				$issues[self::PARAM_ALLOW_DUPLICATES] = 'PARAM_ALLOW_DUPLICATES must be boolean, got '
					. gettype( $settings[self::PARAM_ALLOW_DUPLICATES] );
			}
		}

		if ( !is_bool( $settings[self::PARAM_SENSITIVE] ?? false ) ) {
			$issues[self::PARAM_SENSITIVE] = 'PARAM_SENSITIVE must be boolean, got '
				. gettype( $settings[self::PARAM_SENSITIVE] );
		}

		if ( !is_bool( $settings[self::PARAM_DEPRECATED] ?? false ) ) {
			$issues[self::PARAM_DEPRECATED] = 'PARAM_DEPRECATED must be boolean, got '
				. gettype( $settings[self::PARAM_DEPRECATED] );
		}

		if ( !is_bool( $settings[self::PARAM_IGNORE_UNRECOGNIZED_VALUES] ?? false ) ) {
			$issues[self::PARAM_IGNORE_UNRECOGNIZED_VALUES] = 'PARAM_IGNORE_UNRECOGNIZED_VALUES must be '
				. 'boolean, got ' . gettype( $settings[self::PARAM_IGNORE_UNRECOGNIZED_VALUES] );
		}

		$ret = [ 'issues' => $issues, 'allowedKeys' => $allowedKeys, 'messages' => $messages ];
		if ( $typeDef ) {
			$ret = $typeDef->checkSettings( $name, $settings, $options, $ret );
		}

		return $ret;
	}

	/**
	 * Fetch and validate a parameter value using a settings array
	 *
	 * @param string $name Parameter name
	 * @param array|mixed $settings Default value or an array of settings
	 *  using PARAM_* constants.
	 * @param array $options Options array, passed through to the TypeDef and Callbacks.
	 *  - An additional option, 'is-default', will be set when the value comes from PARAM_DEFAULT.
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
				$strValue = $typeDef->stringifyValue( $name, $value, $settings, $options );
				$this->callbacks->recordCondition(
					DataMessageValue::new( 'paramvalidator-param-sensitive', [], 'param-sensitive' )
						->plaintextParams( $name, $strValue ),
					$name, $value, $settings, $options
				);
			}

			// Set a warning if a deprecated parameter has been passed
			if ( !empty( $settings[self::PARAM_DEPRECATED] ) ) {
				$strValue = $typeDef->stringifyValue( $name, $value, $settings, $options );
				$this->callbacks->recordCondition(
					DataMessageValue::new( 'paramvalidator-param-deprecated', [], 'param-deprecated' )
						->plaintextParams( $name, $strValue ),
					$name, $value, $settings, $options
				);
			}
		} elseif ( isset( $settings[self::PARAM_DEFAULT] ) ) {
			$value = $settings[self::PARAM_DEFAULT];
			$options['is-default'] = true;
		}

		return $this->validateValue( $name, $value, $settings, $options );
	}

	/**
	 * Validate a parameter value using a settings array
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
				throw new ValidationException(
					DataMessageValue::new( 'paramvalidator-missingparam', [], 'missingparam' )
						->plaintextParams( $name ),
					$name, $value, $settings
				);
			}
			return null;
		}

		// Non-multi
		if ( empty( $settings[self::PARAM_ISMULTI] ) ) {
			if ( is_string( $value ) && str_starts_with( $value, "\x1f" ) ) {
				throw new ValidationException(
					DataMessageValue::new( 'paramvalidator-notmulti', [], 'badvalue' )
						->plaintextParams( $name, $value ),
					$name, $value, $settings
				);
			}

			// T326764: If the type of the actual param value is different from
			// the type that is defined via getParamSettings(), throw an exception
			// because this is a type to value mismatch.
			if ( is_array( $value ) && !$typeDef->supportsArrays() ) {
				throw new ValidationException(
					DataMessageValue::new( 'paramvalidator-notmulti', [], 'badvalue' )
						->plaintextParams( $name, gettype( $value ) ),
					$name, $value, $settings
				);
			}

			return $typeDef->validate( $name, $value, $settings, $options );
		}

		// Split the multi-value and validate each parameter
		$limit1 = $settings[self::PARAM_ISMULTI_LIMIT1] ?? $this->ismultiLimit1;
		$limit2 = max( $limit1, $settings[self::PARAM_ISMULTI_LIMIT2] ?? $this->ismultiLimit2 );

		if ( is_array( $value ) ) {
			$valuesList = $value;
		} elseif ( $options[ self::OPT_ENFORCE_JSON_TYPES ] ?? false ) {
			throw new ValidationException(
				DataMessageValue::new(
					'paramvalidator-multivalue-must-be-array',
					[],
					'multivalue-must-be-array'
				)->plaintextParams( $name ),
				$name, $value, $settings
			);
		} else {
			$valuesList = self::explodeMultiValue( $value, $limit2 + 1 );
		}

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
		$sizeLimit = (
			$limit2 > $limit1 && count( $valuesList ) > $limit1 &&
			$this->callbacks->useHighLimits( $options )
		) ? $limit2 : $limit1;
		if ( count( $valuesList ) > $sizeLimit ) {
			throw new ValidationException(
				DataMessageValue::new( 'paramvalidator-toomanyvalues', [], 'toomanyvalues', [
					'parameter' => $name,
					'limit' => $sizeLimit,
					'lowlimit' => $limit1,
					'highlimit' => $limit2,
				] )->plaintextParams( $name )->numParams( $sizeLimit ),
				$name, $valuesList, $settings
			);
		}

		$options['values-list'] = $valuesList;
		$validValues = [];
		$invalidValues = [];
		foreach ( $valuesList as $v ) {
			try {
				$validValues[] = $typeDef->validate( $name, $v, $settings, $options );
			} catch ( ValidationException $ex ) {
				if ( $ex->getFailureMessage()->getCode() !== 'badvalue' ||
					empty( $settings[self::PARAM_IGNORE_UNRECOGNIZED_VALUES] )
				) {
					throw $ex;
				}
				$invalidValues[] = $v;
			}
		}
		if ( $invalidValues ) {
			if ( is_array( $value ) ) {
				$value = self::implodeMultiValue( $value );
			}
			$this->callbacks->recordCondition(
				DataMessageValue::new( 'paramvalidator-unrecognizedvalues', [], 'unrecognizedvalues', [
					'values' => $invalidValues,
				] )
					->plaintextParams( $name, $value )
					->commaListParams( array_map( static function ( $v ) {
						return new ScalarParam( ParamType::PLAINTEXT, $v );
					}, $invalidValues ) )
					->numParams( count( $invalidValues ) ),
				$name, $value, $settings, $options
			);
		}

		// Throw out duplicates if requested
		if ( empty( $settings[self::PARAM_ALLOW_DUPLICATES] ) ) {
			$validValues = array_values( array_unique( $validValues ) );
		}

		return $validValues;
	}

	/**
	 * Describe parameter settings in a machine-readable format.
	 *
	 * @param string $name Parameter name.
	 * @param array|mixed $settings Default value or an array of settings
	 *  using PARAM_* constants.
	 * @param array $options Options array.
	 * @return array
	 */
	public function getParamInfo( $name, $settings, array $options ) {
		$settings = $this->normalizeSettings( $settings );
		$typeDef = $this->getTypeDef( $settings[self::PARAM_TYPE] );
		$info = [];

		$info['type'] = $settings[self::PARAM_TYPE];
		$info['required'] = !empty( $settings[self::PARAM_REQUIRED] );
		if ( !empty( $settings[self::PARAM_DEPRECATED] ) ) {
			$info['deprecated'] = true;
		}
		if ( !empty( $settings[self::PARAM_SENSITIVE] ) ) {
			$info['sensitive'] = true;
		}
		if ( isset( $settings[self::PARAM_DEFAULT] ) ) {
			$info['default'] = $settings[self::PARAM_DEFAULT];
		}
		$info['multi'] = !empty( $settings[self::PARAM_ISMULTI] );
		if ( $info['multi'] ) {
			$info['lowlimit'] = $settings[self::PARAM_ISMULTI_LIMIT1] ?? $this->ismultiLimit1;
			$info['highlimit'] = max(
				$info['lowlimit'], $settings[self::PARAM_ISMULTI_LIMIT2] ?? $this->ismultiLimit2
			);
			$info['limit'] =
				$info['highlimit'] > $info['lowlimit'] && $this->callbacks->useHighLimits( $options )
					? $info['highlimit']
					: $info['lowlimit'];

			if ( !empty( $settings[self::PARAM_ALLOW_DUPLICATES] ) ) {
				$info['allowsduplicates'] = true;
			}

			$allSpecifier = $settings[self::PARAM_ALL] ?? false;
			if ( $allSpecifier !== false ) {
				if ( !is_string( $allSpecifier ) ) {
					$allSpecifier = self::ALL_DEFAULT_STRING;
				}
				$info['allspecifier'] = $allSpecifier;
			}
		}

		if ( $typeDef ) {
			$info = array_merge( $info, $typeDef->getParamInfo( $name, $settings, $options ) );
		}

		// Filter out nulls (strictly)
		return array_filter( $info, static function ( $v ) {
			return $v !== null;
		} );
	}

	/**
	 * Describe parameter settings in human-readable format
	 *
	 * @param string $name Parameter name being described.
	 * @param array|mixed $settings Default value or an array of settings
	 *  using PARAM_* constants.
	 * @param array $options Options array.
	 * @return MessageValue[]
	 */
	public function getHelpInfo( $name, $settings, array $options ) {
		$settings = $this->normalizeSettings( $settings );
		$typeDef = $this->getTypeDef( $settings[self::PARAM_TYPE] );

		// Define ordering. Some are overwritten below, some expected from the TypeDef
		$info = [
			self::PARAM_DEPRECATED => null,
			self::PARAM_REQUIRED => null,
			self::PARAM_SENSITIVE => null,
			self::PARAM_TYPE => null,
			self::PARAM_ISMULTI => null,
			self::PARAM_ISMULTI_LIMIT1 => null,
			self::PARAM_ALL => null,
			self::PARAM_DEFAULT => null,
		];

		if ( !empty( $settings[self::PARAM_DEPRECATED] ) ) {
			$info[self::PARAM_DEPRECATED] = MessageValue::new( 'paramvalidator-help-deprecated' );
		}

		if ( !empty( $settings[self::PARAM_REQUIRED] ) ) {
			$info[self::PARAM_REQUIRED] = MessageValue::new( 'paramvalidator-help-required' );
		}

		if ( !empty( $settings[self::PARAM_ISMULTI] ) ) {
			$info[self::PARAM_ISMULTI] = MessageValue::new( 'paramvalidator-help-multi-separate' );

			$lowcount = $settings[self::PARAM_ISMULTI_LIMIT1] ?? $this->ismultiLimit1;
			$highcount = max( $lowcount, $settings[self::PARAM_ISMULTI_LIMIT2] ?? $this->ismultiLimit2 );
			$values = $typeDef ? $typeDef->getEnumValues( $name, $settings, $options ) : null;
			if (
				// Only mention the limits if they're likely to matter.
				$values === null || count( $values ) > $lowcount ||
				!empty( $settings[self::PARAM_ALLOW_DUPLICATES] )
			) {
				if ( $highcount > $lowcount ) {
					$info[self::PARAM_ISMULTI_LIMIT1] = MessageValue::new( 'paramvalidator-help-multi-max' )
						->numParams( $lowcount, $highcount );
				} else {
					$info[self::PARAM_ISMULTI_LIMIT1] = MessageValue::new( 'paramvalidator-help-multi-max-simple' )
						->numParams( $lowcount );
				}
			}

			$allSpecifier = $settings[self::PARAM_ALL] ?? false;
			if ( $allSpecifier !== false ) {
				if ( !is_string( $allSpecifier ) ) {
					$allSpecifier = self::ALL_DEFAULT_STRING;
				}
				$info[self::PARAM_ALL] = MessageValue::new( 'paramvalidator-help-multi-all' )
					->plaintextParams( $allSpecifier );
			}
		}

		if ( isset( $settings[self::PARAM_DEFAULT] ) && $typeDef ) {
			$value = $typeDef->stringifyValue( $name, $settings[self::PARAM_DEFAULT], $settings, $options );
			if ( $value === '' ) {
				$info[self::PARAM_DEFAULT] = MessageValue::new( 'paramvalidator-help-default-empty' );
			} elseif ( $value !== null ) {
				$info[self::PARAM_DEFAULT] = MessageValue::new( 'paramvalidator-help-default' )
					->plaintextParams( $value );
			}
		}

		if ( $typeDef ) {
			$info = array_merge( $info, $typeDef->getHelpInfo( $name, $settings, $options ) );
		}

		// Put the default at the very end (the TypeDef may have added extra messages)
		$default = $info[self::PARAM_DEFAULT];
		unset( $info[self::PARAM_DEFAULT] );
		$info[self::PARAM_DEFAULT] = $default;

		// Filter out nulls
		return array_filter( $info );
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

		if ( str_starts_with( $value, "\x1f" ) ) {
			$sep = "\x1f";
			$value = substr( $value, 1 );
		} else {
			$sep = '|';
		}

		return explode( $sep, $value, $limit );
	}

	/**
	 * Implode an array as a multi-valued parameter string, like implode()
	 *
	 * @param array $value
	 * @return string
	 */
	public static function implodeMultiValue( array $value ) {
		if ( $value === [ '' ] ) {
			// There's no value that actually returns a single empty string.
			// Best we can do is this that returns two, which will be deduplicated to one.
			return '|';
		}

		foreach ( $value as $v ) {
			if ( strpos( $v, '|' ) !== false ) {
				return "\x1f" . implode( "\x1f", $value );
			}
		}
		return implode( '|', $value );
	}

}
