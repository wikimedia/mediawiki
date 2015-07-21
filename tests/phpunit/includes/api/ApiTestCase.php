<?php

abstract class ApiTestCase extends MediaWikiLangTestCase {
	protected static $apiUrl;

	/**
	 * @var ApiTestContext
	 */
	protected $apiContext;

	/**
	 * @var array
	 */
	protected $tablesUsed = array( 'user', 'user_groups', 'user_properties' );

	protected function setUp() {
		global $wgServer;

		parent::setUp();
		self::$apiUrl = $wgServer . wfScript( 'api' );

		ApiQueryInfo::resetTokenCache(); // tokens are invalid because we cleared the session

		self::$users = array(
			'sysop' => new TestUser(
				'Apitestsysop',
				'Api Test Sysop',
				'api_test_sysop@example.com',
				array( 'sysop' )
			),
			'uploader' => new TestUser(
				'Apitestuser',
				'Api Test User',
				'api_test_user@example.com',
				array()
			)
		);

		$this->setMwGlobals( array(
			'wgMemc' => new EmptyBagOStuff(),
			'wgAuth' => new StubObject( 'wgAuth', 'AuthPlugin' ),
			'wgRequest' => new FauxRequest( array() ),
			'wgUser' => self::$users['sysop']->user,
		) );

		$this->apiContext = new ApiTestContext();
	}

	protected function tearDown() {
		// Avoid leaking session over tests
		if ( session_id() != '' ) {
			global $wgUser;
			$wgUser->logout();
			session_destroy();
		}

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

		// set up local environment
		$context = $this->apiContext->newTestContext( $wgRequest, $wgUser );

		$module = new ApiMain( $context, true );

		// run it!
		$module->execute();

		// construct result
		$results = array(
			$module->getResult()->getResultData( null, array( 'Strip' => 'all' ) ),
			$context->getRequest(),
			$context->getRequest()->getSessionArray()
		);

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
			$session['wsEditToken'] = $session['wsToken'];
			// add token to request parameters
			$timestamp = wfTimestamp();
			$params['token'] = hash_hmac( 'md5', $timestamp, $session['wsToken'] ) .
				dechex( $timestamp ) .
				User::EDIT_TOKEN_SUFFIX;

			return $this->doApiRequest( $params, $session, false, $user );
		} else {
			throw new Exception( "Session token not available" );
		}
	}

	protected function doLogin( $user = 'sysop' ) {
		if ( !array_key_exists( $user, self::$users ) ) {
			throw new MWException( "Can not log in to undefined user $user" );
		}

		$data = $this->doApiRequest( array(
			'action' => 'login',
			'lgname' => self::$users[$user]->username,
			'lgpassword' => self::$users[$user]->password ) );

		$token = $data[0]['login']['token'];

		$data = $this->doApiRequest(
			array(
				'action' => 'login',
				'lgtoken' => $token,
				'lgname' => self::$users[$user]->username,
				'lgpassword' => self::$users[$user]->password,
			),
			$data[2]
		);

		return $data;
	}

	protected function getTokenList( $user, $session = null ) {
		$data = $this->doApiRequest( array(
			'action' => 'tokens',
			'type' => 'edit|delete|protect|move|block|unblock|watch'
		), $session, false, $user->user );

		if ( !array_key_exists( 'tokens', $data[0] ) ) {
			throw new MWException( 'Api failed to return a token list' );
		}

		return $data[0]['tokens'];
	}

	public function testApiTestGroup() {
		$groups = PHPUnit_Util_Test::getGroups( get_class( $this ) );
		$constraint = PHPUnit_Framework_Assert::logicalOr(
			$this->contains( 'medium' ),
			$this->contains( 'large' )
		);
		$this->assertThat( $groups, $constraint,
			'ApiTestCase::setUp can be slow, tests must be "medium" or "large"'
		);
	}
}
