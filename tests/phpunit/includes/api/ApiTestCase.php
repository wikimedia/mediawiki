<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Session\SessionManager;
use PHPUnit\Framework\Assert;
use PHPUnit\Util\Test;

abstract class ApiTestCase extends MediaWikiLangTestCase {
	protected static $apiUrl;

	protected static $errorFormatter = null;

	/**
	 * @var ApiTestContext
	 */
	protected $apiContext;

	protected function setUp() : void {
		global $wgServer;

		parent::setUp();
		self::$apiUrl = $wgServer . wfScript( 'api' );

		ApiQueryInfo::resetTokenCache(); // tokens are invalid because we cleared the session

		self::$users = [
			'sysop' => static::getTestSysop(),
			'uploader' => static::getTestUser(),
		];

		$this->setRequest( new FauxRequest( [] ) );
		$this->setMwGlobals( [
			'wgUser' => self::$users['sysop']->getUser(),
		] );

		$this->apiContext = new ApiTestContext();
	}

	protected function tearDown() : void {
		// Avoid leaking session over tests
		MediaWiki\Session\SessionManager::getGlobalSession()->clear();

		parent::tearDown();
	}

	/**
	 * Does the API request and returns the result.
	 *
	 * @param array $params
	 * @param array|null $session
	 * @param bool $appendModule
	 * @param User|null $user
	 * @param string|null $tokenType Set to a string like 'csrf' to send an
	 *   appropriate token
	 * @return array List of:
	 * - the result data (array)
	 * - the request (WebRequest)
	 * - the session data of the request (array)
	 * - if $appendModule is true, the Api module $module
	 * @throws ApiUsageException
	 */
	protected function doApiRequest( array $params, array $session = null,
		$appendModule = false, User $user = null, $tokenType = null
	) {
		global $wgRequest, $wgUser;

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
		if ( $user ) {
			$wgUser = $user;

			// Only $contextUser should be used, $wgUser is set for anything that still
			// tries to read it
			$contextUser = $user;
		} else {
			// Fallback, eventually should be removed. Once no tests write to $wgUser
			// directly or via `setMwGlobals`, this should always be a reference
			// to `self::$users['sysop']->getUser()` (which is set as the value of
			// $wgUser in ::setUp) and should be replaced with using that.
			$contextUser = $wgUser;
		}

		$sessionObj->setUser( $contextUser );
		if ( $tokenType !== null ) {
			if ( $tokenType === 'auto' ) {
				$tokenType = ( new ApiMain() )->getModuleManager()
					->getModule( $params['action'], 'action' )->needsToken();
			}
			$params['token'] = ApiQueryTokens::getToken(
				$contextUser,
				$sessionObj,
				ApiQueryTokens::getTokenTypeSalts()[$tokenType]
			)->toString();
		}

		$wgRequest = new FauxRequest( $params, true, $sessionObj );
		RequestContext::getMain()->setRequest( $wgRequest );
		RequestContext::getMain()->setUser( $contextUser );
		MediaWiki\Auth\AuthManager::resetCache();

		// set up local environment
		$context = $this->apiContext->newTestContext( $wgRequest, $contextUser );

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
	 * Convenience function to access the token parameter of doApiRequest()
	 * more succinctly.
	 *
	 * @param array $params Key-value API params
	 * @param array|null $session Session array
	 * @param User|null $user A User object for the context
	 * @param string $tokenType Which token type to pass
	 * @return array Result of the API call
	 */
	protected function doApiRequestWithToken( array $params, array $session = null,
		User $user = null, $tokenType = 'auto'
	) {
		return $this->doApiRequest( $params, $session, false, $user, $tokenType );
	}

	protected function getTokenList( TestUser $user, $session = null ) {
		$data = $this->doApiRequest( [
			'action' => 'tokens',
			'type' => 'edit|delete|protect|move|block|unblock|watch'
		], $session, false, $user->getUser() );

		if ( !array_key_exists( 'tokens', $data[0] ) ) {
			throw new MWException( 'Api failed to return a token list' );
		}

		return $data[0]['tokens'];
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
	 * @coversNothing
	 */
	public function testApiTestGroup() {
		$groups = Test::getGroups( static::class );
		$constraint = Assert::logicalOr(
			$this->contains( 'medium' ),
			$this->contains( 'large' )
		);
		$this->assertThat( $groups, $constraint,
			'ApiTestCase::setUp can be slow, tests must be "medium" or "large"'
		);
	}

	/**
	 * Expect an ApiUsageException to be thrown with the given parameters, which are the same as
	 * ApiUsageException::newWithMessage()'s parameters.  This allows checking for an exception
	 * whose text is given by a message key instead of text, so as not to hard-code the message's
	 * text into test code.
	 * @param string $msg
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
