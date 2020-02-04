<?php

namespace MediaWiki\Api\Validator;

use ApiBase;
use ApiMain;
use ApiMessage;
use ApiUsageException;
use MediaWiki\Message\Converter as MessageConverter;
use MediaWiki\ParamValidator\TypeDef\NamespaceDef;
use MediaWiki\ParamValidator\TypeDef\TagsDef;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use Message;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\Message\MessageValue;
use Wikimedia\ObjectFactory;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\EnumDef;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;
use Wikimedia\ParamValidator\TypeDef\LimitDef;
use Wikimedia\ParamValidator\TypeDef\PasswordDef;
use Wikimedia\ParamValidator\TypeDef\PresenceBooleanDef;
use Wikimedia\ParamValidator\TypeDef\StringDef;
use Wikimedia\ParamValidator\TypeDef\TimestampDef;
use Wikimedia\ParamValidator\TypeDef\UploadDef;
use Wikimedia\ParamValidator\ValidationException;

/**
 * This wraps a bunch of the API-specific parameter validation logic.
 *
 * It's intended to be used in ApiMain by composition.
 *
 * @since 1.35
 * @ingroup API
 */
class ApiParamValidator {

	/** @var ParamValidator */
	private $paramValidator;

	/** @var MessageConverter */
	private $messageConverter;

	/** Type defs for ParamValidator */
	private const TYPE_DEFS = [
		'boolean' => [ 'class' => PresenceBooleanDef::class ],
		'enum' => [ 'class' => EnumDef::class ],
		'integer' => [ 'class' => IntegerDef::class ],
		'limit' => [ 'class' => LimitDef::class ],
		'namespace' => [
			'class' => NamespaceDef::class,
			'services' => [ 'NamespaceInfo' ],
		],
		'NULL' => [
			'class' => StringDef::class,
			'args' => [ [
				'allowEmptyWhenRequired' => true,
			] ],
		],
		'password' => [ 'class' => PasswordDef::class ],
		'string' => [ 'class' => StringDef::class ],
		'submodule' => [ 'class' => SubmoduleDef::class ],
		'tags' => [ 'class' => TagsDef::class ],
		'text' => [ 'class' => StringDef::class ],
		'timestamp' => [
			'class' => TimestampDef::class,
			'args' => [ [
				'defaultFormat' => TS_MW,
			] ],
		],
		'user' => [ 'class' => UserDef::class ],
		'upload' => [ 'class' => UploadDef::class ],
	];

	/**
	 * @internal
	 * @param ApiMain $main
	 * @param ObjectFactory $objectFactory
	 */
	public function __construct( ApiMain $main, ObjectFactory $objectFactory ) {
		$this->paramValidator = new ParamValidator(
			new ApiParamValidatorCallbacks( $main ),
			$objectFactory,
			[
				'typeDefs' => self::TYPE_DEFS,
				'ismultiLimits' => [ ApiBase::LIMIT_SML1, ApiBase::LIMIT_SML2 ],
			]
		);
		$this->messageConverter = new MessageConverter();
	}

	/**
	 * List known type names
	 * @return string[]
	 */
	public function knownTypes() : array {
		return $this->paramValidator->knownTypes();
	}

	/**
	 * Adjust certain settings where ParamValidator differs from historical Action API behavior
	 * @param array|mixed $settings
	 * @return array
	 */
	public function normalizeSettings( $settings ) : array {
		$settings = $this->paramValidator->normalizeSettings( $settings );

		if ( !isset( $settings[ParamValidator::PARAM_IGNORE_UNRECOGNIZED_VALUES] ) ) {
			$settings[ParamValidator::PARAM_IGNORE_UNRECOGNIZED_VALUES] = true;
		}

		if ( !isset( $settings[IntegerDef::PARAM_IGNORE_RANGE] ) ) {
			$settings[IntegerDef::PARAM_IGNORE_RANGE] = empty( $settings[ApiBase::PARAM_RANGE_ENFORCE] );
		}

		if ( isset( $settings[EnumDef::PARAM_DEPRECATED_VALUES] ) ) {
			foreach ( $settings[EnumDef::PARAM_DEPRECATED_VALUES] as &$v ) {
				if ( $v === null || $v === true || $v instanceof MessageValue ) {
					continue;
				}

				// Convert the message specification to a DataMessageValue. Flag in the data
				// that it was so converted, so ApiParamValidatorCallbacks::recordCondition() can
				// take that into account.
				// @phan-suppress-next-line PhanTypeMismatchArgument
				$msg = $this->messageConverter->convertMessage( ApiMessage::create( $v ) );
				$v = DataMessageValue::new(
					$msg->getKey(),
					$msg->getParams(),
					'bogus',
					[ '💩' => 'back-compat' ]
				);
			}
			unset( $v );
		}

		return $settings;
	}

	/**
	 * Convert a ValidationException to an ApiUsageException
	 * @param ApiBase $module
	 * @param ValidationException $ex
	 * @throws ApiUsageException always
	 */
	private function convertValidationException( ApiBase $module, ValidationException $ex ) : array {
		$mv = $ex->getFailureMessage();
		throw ApiUsageException::newWithMessage(
			$module,
			$this->messageConverter->convertMessageValue( $mv ),
			$mv->getCode(),
			$mv->getData(),
			0,
			$ex
		);
	}

	/**
	 * Get and validate a value
	 * @param ApiBase $module
	 * @param string $name Parameter name, unprefixed
	 * @param array|mixed $settings Default value or an array of settings
	 *  using PARAM_* constants.
	 * @param array $options Options array
	 * @return mixed Validated parameter value
	 * @throws ApiUsageException if the value is invalid
	 */
	public function getValue( ApiBase $module, string $name, $settings, array $options = [] ) {
		$options['module'] = $module;
		$name = $module->encodeParamName( $name );
		$settings = $this->normalizeSettings( $settings );
		try {
			return $this->paramValidator->getValue( $name, $settings, $options );
		} catch ( ValidationException $ex ) {
			$this->convertValidationException( $module, $ex );
		}
	}

	/**
	 * Valiate a parameter value using a settings array
	 *
	 * @param ApiBase $module
	 * @param string $name Parameter name, unprefixed
	 * @param mixed $value Parameter value
	 * @param array|mixed $settings Default value or an array of settings
	 *  using PARAM_* constants.
	 * @param array $options Options array
	 * @return mixed Validated parameter value(s)
	 * @throws ApiUsageException if the value is invalid
	 */
	public function validateValue(
		ApiBase $module, string $name, $value, $settings, array $options = []
	) {
		$options['module'] = $module;
		$name = $module->encodeParamName( $name );
		$settings = $this->normalizeSettings( $settings );
		try {
			return $this->paramValidator->validateValue( $name, $value, $settings, $options );
		} catch ( ValidationException $ex ) {
			$this->convertValidationException( $module, $ex );
		}
	}

	/**
	 * Describe parameter settings in a machine-readable format.
	 *
	 * @param ApiBase $module
	 * @param string $name Parameter name.
	 * @param array|mixed $settings Default value or an array of settings
	 *  using PARAM_* constants.
	 * @param array $options Options array.
	 * @return array
	 */
	public function getParamInfo( ApiBase $module, string $name, $settings, array $options ) : array {
		$options['module'] = $module;
		$name = $module->encodeParamName( $name );
		return $this->paramValidator->getParamInfo( $name, $settings, $options );
	}

	/**
	 * Describe parameter settings in human-readable format
	 *
	 * @param ApiBase $module
	 * @param string $name Parameter name being described.
	 * @param array|mixed $settings Default value or an array of settings
	 *  using PARAM_* constants.
	 * @param array $options Options array.
	 * @return Message[]
	 */
	public function getHelpInfo( ApiBase $module, string $name, $settings, array $options ) : array {
		$options['module'] = $module;
		$name = $module->encodeParamName( $name );

		$ret = $this->paramValidator->getHelpInfo( $name, $settings, $options );
		foreach ( $ret as &$m ) {
			$k = $m->getKey();
			$m = $this->messageConverter->convertMessageValue( $m );
			if ( substr( $k, 0, 20 ) === 'paramvalidator-help-' ) {
				$m = new Message(
					[ 'api-help-param-' . substr( $k, 20 ), $k ],
					$m->getParams()
				);
			}
		}
		'@phan-var Message[] $ret'; // The above loop converts it

		return $ret;
	}
}
