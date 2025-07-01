<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Api\ApiBase;
use MediaWiki\Api\ApiMain;
use MediaWiki\Api\ApiMessage;
use MediaWiki\Api\ApiUsageException;
use MediaWiki\Api\IApiMessage;
use MediaWiki\Context\RequestContext;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\WebResponse;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Handler\Helper\RestStatusTrait;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\User\User;
use Wikimedia\Message\MessageValue;

/**
 * Base class for REST handlers that are implemented by mapping to an existing ApiModule.
 *
 * @stable to extend
 */
abstract class ActionModuleBasedHandler extends Handler {
	use RestStatusTrait;

	/**
	 * @var ApiMain|null
	 */
	private $apiMain = null;

	protected function getUser(): User {
		return $this->getApiMain()->getUser();
	}

	/**
	 * Set main action API entry point for testing.
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

		// NOTE: This being a MediaWiki\Request\FauxRequest instance triggers special case behavior
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
				'factory' => static function () use ( $module ) {
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
			// NOTE: ApiMain detects this to be an internal call, so it will throw
			// ApiUsageException rather than putting error messages into the result.
			$apiMain->execute();
		} catch ( ApiUsageException $ex ) {
			// use a fake loop to throw the first error
			foreach ( $ex->getStatusValue()->getMessages( 'error' ) as $msg ) {
				$msg = ApiMessage::create( $msg );
				$this->throwHttpExceptionForActionModuleError( $msg, $ex->getCode() ?: 400 );
			}

			// This should never happen, since ApiUsageExceptions should always
			// have errors in their Status object.
			throw new LocalizedHttpException( new MessageValue( "rest-unmapped-action-error", [ $ex->getMessage() ] ),
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
	 *        typically from calling getStatusValue()->getMessages( 'error' ) on
	 *        an ApiUsageException.
	 * @param int $statusCode The HTTP status indicated by the original exception
	 *
	 * @throws HttpException always.
	 */
	protected function throwHttpExceptionForActionModuleError( IApiMessage $msg, $statusCode = 400 ) {
		// override to supply mappings

		throw new LocalizedHttpException(
			MessageValue::newFromSpecifier( $msg ),
			$statusCode,
			// Include the original error code in the response.
			// This makes it easier to track down the original cause of the error,
			// and allows more specific mappings to be added to
			// implementations of throwHttpExceptionForActionModuleError() provided by
			// subclasses
			[ 'actionModuleErrorCode' => $msg->getApiCode() ]
		);
	}

}
