<?php

namespace MediaWiki\Rest\Validator;

use MediaWiki\ParamValidator\TypeDef\ArrayDef;
use MediaWiki\ParamValidator\TypeDef\TitleDef;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestInterface;
use Wikimedia\Message\ListParam;
use Wikimedia\Message\ListType;
use Wikimedia\Message\MessageValue;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\BooleanDef;
use Wikimedia\ParamValidator\TypeDef\EnumDef;
use Wikimedia\ParamValidator\TypeDef\ExpiryDef;
use Wikimedia\ParamValidator\TypeDef\FloatDef;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;
use Wikimedia\ParamValidator\TypeDef\PasswordDef;
use Wikimedia\ParamValidator\TypeDef\StringDef;
use Wikimedia\ParamValidator\TypeDef\TimestampDef;
use Wikimedia\ParamValidator\TypeDef\UploadDef;
use Wikimedia\ParamValidator\ValidationException;

/**
 * Wrapper for ParamValidator
 *
 * It's intended to be used in the REST API classes by composition.
 *
 * @since 1.34
 */
class Validator {

	/**
	 * (array) ParamValidator array to specify the known sources of the parameter.
	 * 'post' refers to application/x-www-form-urlencoded or multipart/form-data encoded parameters
	 * in the body of a POST request (in other words, parameters in PHP's $_POST). For other kinds
	 * of POST parameters, such as JSON fields, use BodyValidator instead of ParamValidator.
	 * This list must correspond to the switch statement in ParamValidatorCallbacks::getParamsFromSource.
	 *
	 * @since 1.42
	 */
	public const KNOWN_PARAM_SOURCES = [ 'path', 'query', 'body', 'post' ];

	/**
	 * (string) ParamValidator constant for use as a key in a param settings array
	 * to specify the source of the parameter.
	 * Value must be one of the values in KNOWN_PARAM_SOURCES.
	 */
	public const PARAM_SOURCE = 'rest-param-source';

	/**
	 * Parameter description to use in generated documentation
	 */
	public const PARAM_DESCRIPTION = 'rest-param-description';

	/** @var array Type defs for ParamValidator */
	private const TYPE_DEFS = [
		'boolean' => [ 'class' => BooleanDef::class ],
		'enum' => [ 'class' => EnumDef::class ],
		'integer' => [ 'class' => IntegerDef::class ],
		'float' => [ 'class' => FloatDef::class ],
		'double' => [ 'class' => FloatDef::class ],
		'NULL' => [
			'class' => StringDef::class,
			'args' => [ [
				'allowEmptyWhenRequired' => true,
			] ],
		],
		'password' => [ 'class' => PasswordDef::class ],
		'string' => [ 'class' => StringDef::class ],
		'timestamp' => [ 'class' => TimestampDef::class ],
		'upload' => [ 'class' => UploadDef::class ],
		'expiry' => [ 'class' => ExpiryDef::class ],
		'title' => [
			'class' => TitleDef::class,
			'services' => [ 'TitleFactory' ],
		],
		'user' => [
			'class' => UserDef::class,
			'services' => [ 'UserIdentityLookup', 'TitleParser', 'UserNameUtils' ]
		],
		'array' => [
			'class' => ArrayDef::class,
		],
	];

	/** @var string[] HTTP request methods that we expect never to have a payload */
	private const NO_BODY_METHODS = [ 'GET', 'HEAD' ];

	/** @var string[] HTTP request methods that we expect always to have a payload */
	private const BODY_METHODS = [ 'POST', 'PUT' ];

	// NOTE: per RFC 7231 (https://www.rfc-editor.org/rfc/rfc7231#section-4.3.5), sending a body
	// with the DELETE method "has no defined semantics". We allow it, as it is useful for
	// passing the csrf token required by some authentication methods.

	/** @var string[] Content types handled via $_POST */
	private const FORM_DATA_CONTENT_TYPES = [
		'application/x-www-form-urlencoded',
		'multipart/form-data',
	];

	private ParamValidator $paramValidator;

	/**
	 * @param ObjectFactory $objectFactory
	 * @param RequestInterface $request
	 * @param Authority $authority
	 * @internal
	 */
	public function __construct(
		ObjectFactory $objectFactory,
		RequestInterface $request,
		Authority $authority
	) {
		$this->paramValidator = new ParamValidator(
			new ParamValidatorCallbacks( $request, $authority ),
			$objectFactory,
			[
				'typeDefs' => self::TYPE_DEFS,
			]
		);
	}

	/**
	 * Validate parameters.
	 * Params with the source specified as 'body' will be ignored.
	 * Use validateBodyParams() for these.
	 *
	 * @see validateBodyParams
	 * @param array[] $paramSettings Parameter settings
	 * @return array Validated parameters
	 * @throws HttpException on validation failure
	 */
	public function validateParams( array $paramSettings ) {
		$validatedParams = [];
		foreach ( $paramSettings as $name => $settings ) {
			try {
				$source = $settings[Handler::PARAM_SOURCE] ?? 'unspecified';
				if ( $source === 'body' ) {
					continue;
				}

				$validatedParams[$name] = $this->paramValidator->getValue( $name, $settings, [
					'source' => $source,
				] );
			} catch ( ValidationException $e ) {
				// NOTE: error data structure must match the one used by validateBodyParams
				throw new LocalizedHttpException( $e->getFailureMessage(), 400, [
					'error' => 'parameter-validation-failed',
					'name' => $e->getParamName(),
					'value' => $e->getParamValue(),
					'failureCode' => $e->getFailureMessage()->getCode(),
					'failureData' => $e->getFailureMessage()->getData(),
				] );
			}
		}
		return $validatedParams;
	}

	/**
	 * Throw an HttpException if there are unexpected body fields.
	 *
	 * Note that this will ignore all body fields if $paramSettings does not
	 * declare any body parameters, to avoid failures when clients send spurious
	 * data to handlers that do not support body validation at all. This
	 * behavior may change in the future.
	 *
	 * @param array $paramSettings
	 * @param array $parsedBody
	 *
	 * @throws LocalizedHttpException if there are unexpected body fields.
	 */
	public function detectExtraneousBodyFields( array $paramSettings, array $parsedBody ) {
		$validatedKeys = [];
		$remainingBodyFields = $parsedBody;
		foreach ( $paramSettings as $name => $settings ) {
			$source = $settings[Handler::PARAM_SOURCE] ?? 'unspecified';

			if ( $source !== 'body' ) {
				continue;
			}

			$validatedKeys[] = $name;
			unset( $remainingBodyFields[$name] );
		}
		$unvalidatedKeys = array_keys( $remainingBodyFields );

		// Throw if there are unvalidated keys left and there are body params defined.
		// If there are no known body params declared, we just ignore any body
		// data coming from the client. This works around that fact that "post"
		// params also show up in the parsed body. That means that mixing "body"
		// and "post" params will trigger an error here. Any "post" params should
		// be converted to "body".
		if ( $validatedKeys && $unvalidatedKeys ) {
			throw new LocalizedHttpException(
				new MessageValue(
					'rest-extraneous-body-fields',
					[ new ListParam( ListType::COMMA, $unvalidatedKeys ) ]
				),
				400,
				[ // match fields used by validateBodyParams()
					'error' => 'parameter-validation-failed',
					'failureCode' => 'extraneous-body-fields'
				]
			);
		}
	}

	/**
	 * Validate body fields.
	 * Only params with the source specified as 'body' will be processed,
	 * use validateParams() for parameters coming from the path, from query, etc.
	 *
	 * @since 1.42
	 *
	 * @see validateParams
	 * @see validateBody
	 * @param array[] $paramSettings Parameter settings.
	 * @return array Validated parameters
	 * @throws HttpException on validation failure
	 */
	public function validateBodyParams( array $paramSettings ) {
		$validatedParams = [];
		foreach ( $paramSettings as $name => $settings ) {
			$source = $settings[Handler::PARAM_SOURCE] ?? 'body';
			if ( $source !== 'body' ) {
				continue;
			}

			try {
				$validatedParams[$name] = $this->paramValidator->getValue( $name, $settings, [
					'source' => $source,
				] );
			} catch ( ValidationException $e ) {
				$msg = $e->getFailureMessage();
				$wrappedMsg = new MessageValue(
					'rest-body-validation-error',
					[ $e->getFailureMessage() ]
				);

				// NOTE: error data structure must match the one used by validateParams
				throw new LocalizedHttpException( $wrappedMsg, 400, [
					'error' => 'parameter-validation-failed',
					'name' => $e->getParamName(),
					'value' => $e->getParamValue(),
					'failureCode' => $msg->getCode(),
					'failureData' => $msg->getData(),
				] );
			}
		}
		return $validatedParams;
	}

	/**
	 * Validate the body of a request.
	 *
	 * This may return a data structure representing the parsed body. When used
	 * in the context of Handler::validateParams(), the returned value will be
	 * available to the handler via Handler::getValidatedBody().
	 *
	 * @param RequestInterface $request
	 * @param Handler $handler Used to call {@see Handler::getBodyValidator}
	 * @return mixed|null Return value from {@see BodyValidator::validateBody}
	 * @throws HttpException on validation failure
	 */
	public function validateBody( RequestInterface $request, Handler $handler ) {
		$method = strtoupper( trim( $request->getMethod() ) );

		// If the method should never have a body, don't bother validating.
		if ( in_array( $method, self::NO_BODY_METHODS, true ) ) {
			return null;
		}

		// Get the content type
		[ $ct ] = explode( ';', $request->getHeaderLine( 'Content-Type' ), 2 );
		$ct = strtolower( trim( $ct ) );
		if ( $ct === '' ) {
			// No Content-Type was supplied. RFC 7231 § 3.1.1.5 allows this, but
			// since it's probably a client error let's return a 415, unless the
			// body is known to be empty.
			$body = $request->getBody();
			if ( $body->getSize() === 0 ) {
				return null;
			} else {
				throw new LocalizedHttpException( new MessageValue( "rest-requires-content-type-header" ), 415, [
					'error' => 'no-content-type',
				] );
			}
		}

		// Form data is parsed into $_POST and $_FILES by PHP and from there is accessed as parameters,
		// don't bother trying to handle these via BodyValidator too.
		if ( in_array( $ct, RequestInterface::FORM_DATA_CONTENT_TYPES, true ) ) {
			return null;
		}

		// Validate the body. BodyValidator throws an HttpException on failure.
		return $handler->getBodyValidator( $ct )->validateBody( $request );
	}

	private const PARAM_TYPE_SCHEMAS = [
		'boolean-param' => [ 'type' => 'boolean' ],
		'enum-param' => [ 'type' => 'string' ],
		'integer-param' => [ 'type' => 'integer' ],
		'float-param' => [ 'type' => 'number', 'format' => 'float' ],
		'double-param' => [ 'type' => 'number', 'format' => 'double' ],
		// 'NULL-param' => [ 'type' => 'null' ], // FIXME
		'password-param' => [ 'type' => 'string' ],
		'string-param' => [ 'type' => 'string' ],
		'timestamp-param' => [ 'type' => 'string', 'format' => 'mw-timestamp' ],
		'upload-param' => [ 'type' => 'string', 'format' => 'mw-upload' ],
		'expiry-param' => [ 'type' => 'string', 'format' => 'mw-expiry' ],
		'title-param' => [ 'type' => 'string', 'format' => 'mw-title' ],
		'user-param' => [ 'type' => 'string', 'format' => 'mw-user' ],
		'array-param' => [ 'type' => 'object' ],
	];

	/**
	 * Returns JSON Schema description of all known parameter types.
	 * The name of the schema is the name of the parameter type with "-param" appended.
	 *
	 * @see https://swagger.io/specification/#schema-object
	 * @see self::TYPE_DEFS
	 *
	 * @return array
	 */
	public static function getParameterTypeSchemas(): array {
		return self::PARAM_TYPE_SCHEMAS;
	}

	/**
	 * Convert a param settings array into an OpenAPI Parameter Object specification structure.
	 * @see https://swagger.io/specification/#parameter-object
	 *
	 * @param string $name
	 * @param array $paramSetting
	 *
	 * @return array
	 */
	public static function getParameterSpec( string $name, array $paramSetting ): array {
		$type = $paramSetting[ ParamValidator::PARAM_TYPE ] ?? 'string';

		if ( is_array( $type ) ) {
			if ( $type === [] ) {
				// Hack for empty enums. In path and query parameters,
				// the empty string is often the same as "no value".
				// TODO: generate a warning!
				$type = [ '' ];
			}

			$schema = [
				'type' => 'string',
				'enum' => $type
			];
		} else {
			// TODO: multi-value params?!
			$schema = self::PARAM_TYPE_SCHEMAS["{$type}-param"] ?? [];
		}

		// TODO: generate a warning if the source is not specified!
		$location = $paramSetting[ self::PARAM_SOURCE ] ?? 'unspecified';

		$param = [
			'name' => $name,
			'description' => $paramSetting[ self::PARAM_DESCRIPTION ] ?? "$name parameter",
			'in' => $location,
			'schema' => $schema
		];

		// TODO: generate a warning if required is false for a path param
		$param['required'] = $location === 'path'
			|| ( $paramSetting[ ParamValidator::PARAM_REQUIRED ] ?? false );

		return $param;
	}

}
