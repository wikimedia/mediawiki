<?php
namespace MediaWiki\Rest\Handler;

use MediaWiki\Api\ApiBase;
use MediaWiki\Api\ApiHelpParamValueMessage;
use MediaWiki\Api\ApiMain;
use MediaWiki\Api\ApiRestHelper;
use MediaWiki\Api\ApiRestHelperContext;
use MediaWiki\Api\ApiRestHybrid;
use MediaWiki\Api\IApiMessage;
use MediaWiki\Api\Validator\SubmoduleDef;
use MediaWiki\Language\RawMessage;
use MediaWiki\Message\Message;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\User\LoggedOutEditToken;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\BinaryBooleanDef;

/**
 * A REST handler acting as an adapter for an action API module.
 *
 * NOTE: This class should only be used for "proper" actions, no query modules.
 *
 * @internal
 */
class GenericActionHandler extends ActionModuleBasedHandler {

	/**
	 * Parameter types of the action API that the REST framework doesn't know,
	 * mapped to the closest REST type. They are only used for the OpenAPI
	 * spec, since parameters are validated by the action module. "limit" also
	 * accepts "max", which an integer type can't express.
	 */
	private const ACTION_PARAM_TYPES = [
		'limit' => 'integer',
		'raw' => 'string',
		'text' => 'string',
		'submodule' => 'string',
	];

	private ?ApiBase $actionModule = null;

	private ?ApiRestHelper $apiRestHelper = null;

	/**
	 * Parameter settings that only the action API understands. The REST
	 * framework rejects unknown settings, so they are removed.
	 */
	private const ACTION_PARAM_SETTINGS = [
		ApiBase::PARAM_HELP_MSG,
		ApiBase::PARAM_RANGE_ENFORCE,
		ApiBase::PARAM_HELP_MSG_APPEND,
		ApiBase::PARAM_HELP_MSG_INFO,
		ApiBase::PARAM_HELP_MSG_PER_VALUE,
		ApiBase::PARAM_VALUE_LINKS,
		ApiBase::PARAM_TEMPLATE_VARS,
		SubmoduleDef::PARAM_SUBMODULE_MAP,
		SubmoduleDef::PARAM_SUBMODULE_PARAM_PREFIX,
	];

	/**
	 * Action API help messages with wikitext links that have a Markdown
	 * version, which OpenAPI descriptions use. The Markdown version has the
	 * same key with a ".md" suffix.
	 *
	 * @todo Stop-gap until action API help messages have a Markdown version
	 *       in general.
	 */
	private const MARKDOWN_HELP_MESSAGES = [
		'api-help-param-continue',
		'api-help-param-token',
	];

	/**
	 * Corresponds to the property declared in the AdapterSpec fragment
	 * in mwapi-1.2.json.
	 */
	public const SUPPRESSED_PARAMS_KEY = 'suppressedParams';

	public function __construct(
		protected readonly string $actionName,
		protected readonly array $adapterConfig = [],
	) {
	}

	/**
	 * Set main action API entry point for testing.
	 * @warning The ApiMain instance may be modified!
	 *
	 * @internal
	 */
	public function setApiMain( ApiMain $apiMain ) {
		parent::setApiMain( $apiMain );
		$this->adjustParamValidator( $apiMain );
	}

	/**
	 * Adjust the ApiMain's ParamValidator to use REST-style parameter
	 * interpretation.
	 */
	protected function adjustParamValidator( ApiMain $apiMain ): void {
		// Interpret boolean parameters REST-style (BooleanDef, value-based)
		// rather than the action API's presence-based PresenceBooleanDef.
		$apiMain->getParamValidator()->overrideTypeDef(
			'boolean', [ 'class' => BinaryBooleanDef::class ]
		);
	}

	/**
	 * Returns the module that will be called by this handler, based on
	 * the 'action' parameter returned by getActionModuleParameters().
	 *
	 * If that module implements ApiRestHybrid, this method calls initRestMode()
	 * on it.
	 *
	 * @return ApiBase
	 */
	protected function getActionModule(): ApiBase {
		if ( $this->actionModule ) {
			return $this->actionModule;
		}

		$this->actionModule = $this->getApiMain()->initModule( $this->actionName );

		if ( $this->actionModule instanceof ApiRestHybrid ) {
			$this->apiRestHelper = $this->actionModule->initRestMode(
				new ApiRestHelperContext( $this->adapterConfig, $this->getConfig() )
			);
		}

		return $this->actionModule;
	}

	/**
	 * Returns the ApiRestHelper for the module, if the module implements ApiRestHybrid.
	 * @return ?ApiRestHelper
	 */
	protected function getApiRestHelper(): ?ApiRestHelper {
		$this->getActionModule();
		return $this->apiRestHelper;
	}

	/**
	 * @inheritDoc
	 */
	protected function throwHttpExceptionForActionModuleError( IApiMessage $msg, $statusCode = 0 ) {
		$helper = $this->getApiRestHelper();

		// NOTE: the helper overrides any status code already present in the exception.
		// Both are under control of the Action API module.
		$statusCode = $helper?->getStatusForErrorMessage( $msg ) ?: ( $statusCode );

		parent::throwHttpExceptionForActionModuleError( $msg, $statusCode );
	}

	/**
	 * @inheritDoc
	 */
	protected function getActionModuleParameters() {
		// NOTE: ActionModuleBasedHandler will set the correct values for format, etc.
		return array_merge( $this->getRequestedModuleParameters(), $this->getFixedParams() );
	}

	/**
	 * Returns the parameters of the action API request that select the module to call.
	 * They are not taken from the REST request. ActionModuleBasedHandler::execute() adds
	 * the ones that select the output format.
	 *
	 * @return array
	 */
	protected function getFixedParams(): array {
		return [ 'action' => $this->actionName ];
	}

	/**
	 * Returns the parameters of the module that the request supplies, by the names the
	 * action API uses for them, and a token if the module needs one and the session is
	 * safe against CSRF. Action modules have no prefix, so the names are those of
	 * getActionModuleParamSpecs().
	 *
	 * @return array
	 */
	protected function getRequestedModuleParameters(): array {
		// The action API conflates query params and body params.
		// Body parameters take precedence.
		$all = ( $this->getRequest()->getParsedBody() ?? [] )
			+ $this->getRequest()->getPathParams()
			+ $this->getRequest()->getQueryParams();

		$params = array_intersect_key( $all, $this->getActionModuleParamSpecs() );

		// Hack: If the ActioModule requires a token, but the session is safe
		// against CSRF attacks, then make the action module happy by
		// supplying a valid token. The action API validates the token
		// unconditionally, it has no exemption for CSRF-safe providers.
		if ( $this->getActionModule()->needsToken() && !isset( $params['token'] ) ) {
			if ( $this->getSession()->getProvider()->safeAgainstCsrf() ) {
				$sessionToken = null;
				if ( $this->getSession()->getUser()->isAnon() ) {
					$sessionToken = new LoggedOutEditToken();
				} else {
					$sessionToken = $this->getSession()->getToken();
				}

				$params['token'] = $sessionToken;
			}
		}

		return $params;
	}

	/**
	 * Determines if the handler is registered for an http method
	 * that uses a request body.
	 */
	private function usesRequestBody(): bool {
		$method = strtoupper( $this->getHttpMethod() );
		return in_array( $method, RequestInterface::BODY_METHODS );
	}

	/**
	 * Return query parameters for use in OpenAPI spec generation.
	 * Since we override validate() to disable validation, they are not used
	 * for validating parameters. Parameter validation happens in the action module.
	 *
	 * @inheritDoc
	 */
	public function getParamSettings() {
		$params = $this->makeParamSettings( $this->getSupportedPathParams(), 'path' );

		if ( !$this->usesRequestBody() ) {
			$supported = $this->getSupportedQueryParams();
		} else {
			$supported = array_intersect(
				$this->getSupportedQueryParams(),
				$this->getQueryOnlyParams()
			);
		}

		$params += $this->makeParamSettings( $supported, 'query' );
		$params += parent::getParamSettings();
		return $params;
	}

	/**
	 * Return body parameters for use in OpenAPI spec generation.
	 * Since we override validate() to disable validation, they are not used
	 * for validating parameters. Parameter validation happens in the action module.
	 *
	 * @inheritDoc
	 */
	public function getBodyParamSettings(): array {
		$params = [];

		if ( $this->usesRequestBody() ) {
			// XXX: Do we need to support 'post' as a source (form data) as well?
			//      We could ask the helper for the supported body formats...
			$supported = array_diff(
				$this->getSupportedQueryParams(),
				$this->getQueryOnlyParams()
			);

			$params += $this->makeParamSettings( $supported, 'body' );
		}

		$params += parent::getBodyParamSettings();
		return $params;
	}

	/**
	 * The module whose parameters the endpoint exposes.
	 */
	protected function getParamModule(): ApiBase {
		return $this->getActionModule();
	}

	/**
	 * Returns the parameters to be exposed via the REST framework.
	 * The default implementation returns the parameters of the action module
	 * after running them through filterSupportedParams().
	 *
	 * @return array[]
	 */
	protected function getActionModuleParamSpecs(): array {
		// SEAM: We can add parameters from ApiMain here, but we probably
		//       don't want to, see T436749.
		//       They can't collide with the parameters of an action module, which
		//       share the names of ApiMain's. List modules can collide, see
		//       GenericListHandler::getActionModuleParamSpecs().
		$actionParams = $this->getModuleParamSpecs( $this->getActionModule() );
		return $this->filterSupportedParams( $actionParams );
	}

	/**
	 * The parameter specs of the given module, in array form.
	 *
	 * @return array[]
	 */
	protected function getModuleParamSpecs( ApiBase $module ): array {
		$specs = [];
		foreach ( $module->getFinalParams() as $name => $spec ) {
			// Specs may be in scalar shorthand form, just a default value.
			$specs[$name] = is_array( $spec ) ? $spec : [ ParamValidator::PARAM_DEFAULT => $spec ];
		}
		return $specs;
	}

	/**
	 * Parameter descriptions, without the module prefix, which REST names lack.
	 *
	 * @return Message[][]
	 */
	protected function getActionModuleParamDescriptions(): array {
		return $this->getParamModule()->getFinalParamDescription( '' );
	}

	/**
	 * Remove the parameters that the route suppresses.
	 *
	 * @param array[] $paramSpecs Parameter specs by name
	 * @return array[]
	 */
	protected function filterSupportedParams( array $paramSpecs ): array {
		$suppressedParams = $this->adapterConfig[ self::SUPPRESSED_PARAMS_KEY ] ?? [];

		return array_diff_key(
			$paramSpecs,
			array_flip( $suppressedParams )
		);
	}

	/**
	 * Return the names of query parameters that can never be given in the
	 * request body. The action API doesn't really distinguish between
	 * query parameters and post data, but some parameters must be part of
	 * the URI.
	 *
	 * @return string[]
	 */
	protected function getQueryOnlyParams(): array {
		// SEAM: This is a placeholder. We will probably need to get this from the
		// ApiRestHelper, as in getApiRestHelper()->getQueryOnlyParams().
		//
		// Candidates from ApiMain::getAllowedParams(), for reference:
		// origin, crossorigin, maxage, smaxage.
		// But we currently don't expose any params from ApiMain.
		return [];
	}

	/**
	 * Return the names of supported query parameters.
	 * Parameters provided in the path are excluded.
	 *
	 * @return string[]
	 */
	protected function getSupportedQueryParams(): array {
		$params = $this->getActionModuleParamSpecs();

		// strip path params
		$params = array_diff_key( $params, array_flip( $this->getSupportedPathParams() ) );

		return array_keys( $params );
	}

	/**
	 * Return normalized param settings for use in the REST framework.
	 */
	protected function makeParamSettings( array $names, string $source ): array {
		$specs = $this->getActionModuleParamSpecs();
		$descriptions = $this->getActionModuleParamDescriptions();
		$settings = [];

		foreach ( $names as $param ) {
			$spec = $specs[$param];
			// Action API param specs may be in scalar-shorthand form
			// (just a default value); the REST framework expects an array.
			if ( !is_array( $spec ) ) {
				$spec = [
					ParamValidator::PARAM_DEFAULT => $spec,
				];
			}

			if ( !isset( $spec[ParamValidator::PARAM_TYPE] ) ) {
				// Compare ParamValidator::normalizeSettingsInternal()
				$spec[ParamValidator::PARAM_TYPE] = gettype( $spec[ParamValidator::PARAM_DEFAULT] ?? null );
			}
			$type = $spec[ParamValidator::PARAM_TYPE];
			if ( is_string( $type ) && isset( self::ACTION_PARAM_TYPES[$type] ) ) {
				$spec[ParamValidator::PARAM_TYPE] = self::ACTION_PARAM_TYPES[$type];
			}

			$spec[self::PARAM_SOURCE] = $source;

			$spec[self::PARAM_DESCRIPTION] = self::combineMessages( $descriptions[$param] ?? [] );
			$spec = array_diff_key( $spec, array_flip( self::ACTION_PARAM_SETTINGS ) );

			$settings[$param] = $spec;
		}

		return $settings;
	}

	/**
	 * Combine the messages into one description, one paragraph each.
	 *
	 * @param Message[] $msgs
	 */
	private static function combineMessages( array $msgs ): MessageSpecifier {
		$msgs = array_map( self::toMarkdownMessage( ... ), array_values( $msgs ) );
		if ( !$msgs ) {
			return new MessageValue( 'api-help-param-no-description' );
		}
		if ( count( $msgs ) === 1 ) {
			return $msgs[0];
		}
		$text = implode( "\n\n", array_map( static fn ( $i ) => '$' . $i, range( 1, count( $msgs ) ) ) );
		return new RawMessage( $text, $msgs );
	}

	/**
	 * The Markdown version of an action API help message.
	 */
	private static function toMarkdownMessage( Message $msg ): MessageSpecifier {
		if ( $msg instanceof ApiHelpParamValueMessage ) {
			// Not the message itself, which adds wikitext for Special:ApiHelp.
			// shortcut: drops the deprecated and internal flags of the value,
			// add them when an exposed parameter has such values.
			return new Message( 'rest-param-value-list-item.md', [
				Message::plaintextParam( $msg->getParamValue() ),
				new Message( $msg->getKeysToTry(), $msg->getParams() ),
			] );
		}
		if ( in_array( $msg->getKey(), self::MARKDOWN_HELP_MESSAGES, true ) ) {
			return new Message( $msg->getKey() . '.md', $msg->getParams() );
		}
		return $msg;
	}

	/**
	 * Overwritten to do nothing.
	 *
	 * @inheritDoc
	 */
	public function validate( Validator $restValidator ) {
		// HACK: disable REST validation by not calling the parent.
		// This breaks access to validated parameters and body in the handler.
		// But since all parameter access, including validation, happens in the
		// action module, that should be fine.
		// It would be nice if ApiMain provided a way to trigger validation
		// separately from execution, then we could call it here.

		// We still want to fail if the client tries to send extra body params,
		// don't just ignore them (T436749)!
		$this->detectExtraneousBodyFields( $restValidator );
	}

	/**
	 * @inheritDoc
	 */
	public function execute() {
		return parent::execute();
	}

	/**
	 * @inheritDoc
	 */
	protected function mapActionModuleResult( array $data ) {
		// TODO: warning headers and such (T436750).
		return $data[ $this->actionName ] ?? [];
	}

	protected function getResponseBodySchema( string $method ): ?array {
		$helper = $this->getApiRestHelper();
		if ( $helper ) {
			return $helper->getResponseBodySchema();
		}
		return parent::getResponseBodySchema( $method );
	}

}
