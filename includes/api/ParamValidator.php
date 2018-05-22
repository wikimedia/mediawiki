<?php

namespace MediaWiki\Api;

use ApiBase;
use ApiUsageException;
use DomainException;
use Message;

/**
 * Service for fetching API parameters based on API parameter settings arrays
 *
 * A settings array is simply an array with keys being the ApiBase::PARAM_*
 * constants, as returned from ApiBase::getFinalParams() or ApiBase::getAllowedParams().
 *
 * @since 1.32
 * @ingroup API
 */
class ParamValidator {

	/** @var TypeDefRegistry */
	private $typeDefRegistry;

	/**
	 * @param TypeDefRegistry $typeDefRegistry
	 */
	public function __construct( TypeDefRegistry $typeDefRegistry ) {
		$this->typeDefRegistry = $typeDefRegistry;
	}

	/**
	 * Get the TypeDef for a type
	 * @param string $type
	 * @return TypeDef
	 */
	public function getTypeDef( $type ) {
		return $this->typeDefRegistry->getTypeDef( $type );
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
				ApiBase::PARAM_DFLT => $settings,
			];
		}

		// When type is not given, determine it from the type of the PARAM_DFLT
		if ( !isset( $settings[ApiBase::PARAM_TYPE] ) ) {
			if ( isset( $settings[ApiBase::PARAM_DFLT] ) ) {
				$settings[ApiBase::PARAM_TYPE] = gettype( $settings[ApiBase::PARAM_DFLT] );
			} else {
				$settings[ApiBase::PARAM_TYPE] = 'NULL'; // allow everything
			}
		}

		$typeDef = $this->getTypeDef( $settings[ApiBase::PARAM_TYPE] );
		if ( $typeDef ) {
			$settings = $typeDef->normalizeSettings( $settings );
		}

		return $settings;
	}

	/**
	 * Fetch and valiate a parameter value using a settings array
	 * @param string $name Parameter name
	 * @param array|mixed $settings Default value or an array of settings
	 *  using PARAM_* constants.
	 * @param ApiBase $module Module for which the parameter is being validated
	 * @param bool $parseLimit Whether to parse and validate 'limit' parameters
	 * @return mixed Validated parameter value
	 * @throws ApiUsageException if the value is invalid
	 */
	public function getValue( $name, $settings, ApiBase $module, $parseLimit = true ) {
		$encParamName = $module->encodeParamName( $name );

		$settings = $this->normalizeSettings( $settings );

		$typeDef = $this->getTypeDef( $settings[ApiBase::PARAM_TYPE] );
		if ( !$typeDef ) {
			throw new DomainException(
				"Param $encParamName's type is unknown - {$settings[ApiBase::PARAM_TYPE]}"
			);
		}

		$value = $typeDef->get( $name, $settings, $module );

		if ( $value !== null ) {
			if ( !empty( $settings[ApiBase::PARAM_SENSITIVE] ) ) {
				$module->getMain()->markParamsSensitive( $encParamName );
			}

			// Set a warning if a deprecated parameter has been passed
			if ( !empty( $settings[ApiBase::PARAM_DEPRECATED] ) ) {
				$feature = $encParamName;
				$m = $module;
				while ( !$m->isMain() ) {
					$p = $m->getParent();
					$moduleName = $m->getModuleName();
					$param = $p->encodeParamName( $p->getModuleManager()->getModuleGroup( $moduleName ) );
					$feature = "{$param}={$moduleName}&{$feature}";
					$m = $p;
				}
				$module->addDeprecation( [ 'apiwarn-deprecation-parameter', $encParamName ], $feature );
			}
		} elseif ( isset( $settings[ApiBase::PARAM_DFLT] ) ) {
			$value = $settings[ApiBase::PARAM_DFLT];
		}

		return $this->validateValue( $name, $value, $settings, $module, $parseLimit );
	}

	/**
	 * Valiate a parameter value using a settings array
	 * @param string $name Parameter name
	 * @param null|mixed $value Parameter value
	 * @param array|mixed $settings Default value or an array of settings
	 *  using PARAM_* constants.
	 * @param ApiBase $module Module for which the parameter is being validated
	 * @param bool $parseLimit Whether to parse and validate 'limit' parameters
	 * @return mixed Validated parameter value
	 * @throws ApiUsageException if the value is invalid
	 */
	public function validateValue( $name, $value, $settings, ApiBase $module, $parseLimit = true ) {
		$settings = $this->normalizeSettings( $settings );
		$settings += [
			'parse-limit' => $parseLimit,
			'set-parsed-limit' => true,
		];

		$typeDef = $this->getTypeDef( $settings[ApiBase::PARAM_TYPE] );
		if ( !$typeDef ) {
			$encParamName = $module->encodeParamName( $name );
			throw new DomainException(
				"Param $encParamName's type is unknown - {$settings[ApiBase::PARAM_TYPE]}"
			);
		}

		if ( $value === null ) {
			if ( !empty( $settings[ApiBase::PARAM_REQUIRED] ) ) {
				$encParamName = $module->encodeParamName( $name );
				$module->dieWithError( [ 'apierror-missingparam', $encParamName ] );
			}
			return null;
		}

		// Non-multi
		if ( empty( $settings[ApiBase::PARAM_ISMULTI] ) ) {
			return $typeDef->validate( $name, $value, $settings, $module );
		}

		// Split the multi-value and validate each parameter
		$limit1 = isset( $settings[ApiBase::PARAM_ISMULTI_LIMIT1] )
			? $settings[ApiBase::PARAM_ISMULTI_LIMIT1]
			: ApiBase::LIMIT_SML1;
		$limit2 = isset( $settings[ApiBase::PARAM_ISMULTI_LIMIT2] )
			? $settings[ApiBase::PARAM_ISMULTI_LIMIT2]
			: ApiBase::LIMIT_SML2;
		$valuesList = self::explodeMultiValue( $value, $limit2 + 1 );

		// Handle PARAM_ALL
		$enumValues = $typeDef->getEnumValues( $name, $settings, $module );
		if ( is_array( $enumValues ) && isset( $settings[ApiBase::PARAM_ALL] ) &&
			count( $valuesList ) === 1
		) {
			$allValue = is_string( $settings[ApiBase::PARAM_ALL] )
				? $settings[ApiBase::PARAM_ALL]
				: ApiBase::ALL_DEFAULT_STRING;
			if ( $valuesList[0] === $allValue ) {
				return $enumValues;
			}
		}

		// Avoid checking canApiHighLimits() unless it's actually necessary
		$sizeLimit = count( $valuesList ) > $limit1 && $module->getMain()->canApiHighLimits()
			? $limit2
			: $limit1;
		if ( count( $valuesList ) > $sizeLimit ) {
			$encParamName = $module->encodeParamName( $name );
			$module->dieWithError(
				[ 'apierror-toomanyvalues', $encParamName, $sizeLimit ],
				"too-many-$encParamName"
			);
		}

		$settings['values-list'] = $valuesList;
		$validValues = [];
		$invalidValues = [];
		foreach ( $valuesList as $v ) {
			try {
				$validValues[] = $typeDef->validate( $name, $v, $settings, $module );
			} catch ( ApiUsageException $ex ) {
				if ( empty( $settings[ApiBase::PARAM_IGNORE_INVALID_VALUES] ) ) {
					throw $ex;
				}
				$invalidValues[] = wfEscapeWikiText( $v );
			}
		}
		if ( $invalidValues ) {
			$encParamName = $module->encodeParamName( $name );
			$module->addWarning( [
				'apiwarn-unrecognizedvalues',
				$encParamName,
				Message::listParam( $invalidValues, 'comma' ),
				count( $invalidValues ),
			] );
		}

		// Throw out duplicates if requested
		if ( empty( $settings[ApiBase::PARAM_ALLOW_DUPLICATES] ) ) {
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
