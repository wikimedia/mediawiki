<?php

namespace MediaWiki\Rest\Handler;

use ApiBase;
use ApiMain;
use ApiMessage;
use ApiUsageException;
use FauxRequest;
use IApiMessage;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Session\Session;
use RequestContext;
use WebResponse;
use Wikimedia\Message\ListParam;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;

/**
 * Base class for REST handlers that are implemented by mapping to an existing ApiModule.
 *
 * @stable to extend
 */
abstract class ActionModuleBasedHandler extends Handler {

	/**
	 * @var Session|null
	 */
	private $session = null;

	/**
	 * @var ApiMain|null
	 */
	private $apiMain = null;

	protected function getUser() {
		return $this->getApiMain()->getUser();
	}

	/**
	 * @return Session
	 */
	protected function getSession() {
		if ( !$this->session ) {
			$this->session = $this->getApiMain()->getRequest()->getSession();
		}

		return $this->session;
	}

	/**
	 * Set main action API entry point for testing.
	 *
	 * @param ApiMain $apiMain
	 */
	public function setApiMain( ApiMain $apiMain ) {
		$this->apiMain = $apiMain;
	}

	/**
	 * @return ApiMain
	 */
	public function getApiMain() {
		if ( $this->apiMain ) {
			return $this->apiMain;
		}

		$context = RequestContext::getMain();
		$session = $context->getRequest()->getSession();

		// NOTE: This being a FauxRequest instance triggers special case behavior
		// in ApiMain, causing ApiMain::isInternalMode() to return true. Among other things,
		// this causes ApiMain to throw errors rather than encode them in the result data.
		$fauxRequest = new FauxRequest( [], true, $session );
		$fauxRequest->setSessionId( $session->getSessionId() );

		$fauxContext = new RequestContext();
		$fauxContext->setRequest( $fauxRequest );
		$fauxContext->setUser( $context->getUser() );
		$fauxContext->setLanguage( $context->getLanguage() );

		$this->apiMain = new ApiMain( $fauxContext, true );
		return $this->apiMain;
	}

	/**
	 * Overrides an action API module. Used for testing.
	 *
	 * @param string $name
	 * @param string $group
	 * @param ApiBase $module
	 */
	public function overrideActionModule( string $name, string $group, ApiBase $module ) {
		$this->getApiMain()->getModuleManager()->addModule(
			$name,
			$group,
			[
				'class' => get_class( $module ),
				'factory' => function () use ( $module ) {
					return $module;
				}
			]
		);
	}

	/**
	 * Main execution method, implemented to delegate execution to ApiMain.
	 * Which action API module gets called is controlled by the parameter array returned
	 * by getActionModuleParameters(). The response from the action module is passed to
	 * mapActionModuleResult(), any ApiUsageException thrown will be converted to a
	 * HttpException by throwHttpExceptionForActionModuleError().
	 *
	 * @return mixed
	 */
	public function execute() {
		$apiMain = $this->getApiMain();

		$params = $this->getActionModuleParameters();
		$request = $apiMain->getRequest();

		foreach ( $params as $key => $value ) {
			$request->setVal( $key, $value );
		}

		try {
			// NOTE: ApiMain detects the this to be an internal call, so it will throw
			// ApiUsageException rather than putting error messages into the result.
			$apiMain->execute();
		} catch ( ApiUsageException $ex ) {
			// use a fake loop to throw the first error
			foreach ( $ex->getStatusValue()->getErrorsByType( 'error' ) as $error ) {
				$msg = ApiMessage::create( $error );
				$this->throwHttpExceptionForActionModuleError( $msg, $ex->getCode() ?: 400 );
			}

			// This should never happen, since ApiUsageExceptions should always
			// have errors in their Status object.
			throw new HttpException(
				'Unmapped action module error: ' . $ex->getMessage(),
				$ex->getCode()
			);
		}

		$actionModuleResult = $apiMain->getResult()->getResultData( null, [ 'Strip' => 'all' ] );

		// construct result
		$resultData = $this->mapActionModuleResult( $actionModuleResult );

		$response = $this->getResponseFactory()->createFromReturnValue( $resultData );

		$this->mapActionModuleResponse(
			$apiMain->getRequest()->response(),
			$actionModuleResult,
			$response
		);

		return $response;
	}

	/**
	 * Maps a REST API request to an action API request.
	 * Implementations typically use information returned by $this->getValidatedBody()
	 * and $this->getValidatedParams() to construct the return value.
	 *
	 * The return value of this method controls which action module is called by execute().
	 *
	 * @return array Emulated request parameters to be passed to the ApiModule.
	 */
	abstract protected function getActionModuleParameters();

	/**
	 * Maps an action API result to a REST API result.
	 *
	 * @param array $data Data structure retrieved from the ApiResult returned by the ApiModule
	 *
	 * @return mixed Data structure to be converted to JSON and wrapped in a REST Response.
	 *         Will be processed by ResponseFactory::createFromReturnValue().
	 */
	abstract protected function mapActionModuleResult( array $data );

	/**
	 * Transfers relevant information, such as header values, from the WebResponse constructed
	 * by the action API call to a REST Response object.
	 *
	 * Subclasses may override this to provide special case handling for header fields.
	 * For mapping the response body, override mapActionModuleResult() instead.
	 *
	 * Subclasses overriding this method should call this method in the parent class,
	 * to preserve baseline behavior.
	 *
	 * @stable to override
	 *
	 * @param WebResponse $actionModuleResponse
	 * @param array $actionModuleResult
	 * @param Response $response
	 */
	protected function mapActionModuleResponse(
		WebResponse $actionModuleResponse,
		array $actionModuleResult,
		Response $response
	) {
		// TODO: map status, headers, cookies, etc
	}

	/**
	 * Throws a HttpException for a given IApiMessage that represents an error.
	 * Never returns normally.
	 *
	 * Subclasses may override this to provide mappings for specific error codes,
	 * typically based on $msg->getApiCode(). Subclasses overriding this method must
	 * always either throw an exception, or call this method in the parent class,
	 * which then throws an exception.
	 *
	 * @stable to override
	 *
	 * @param IApiMessage $msg A message object representing an error in an action module,
	 *        typically from calling getStatusValue()->getErrorsByType( 'error' ) on
	 *        an ApiUsageException.
	 * @param int $statusCode The HTTP status indicated by the original exception
	 *
	 * @throws HttpException always.
	 */
	protected function throwHttpExceptionForActionModuleError( IApiMessage $msg, $statusCode = 400 ) {
		// override to supply mappings

		throw new LocalizedHttpException(
			$this->makeMessageValue( $msg ),
			$statusCode,
			// Include the original error code in the response.
			// This makes it easier to track down the original cause of the error,
			// and allows more specific mappings to be added to
			// implementations of throwHttpExceptionForActionModuleError() provided by
			// subclasses
			[ 'actionModuleErrorCode' => $msg->getApiCode() ]
		);
	}

	/**
	 * Constructs a MessageValue from an IApiMessage.
	 *
	 * @param IApiMessage $msg
	 *
	 * @return MessageValue
	 */
	protected function makeMessageValue( IApiMessage $msg ) {
		$params = [];

		// TODO: find a better home for the parameter mapping logic
		foreach ( $msg->getParams() as $p ) {
			$params[] = $this->makeMessageParam( $p );
		}

		return new MessageValue( $msg->getKey(), $params );
	}

	/**
	 * @param mixed $param
	 *
	 * @return MessageParam
	 */
	private function makeMessageParam( $param ) {
		if ( is_array( $param ) ) {
			foreach ( $param as $type => $value ) {
				if ( $type === 'list' ) {
					$paramList = [];

					foreach ( $value as $v ) {
						$paramList[] = $this->makeMessageParam( $v );
					}

					return new ListParam( ParamType::TEXT, $paramList );
				} else {
					return new ScalarParam( $type, $value );
				}
			}
		} else {
			return new ScalarParam( ParamType::TEXT, $param );
		}
	}

}
