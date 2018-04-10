<?php

use MediaWiki\Session\SessionManager;

abstract class ApiTestCase extends MediaWikiLangTestCase {
	protected static $apiUrl;

	protected static $errorFormatter = null;

	/**
	 * @var ApiTestContext
	 */
	protected $apiContext;

	protected function setUp() {
		global $wgServer;

		parent::setUp();
		self::$apiUrl = $wgServer . wfScript( 'api' );

		ApiQueryInfo::resetTokenCache(); // tokens are invalid because we cleared the session

		self::$users = [
			'sysop' => static::getTestSysop(),
			'uploader' => static::getTestUser(),
		];

		$this->setMwGlobals( [
			'wgAuth' => new MediaWiki\Auth\AuthManagerAuthPlugin,
			'wgRequest' => new FauxRequest( [] ),
			'wgUser' => self::$users['sysop']->getUser(),
		] );

		$this->apiContext = new ApiTestContext();
	}

	protected function tearDown() {
		// Avoid leaking session over tests
		MediaWiki\Session\SessionManager::getGlobalSession()->clear();

		parent::tearDown();
	}

	/**
	 * Edits or creates a page/revision
	 * @param string $pageName Page title
	 * @param string $text Content of the page
	 * @param string $summary Optional summary string for the revision
	 * @param int $defaultNs Optional namespace id
	 * @return array Array as returned by WikiPage::doEditContent()
	 */
	protected function editPage( $pageName, $text, $summary = '', $defaultNs = NS_MAIN ) {
		$title = Title::newFromText( $pageName, $defaultNs );
		$page = WikiPage::factory( $title );

		return $page->doEditContent( ContentHandler::makeContent( $text, $title ), $summary );
	}

	/**
	 * Deletes a revision.
	 * @param Revision|int $rev Revision to delete
	 * @param array $value What flags to set on the revision
	 * @param string $comment Deletion comment
	 */
	protected function deleteRevision(
		$rev, $value = [ Revision::DELETED_TEXT => 1 ], $comment = ''
	) {
		if ( is_int( $rev ) ) {
			$rev = Revision::newFromId( $rev );
		}
		RevisionDeleter::createList(
			'revision', RequestContext::getMain(), $rev->getTitle(), [ $rev->getId() ]
		)->setVisibility( [
			'value' => $value,
			'comment' => $comment,
		] );
	}

	/**
	 * Does the API request and returns the result.
	 *
	 * The returned value is an array containing
	 * - the result data (array)
	 * - the request (WebRequest)
	 * - the session data of the request (array)
	 * - if $appendModule is true, the Api module $module
	 *
	 * @param array $params
	 * @param array|null $session
	 * @param bool $appendModule
	 * @param User|null $user
	 * @param string|null $tokenType Set to a string like 'csrf' to send an
	 *   appropriate token
	 *
	 * @return array
	 */
	protected function doApiRequest( array $params, array $session = null,
		$appendModule = false, User $user = null, $tokenType = null
	) {
		global $wgRequest, $wgUser;

		if ( is_null( $session ) ) {
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
		}

		if ( $tokenType !== null ) {
			$params['token'] = ApiQueryTokens::getToken(
				$wgUser, $sessionObj, ApiQueryTokens::getTokenTypeSalts()[$tokenType]
			)->toString();
		}

		$wgRequest = new FauxRequest( $params, true, $sessionObj );
		RequestContext::getMain()->setRequest( $wgRequest );
		RequestContext::getMain()->setUser( $wgUser );
		MediaWiki\Auth\AuthManager::resetCache();

		// set up local environment
		$context = $this->apiContext->newTestContext( $wgRequest, $wgUser );

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
		User $user = null, $tokenType = 'csrf'
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
				Language::factory( 'en' ),
				'none'
			);
		}
		return self::$errorFormatter;
	}

	public static function apiExceptionHasCode( ApiUsageException $ex, $code ) {
		return (bool)array_filter(
			self::getErrorFormatter()->arrayFromStatus( $ex->getStatusValue() ),
			function ( $e ) use ( $code ) {
				return is_array( $e ) && $e['code'] === $code;
			}
		);
	}

	/**
	 * @coversNothing
	 */
	public function testApiTestGroup() {
		$groups = PHPUnit_Util_Test::getGroups( static::class );
		$constraint = PHPUnit_Framework_Assert::logicalOr(
			$this->contains( 'medium' ),
			$this->contains( 'large' )
		);
		$this->assertThat( $groups, $constraint,
			'ApiTestCase::setUp can be slow, tests must be "medium" or "large"'
		);
	}

	protected function setExpectedApiException(
		$msg, $code = null, array $data = null, $httpCode = 0
	) {
		$expected = ApiUsageException::newWithMessage( null, $msg, $code, $data, $httpCode );
		$this->setExpectedException( ApiUsageException::class, $expected->getMessage() );
	}
}
