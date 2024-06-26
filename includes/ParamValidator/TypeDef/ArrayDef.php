<?php

namespace MediaWiki\ParamValidator\TypeDef;

use InvalidArgumentException;
use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;
use LogicException;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\TypeDef;

/**
 * Type definition for array structures, typically
 * used for validating JSON request bodies.
 *
 * Failure codes:
 *  - 'notarray': The value is not an array.
 *
 * @since 1.42
 */
class ArrayDef extends TypeDef {

	/**
	 * (object) Schema settings.
	 *
	 */
	public const PARAM_SCHEMA = 'param-schema';

	public function supportsArrays() {
		return true;
	}

	public function validate( $name, $value, array $settings, array $options ) {
		if ( !is_array( $value ) ) {
			// Message used: paramvalidator-notarray
			$this->failure( 'notarray', $name, $value, $settings, $options );
		}

		if ( isset( $settings[ self::PARAM_SCHEMA ] ) ) {
			$schema = $settings[ self::PARAM_SCHEMA ];

			if ( !isset( $schema[ 'type' ] ) ) {
				throw new InvalidArgumentException( "Schema type not set " );
			}

			$types = (array)$schema['type'];
			foreach ( $types as $type ) {
				// @todo:  start using JsonSchemaTrait::normalizeJsonSchema
				// so we can also support the "list" and "map" types
				if ( ( $type !== 'object' ) && ( $type !== 'array' ) ) {
					throw new LogicException( 'Invalid data type' );
				}
			}

			$validator = new Validator();
			$validator->validate( $value, $schema, Constraint::CHECK_MODE_TYPE_CAST );
			if ( !$validator->isValid() ) {
				$errorCode = 'schema-validation-failed';
				foreach ( $validator->getErrors() as $error ) {
					$message = DataMessageValue::new(
						"paramvalidator-$errorCode",
						[ $error[ 'message' ] ],
						$errorCode,
						[ 'schema-validation-error' => $error ]
					);
					$this->failure( $message, $name, $value, $settings, $options );
				}
			}
		}
		return $value;
	}

	public function stringifyValue( $name, $value, array $settings, array $options ) {
		if ( !is_array( $value ) ) {
			return parent::stringifyValue( $name, $value, $settings, $options );
		}

		return json_encode( $value );
	}
}
