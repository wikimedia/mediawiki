<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use MediaWiki\Session\SessionManager;

abstract class ApiTestCase extends MediaWikiLangTestCase {
	protected static $apiUrl;

	protected static $errorFormatter = null;

	/**
	 * @var ApiTestContext
	 */
	protected $apiContext;

	protected function setUp(): void {
		global $wgServer;

		parent::setUp();
		self::$apiUrl = $wgServer . wfScript( 'api' );

		self::$users = [
			'sysop' => static::getTestSysop(),
			'uploader' => static::getTestUser(),
		];

		$this->setRequest( new FauxRequest( [] ) );

		$this->apiContext = new ApiTestContext();
	}

	protected function tearDown(): void {
		// Avoid leaking session over tests
		MediaWiki\Session\SessionManager::getGlobalSession()->clear();

		ApiBase::clearCacheForTest();

		parent::tearDown();
	}

	/**
	 * Does the API request and returns the result.
	 *
	 * @param array $params
	 * @param array|null $session
	 * @param bool $appendModule
	 * @param Authority|null $performer
	 * @param string|null $tokenType Set to a string like 'csrf' to send an
	 *   appropriate token
	 * @param string|null $paramPrefix Prefix to prepend to parameters
	 * @return array List of:
	 * - the result data (array)
	 * - the request (WebRequest)
	 * - the session data of the request (array)
	 * - if $appendModule is true, the Api module $module
	 * @throws ApiUsageException
	 */
	protected function doApiRequest( array $params, array $session = null,
		$appendModule = false, Authority $performer = null, $tokenType = null,
		$paramPrefix = null
	) {
		global $wgRequest;

		if ( $session === null ) {
			// re-use existing global session by default
			$session = $wgRequest->getSessionArray();
		}

		$sessionObj = SessionManager::singleton()->getEmptySession();

		if ( $session !== null ) {
			foreach ( $session as $key => $value ) {
				$sessionObj->set( $key, $value );
			}
		}

		// set up global environment
		if ( $performer ) {
			$legacyUser = $this->getServiceContainer()->getUserFactory()->newFromAuthority( $performer );
			$contextUser = $legacyUser;
		} else {
			$contextUser = self::$users['sysop']->getUser();
			$performer = $contextUser;
		}

		$sessionObj->setUser( $contextUser );
		if ( $tokenType !== null ) {
			if ( $tokenType === 'auto' ) {
				$tokenType = ( new ApiMain() )->getModuleManager()
					->getModule( $params['action'], 'action' )->needsToken();
			}
			if ( $tokenType !== false ) {
				$params['token'] = ApiQueryTokens::getToken(
					$contextUser,
					$sessionObj,
					ApiQueryTokens::getTokenTypeSalts()[$tokenType]
				)->toString();
			}
		}

		// prepend parameters with prefix
		foreach ( array_keys( $params ) as $key ) {
			$newKeys[] = $paramPrefix . $key;
		}
		$params = array_combine(
			$newKeys,
			array_values( $params )
		);

		$wgRequest = $this->buildFauxRequest( $params, $sessionObj );
		RequestContext::getMain()->setRequest( $wgRequest );
		RequestContext::getMain()->setAuthority( $performer );

		// set up local environment
		$context = $this->apiContext->newTestContext( $wgRequest, $performer );

		$module = new ApiMain( $context, true );

		// run it!
		$module->execute();

		// construct result
		$results = [
			$module->getResult()->getResultData( null, [ 'Strip' => 'all' ] ),
			$context->getRequest(),
			$context->getRequest()->getSessionArray()
		];

		if ( $appendModule ) {
			$results[] = $module;
		}

		return $results;
	}

	/**
	 * @since 1.37
	 * @param array $params
	 * @param MediaWiki\Session\Session|array|null $session
	 * @return FauxRequest
	 */
	protected function buildFauxRequest( $params, $session ) {
		return new FauxRequest( $params, true, $session );
	}

	/**
	 * Convenience function to access the token parameter of doApiRequest()
	 * more succinctly.
	 *
	 * @param array $params Key-value API params
	 * @param array|null $session Session array
	 * @param Authority|null $performer A User object for the context
	 * @param string $tokenType Which token type to pass
	 * @param string|null $paramPrefix Prefix to prepend to parameters
	 * @return array Result of the API call
	 */
	protected function doApiRequestWithToken( array $params, array $session = null,
		Authority $performer = null, $tokenType = 'auto', $paramPrefix = null
	) {
		return $this->doApiRequest( $params, $session, false, $performer, $tokenType, $paramPrefix );
	}

	protected static function getErrorFormatter() {
		if ( self::$errorFormatter === null ) {
			self::$errorFormatter = new ApiErrorFormatter(
				new ApiResult( false ),
				MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'en' ),
				'none'
			);
		}
		return self::$errorFormatter;
	}

	public static function apiExceptionHasCode( ApiUsageException $ex, $code ) {
		return (bool)array_filter(
			self::getErrorFormatter()->arrayFromStatus( $ex->getStatusValue() ),
			static function ( $e ) use ( $code ) {
				return is_array( $e ) && $e['code'] === $code;
			}
		);
	}

	/**
	 * Expect an ApiUsageException to be thrown with the given parameters, which are the same as
	 * ApiUsageException::newWithMessage()'s parameters.  This allows checking for an exception
	 * whose text is given by a message key instead of text, so as not to hard-code the message's
	 * text into test code.
	 * @param string|array|Message $msg
	 * @param string|null $code
	 * @param array|null $data
	 * @param int $httpCode
	 */
	protected function setExpectedApiException(
		$msg, $code = null, array $data = null, $httpCode = 0
	) {
		$expected = ApiUsageException::newWithMessage( null, $msg, $code, $data, $httpCode );
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage( $expected->getMessage() );
	}
}
