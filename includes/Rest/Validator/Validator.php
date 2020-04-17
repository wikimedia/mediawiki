<?php

namespace MediaWiki\Rest\Validator;

use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\User\UserIdentity;
use Wikimedia\ObjectFactory;
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
	];

	/** @var string[] HTTP request methods that we expect never to have a payload */
	private const NO_BODY_METHODS = [ 'GET', 'HEAD', 'DELETE' ];

	/** @var string[] HTTP request methods that we expect always to have a payload */
	private const BODY_METHODS = [ 'POST', 'PUT' ];

	/** @var string[] Content types handled via $_POST */
	private const FORM_DATA_CONTENT_TYPES = [
		'application/x-www-form-urlencoded',
		'multipart/form-data',
	];

	/** @var ParamValidator */
	private $paramValidator;

	/**
	 * @param ObjectFactory $objectFactory
	 * @param PermissionManager $permissionManager
	 * @param RequestInterface $request
	 * @param UserIdentity $user
	 * @internal
	 */
	public function __construct(
		ObjectFactory $objectFactory,
		PermissionManager $permissionManager,
		RequestInterface $request,
		UserIdentity $user
	) {
		$this->paramValidator = new ParamValidator(
			new ParamValidatorCallbacks( $permissionManager, $request, $user ),
			$objectFactory,
			[
				'typeDefs' => self::TYPE_DEFS,
			]
		);
	}

	/**
	 * Validate parameters
	 * @param array[] $paramSettings Parameter settings
	 * @return array Validated parameters
	 * @throws HttpException on validaton failure
	 */
	public function validateParams( array $paramSettings ) {
		$validatedParams = [];
		foreach ( $paramSettings as $name => $settings ) {
			try {
				$validatedParams[$name] = $this->paramValidator->getValue( $name, $settings, [
					'source' => $settings[Handler::PARAM_SOURCE] ?? 'unspecified',
				] );
			} catch ( ValidationException $e ) {
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
	 * Validate the body of a request.
	 *
	 * This may return a data structure representing the parsed body. When used
	 * in the context of Handler::validateParams(), the returned value will be
	 * available to the handler via Handler::getValidatedBody().
	 *
	 * @param RequestInterface $request
	 * @param Handler $handler Used to call getBodyValidator()
	 * @return mixed May be null
	 * @throws HttpException on validation failure
	 */
	public function validateBody( RequestInterface $request, Handler $handler ) {
		$method = strtoupper( trim( $request->getMethod() ) );

		// If the method should never have a body, don't bother validating.
		if ( in_array( $method, self::NO_BODY_METHODS, true ) ) {
			return null;
		}

		// Get the content type
		list( $ct ) = explode( ';', $request->getHeaderLine( 'Content-Type' ), 2 );
		$ct = strtolower( trim( $ct ) );
		if ( $ct === '' ) {
			// No Content-Type was supplied. RFC 7231 § 3.1.1.5 allows this, but since it's probably a
			// client error let's return a 415. But don't 415 for unknown methods and an empty body.
			if ( !in_array( $method, self::BODY_METHODS, true ) ) {
				$body = $request->getBody();
				$size = $body->getSize();
				if ( $size === null ) {
					// No size available. Try reading 1 byte.
					if ( $body->isSeekable() ) {
						$body->rewind();
					}
					$size = $body->read( 1 ) === '' ? 0 : 1;
				}
				if ( $size === 0 ) {
					return null;
				}
			}
			throw new HttpException( "A Content-Type header must be supplied with a request payload.", 415, [
				'error' => 'no-content-type',
			] );
		}

		// Form data is parsed into $_POST and $_FILES by PHP and from there is accessed as parameters,
		// don't bother trying to handle these via BodyValidator too.
		if ( in_array( $ct, self::FORM_DATA_CONTENT_TYPES, true ) ) {
			return null;
		}

		// Validate the body. BodyValidator throws an HttpException on failure.
		return $handler->getBodyValidator( $ct )->validateBody( $request );
	}

}
