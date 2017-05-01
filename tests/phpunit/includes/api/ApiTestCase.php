<?php

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
	 *
	 * @return array
	 */
	protected function doApiRequest( array $params, array $session = null,
		$appendModule = false, User $user = null
	) {
		global $wgRequest, $wgUser;

		if ( is_null( $session ) ) {
			// re-use existing global session by default
			$session = $wgRequest->getSessionArray();
		}

		// set up global environment
		if ( $user ) {
			$wgUser = $user;
		}

		$wgRequest = new FauxRequest( $params, true, $session );
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
	 * Add an edit token to the API request
	 * This is cheating a bit -- we grab a token in the correct format and then
	 * add it to the pseudo-session and to the request, without actually
	 * requesting a "real" edit token.
	 *
	 * @param array $params Key-value API params
	 * @param array|null $session Session array
	 * @param User|null $user A User object for the context
	 * @return array Result of the API call
	 * @throws Exception In case wsToken is not set in the session
	 */
	protected function doApiRequestWithToken( array $params, array $session = null,
		User $user = null
	) {
		global $wgRequest;

		if ( $session === null ) {
			$session = $wgRequest->getSessionArray();
		}

		if ( isset( $session['wsToken'] ) && $session['wsToken'] ) {
			// @todo Why does this directly mess with the session? Fix that.
			// add edit token to fake session
			$session['wsTokenSecrets']['default'] = $session['wsToken'];
			// add token to request parameters
			$timestamp = wfTimestamp();
			$params['token'] = hash_hmac( 'md5', $timestamp, $session['wsToken'] ) .
				dechex( $timestamp ) .
				MediaWiki\Session\Token::SUFFIX;

			return $this->doApiRequest( $params, $session, false, $user );
		} else {
			throw new Exception( "Session token not available" );
		}
	}

	protected function doLogin( $testUser = 'sysop' ) {
		if ( $testUser === null ) {
			$testUser = static::getTestSysop();
		} elseif ( is_string( $testUser ) && array_key_exists( $testUser, self::$users ) ) {
			$testUser = self::$users[ $testUser ];
		} elseif ( !$testUser instanceof TestUser ) {
			throw new MWException( "Can not log in to undefined user $testUser" );
		}

		$data = $this->doApiRequest( [
			'action' => 'login',
			'lgname' => $testUser->getUser()->getName(),
			'lgpassword' => $testUser->getPassword() ] );

		$token = $data[0]['login']['token'];

		$data = $this->doApiRequest(
			[
				'action' => 'login',
				'lgtoken' => $token,
				'lgname' => $testUser->getUser()->getName(),
				'lgpassword' => $testUser->getPassword(),
			],
			$data[2]
		);

		if ( $data[0]['login']['result'] === 'Success' ) {
			// DWIM
			global $wgUser;
			$wgUser = $testUser->getUser();
			RequestContext::getMain()->setUser( $wgUser );
		}

		return $data;
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
}
