<?php

namespace MediaWiki\Tests\Api;

use ArrayAccess;
use LogicException;
use MediaWiki\Api\ApiBase;
use MediaWiki\Api\ApiErrorFormatter;
use MediaWiki\Api\ApiMain;
use MediaWiki\Api\ApiMessage;
use MediaWiki\Api\ApiQueryTokens;
use MediaWiki\Api\ApiResult;
use MediaWiki\Api\ApiUsageException;
use MediaWiki\Context\RequestContext;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Permissions\Authority;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Session\Session;
use MediaWiki\Session\SessionManager;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWikiLangTestCase;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\Constraint;
use ReturnTypeWillChange;

abstract class ApiTestCase extends MediaWikiLangTestCase {
	use MockAuthorityTrait;

	/** @var string */
	protected static $apiUrl;

	/** @var ApiErrorFormatter|null */
	protected static $errorFormatter = null;

	/**
	 * @var ApiTestContext
	 */
	protected $apiContext;

	protected function setUp(): void {
		global $wgServer;

		parent::setUp();
		self::$apiUrl = $wgServer . wfScript( 'api' );

		// HACK: Avoid creating test users in the DB if the test may not need them.
		$getters = [
			'sysop' => fn () => $this->getTestSysop(),
			'uploader' => fn () => $this->getTestUser(),
		];
		$fakeUserArray = new class ( $getters ) implements ArrayAccess {
			private array $getters;
			private array $extraUsers = [];

			public function __construct( array $getters ) {
				$this->getters = $getters;
			}

			public function offsetExists( $offset ): bool {
				return isset( $this->getters[$offset] ) || isset( $this->extraUsers[$offset] );
			}

			#[ReturnTypeWillChange]
			public function offsetGet( $offset ) {
				if ( isset( $this->getters[$offset] ) ) {
					return ( $this->getters[$offset] )();
				}
				if ( isset( $this->extraUsers[$offset] ) ) {
					return $this->extraUsers[$offset];
				}
				throw new LogicException( "Requested unknown user $offset" );
			}

			public function offsetSet( $offset, $value ): void {
				$this->extraUsers[$offset] = $value;
			}

			public function offsetUnset( $offset ): void {
				unset( $this->getters[$offset] );
				unset( $this->extraUsers[$offset] );
			}
		};

		self::$users = $fakeUserArray;

		$this->setRequest( new FauxRequest( [] ) );

		$this->apiContext = new ApiTestContext();
	}

	protected function tearDown(): void {
		// Avoid leaking session over tests
		SessionManager::getGlobalSession()->clear();

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
	protected function doApiRequest( array $params, ?array $session = null,
		$appendModule = false, ?Authority $performer = null, $tokenType = null,
		$paramPrefix = null
	) {
		global $wgRequest;

		// re-use existing global session by default
		$session ??= $wgRequest->getSessionArray();

		$sessionObj = SessionManager::singleton()->getEmptySession();

		if ( $session !== null ) {
			foreach ( $session as $key => $value ) {
				$sessionObj->set( $key, $value );
			}
		}

		// set up global environment
		if ( !$performer && !$this->needsDB() ) {
			$performer = $this->mockRegisteredUltimateAuthority();
		}
		if ( $performer ) {
			$legacyUser = $this->getServiceContainer()->getUserFactory()->newFromAuthority( $performer );
			$contextUser = $legacyUser;
			// Clone the user object, because something in Session code will replace its user with "Unknown user"
			// if it doesn't exist. But that'll also change $contextUser, and the token won't match (T341953).
			$sessionUser = clone $contextUser;
		} else {
			$contextUser = $this->getTestSysop()->getUser();
			$performer = $contextUser;
			$sessionUser = $contextUser;
		}

		$sessionObj->setUser( $sessionUser );
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
		if ( $paramPrefix !== null && $paramPrefix !== '' ) {
			$prefixedParams = [];
			foreach ( $params as $key => $value ) {
				$prefixedParams[$paramPrefix . $key] = $value;
			}
			$params = $prefixedParams;
		}

		$wgRequest = $this->buildFauxRequest( $params, $sessionObj );
		RequestContext::getMain()->setRequest( $wgRequest );
		RequestContext::getMain()->setAuthority( $performer );
		RequestContext::getMain()->setUser( $sessionUser );

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
	 * @param Session|array|null $session
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
	protected function doApiRequestWithToken( array $params, ?array $session = null,
		?Authority $performer = null, $tokenType = 'auto', $paramPrefix = null
	) {
		return $this->doApiRequest( $params, $session, false, $performer, $tokenType, $paramPrefix );
	}

	protected static function getErrorFormatter() {
		self::$errorFormatter ??= new ApiErrorFormatter(
			new ApiResult( false ),
			MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'en' ),
			'none'
		);
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
	 *
	 * @deprecated since 1.43; use expectApiErrorCode() instead, it's better to test error codes than messages
	 * @param string|array|Message $msg
	 * @param string|null $code
	 * @param array|null $data
	 * @param int $httpCode
	 */
	protected function setExpectedApiException(
		$msg, $code = null, ?array $data = null, $httpCode = 0
	) {
		$expected = ApiUsageException::newWithMessage( null, $msg, $code, $data, $httpCode );
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage( $expected->getMessage() );
	}

	private ?string $expectedApiErrorCode;

	/**
	 * Expect an ApiUsageException that results in the given API error code to be thrown.
	 *
	 * Note that you can't mix this method with standard PHPUnit expectException() methods,
	 * as PHPUnit will catch the exception and prevent us from testing it.
	 *
	 * @since 1.41
	 * @param string $expectedCode
	 */
	protected function expectApiErrorCode( string $expectedCode ) {
		$this->expectedApiErrorCode = $expectedCode;
	}

	/**
	 * Expect an ApiUsageException that results in the given API error code to be thrown
	 * from provided callback.
	 *
	 * @since 1.45
	 * @param string $expectedCode
	 */
	protected function expectApiErrorCodeFromCallback( string $expectedCode, callable $callback ) {
		try {
			$callback();
		} catch ( ApiUsageException $exception ) {
			$this->assertApiErrorCode( $expectedCode, $exception );

			// rethrow, no further code in the test class can be executed
			$this->expectException( ApiUsageException::class );
			throw $exception;
		}
		self::fail( sprintf(
			'Failed asserting that exception with API error code "%s" is thrown',
			$this->expectedApiErrorCode
		) );
	}

	/**
	 * Assert that an ApiUsageException will result in the given API error code being outputted.
	 *
	 * @since 1.41
	 * @param string $expectedCode
	 * @param ApiUsageException $exception
	 * @param string $message
	 */
	protected function assertApiErrorCode( string $expectedCode, ApiUsageException $exception, string $message = '' ) {
		$constraint = new class( $expectedCode ) extends Constraint {
			private string $expectedApiErrorCode;

			public function __construct( string $expected ) {
				$this->expectedApiErrorCode = $expected;
			}

			public function toString(): string {
				return 'API error code is ';
			}

			private function getApiErrorCode( $other ) {
				if ( !$other instanceof ApiUsageException ) {
					return null;
				}
				$errors = $other->getStatusValue()->getMessages();
				if ( count( $errors ) === 0 ) {
					return '(no error)';
				} elseif ( count( $errors ) > 1 ) {
					return '(multiple errors)';
				}
				return ApiMessage::create( $errors[0] )->getApiCode();
			}

			protected function matches( $other ): bool {
				return $this->getApiErrorCode( $other ) === $this->expectedApiErrorCode;
			}

			protected function failureDescription( $other ): string {
				return sprintf(
					'%s is equal to expected API error code %s',
					$this->exporter()->export( $this->getApiErrorCode( $other ) ),
					$this->exporter()->export( $this->expectedApiErrorCode )
				);
			}
		};

		$this->assertThat( $exception, $constraint, $message );
	}

	/**
	 * @inheritDoc
	 *
	 * Adds support for expectApiErrorCode().
	 */
	protected function runTest() {
		try {
			$testResult = parent::runTest();

		} catch ( ApiUsageException $exception ) {
			if ( !isset( $this->expectedApiErrorCode ) ) {
				throw $exception;
			}

			$this->assertApiErrorCode( $this->expectedApiErrorCode, $exception );

			return null;
		}

		if ( !isset( $this->expectedApiErrorCode ) ) {
			return $testResult;
		}

		throw new AssertionFailedError(
			sprintf(
				'Failed asserting that exception with API error code "%s" is thrown',
				$this->expectedApiErrorCode
			)
		);
	}
}

/** @deprecated class alias since 1.42 */
class_alias( ApiTestCase::class, 'ApiTestCase' );
