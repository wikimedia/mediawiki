<?php
namespace MediaWiki\Rest\Handler;

use MediaWiki\Api\ApiBase;
use MediaWiki\Api\ApiMain;
use MediaWiki\Api\ApiRestHelper;
use MediaWiki\Api\ApiRestHybrid;
use MediaWiki\Api\IApiMessage;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\Validator\Validator;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\BinaryBooleanDef;

/**
 * A REST handler acting as an adapter for an action API module.
 *
 * NOTE: This class should only be used for "proper" actions, no query modules.
 */
class GenericActionHandler extends ActionModuleBasedHandler {

	public function __construct(
		protected string $actionName
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
	 * Returns the module that will be called by this handler, based on
	 * the 'action' parameter returned by getActionModuleParameters().
	 *
	 * @return ApiBase
	 */
	protected function getApiActionModule(): ApiBase {
		return $this->getApiMain()->initModule( $this->actionName );
	}

	/**
	 * Returns the ApiRestHelper for the module, if the module implements ApiRestHybrid.
	 * @return ?ApiRestHelper
	 */
	protected function getApiRestHelper(): ?ApiRestHelper {
		$module = $this->getApiActionModule();
		if ( $module instanceof ApiRestHybrid ) {
			return $module->getRestHelper();
		}

		return null;
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
		// The action API conflates query params and body params.
		// Body parameters take precedence.
		$params = ( $this->getRequest()->getParsedBody() ?? [] )
			+ $this->getRequest()->getQueryParams();

		// Strip all parameters defined by ApiMain.
		// These shouldn't be needed in a REST style call, compare the comment
		// in getParamSettings().
		// TODO: Fail if the client tries to set them, don't just ignore them (T436749)!
		// This doesn't happen automatically, since we are overriding
		// validate() to do nothing, leaving validation to ApiMain.
		// TODO: Only filter some framework params, allow things like tokens (T436749).
		$frameworkParams = $this->getApiMain()->getFinalParams();
		$params = array_diff_key( $params, $frameworkParams );

		// NOTE: ActionModuleBasedHandler will set the correct values
		//       for format, etc.
		$params['action'] = $this->actionName;

		// TODO: If there are path parameters, use them. If the path parameters
		// are titles, apply normalization redirects (T436748).

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
		if ( !$this->usesRequestBody() ) {
			return $this->makeParamSettings( 'query' );
		} else {
			return [];
		}
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
	 * Return body parameters for use in OpenAPI spec generation.
	 * Since we override validate() to disable validation, they are not used
	 * for validating parameters. Parameter validation happens in the action module.
	 *
	 * @inheritDoc
	 */
	public function getBodyParamSettings(): array {
		if ( $this->usesRequestBody() ) {
			// XXX: Do we need to support 'post' as a source (form data) as well?
			//      We could ask the helper for the supported body formats...
			return $this->makeParamSettings( 'body' );
		} else {
			return [];
		}
	}

	/**
	 * Normalize param settings for use in the REST framework.
	 */
	private function makeParamSettings( string $source ): array {
		// NOTE: Don't expose the params defined by ApiMain (maxlag, smaxage,
		// errorlang, etc.). They should be covered by REST style headers
		// (Cache-Control, Accept-Language, etc.).
		// We also strip these in getActionModuleParameters().
		// If we do include param specs from ApiMain, we'll have to make
		// sure that they default to the correct message key prefix, namely
		// apihelp-main-param-*.
		$params = $this->getApiActionModule()->getFinalParams()
			+ parent::getParamSettings();

		// Unset the parameters that are forced in getActionModuleParameters(),
		// because the client can't specify them.
		unset( $params['action'] );
		unset( $params['format'] );
		unset( $params['formatversion'] );
		unset( $params['errorformat'] );

		foreach ( $params as $param => &$spec ) {
			// Action API param specs may be in scalar-shorthand form
			// (just a default value); the REST framework expects an array.
			if ( !is_array( $spec ) ) {
				$spec = [ ParamValidator::PARAM_DEFAULT => $spec ];
			}
			$spec[self::PARAM_SOURCE] = $source;

			// Translate Action API help-message specs into the REST
			// framework's description field. PARAM_HELP_MSG holds either
			// a bare message key or [ key, params... ].
			if ( isset( $spec[ApiBase::PARAM_HELP_MSG] ) ) {
				$helpMsg = (array)$spec[ApiBase::PARAM_HELP_MSG];
				$key = array_shift( $helpMsg );
				$spec[self::PARAM_DESCRIPTION] = new MessageValue( $key, $helpMsg );
				unset( $spec[ApiBase::PARAM_HELP_MSG] );
			} else {
				$msg = "apihelp-{$this->actionName}-param-{$param}";
				$spec[self::PARAM_DESCRIPTION] = new MessageValue( $msg );
			}
		}

		return $params;
	}

	/**
	 * Overwritten to do nothing.
	 *
	 * @inheritDoc
	 */
	public function validate( Validator $restValidator ) {
		// HACK: disable REST validation completely by not calling the parent.
		// This breaks access to validated parameters and body in the handler.
		// But since all parameter access, including validation, happens in the
		// action module, that should be fine.
		// It would be nice of ApiMain provided a way to trigger validation
		// separately from execution, then we could call it here.
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
