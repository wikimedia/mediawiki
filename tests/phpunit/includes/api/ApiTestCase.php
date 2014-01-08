<?php

abstract class ApiTestCase extends MediaWikiLangTestCase {
	protected static $apiUrl;

	/**
	 * @var ApiTestContext
	 */
	protected $apiContext;

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

	/**
	 * Edits or creates a page/revision
	 * @param $pageName string page title
	 * @param $text string content of the page
	 * @param $summary string optional summary string for the revision
	 * @param $defaultNs int optional namespace id
	 * @return array as returned by WikiPage::doEditContent()
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
	 * @param array $params GET/POST data to submit
	 * @param array|null $session Session data, or null to use global session data
	 * @param bool $appendModule Whether to append the Api* module to the result
	 * @param User|null $user User to use, or null for $wgUser
	 *
	 * @return array [ ResultData, WebRequest, SessionData ]
	 */
	protected function doApiRequest( array $params, array $session = null, $appendModule = false, User $user = null ) {
		global $wgRequest;

		if ( is_null( $session ) ) {
			// re-use existing global session by default
			$session = $wgRequest->getSessionArray();
		}

		$request = new FauxRequest( $params, true, $session );

		return $this->doApiRequestInternal( $request, $user, $appendModule );
	}

	/**
	 * Add an edit token to the API request
	 *
	 * @param array $params Key-value API params
	 * @param array|null $session Session array, or null to use global session
	 * @param User|null $user A User object for the context
	 *
	 * @return array [ ResultData, WebRequest, SessionData ]
	 */
	protected function doApiRequestWithToken( array $params, array $session = null, User $user = null ) {
		global $wgRequest;

		if ( is_null( $session ) ) {
			// re-use existing global session by default
			$session = $wgRequest->getSessionArray();
		}

		$request = new FauxRequest( $params, true, $session );
		$request->setVal( 'token', $request->getCsrfToken() );

		return $this->doApiRequestInternal( $request, $user, false );
	}

	/**
	 * Actually execute an internal API request
	 *
	 * Take a given request and user, insert the information into the global state, and execute
	 * an API request based on the given request.
	 *
	 * @param FauxRequest $request Request to execute
	 * @param User|null $user User to use, or null for the curren $wgUser
	 * @param bool $appendModule Whether to append the Api module object onto the result array
	 *
	 * @return array [ ResultData, WebRequest, SessionData ]
	 */
	private function doApiRequestInternal( FauxRequest $request, User $user = null, $appendModule = false ) {
		global $wgRequest, $wgUser;

		// Set global state
		$wgRequest = $request;
		if ( $user !== null ) {
			$wgUser = $user;
		} else {
			$user = $wgUser;
		}

		$mainContext = RequestContext::getMain();
		$mainContext->setRequest( $request );
		$mainContext->setUser( $user );

		// Make a new context and module, and run the module
		$context = $this->apiContext->newTestContext( $request, $user );
		$module = new ApiMain( $context, true );
		$module->execute();

		// Construct the result
		/** @var FauxRequest $resRequest */
		$resRequest = $context->getRequest();
		$results = array(
			$module->getResultData(),
			$resRequest,
			$resRequest->getSessionArray()
		);

		if ( $appendModule ) {
			$results[] = $module;
		}

		return $results;
	}

	protected function doLogin( $user = 'sysop' ) {
		if ( !array_key_exists( $user, self::$users ) ) {
			throw new MWException( "Can not log in to undefined user $user" );
		}

		$data = $this->doApiRequest( array(
			'action' => 'login',
			'lgname' => self::$users[ $user ]->username,
			'lgpassword' => self::$users[ $user ]->password ) );

		$token = $data[0]['login']['token'];

		$data = $this->doApiRequest(
			array(
				'action' => 'login',
				'lgtoken' => $token,
				'lgname' => self::$users[ $user ]->username,
				'lgpassword' => self::$users[ $user ]->password,
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
