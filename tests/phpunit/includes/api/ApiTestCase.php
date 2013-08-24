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
	 * @param array $params
	 * @param array|null $session
	 * @param bool $appendModule
	 * @param User|null $user
	 *
	 * @return array
	 */
	protected function doApiRequest( array $params, array $session = null, $appendModule = false, User $user = null ) {
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
			$module->getResultData(),
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
	 * This is cheating a bit -- we grab a token in the correct format and then add it to the pseudo-session and to the
	 * request, without actually requesting a "real" edit token
	 * @param $params Array: key-value API params
	 * @param $session Array|null: session array
	 * @param $user User|null A User object for the context
	 * @return result of the API call
	 * @throws Exception in case wsToken is not set in the session
	 */
	protected function doApiRequestWithToken( array $params, array $session = null, User $user = null ) {
		global $wgRequest;

		if ( $session === null ) {
			$session = $wgRequest->getSessionArray();
		}

		if ( $session['wsToken'] ) {
			// add edit token to fake session
			$session['wsEditToken'] = $session['wsToken'];
			// add token to request parameters
			$params['token'] = md5( $session['wsToken'] ) . User::EDIT_TOKEN_SUFFIX;

			return $this->doApiRequest( $params, $session, false, $user );
		} else {
			throw new Exception( "request data not in right format" );
		}
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

class UserWrapper {
	public $userName;
	public $password;
	public $user;

	public function __construct( $userName, $password, $group = '' ) {
		$this->userName = $userName;
		$this->password = $password;

		$this->user = User::newFromName( $this->userName );
		if ( !$this->user->getID() ) {
			$this->user = User::createNew( $this->userName, array(
				"email" => "test@example.com",
				"real_name" => "Test User" ) );
		}
		$this->user->setPassword( $this->password );

		if ( $group !== '' ) {
			$this->user->addGroup( $group );
		}
		$this->user->saveSettings();
	}
}

class MockApi extends ApiBase {
	public function execute() {
	}

	public function getVersion() {
	}

	public function __construct() {
	}

	public function getAllowedParams() {
		return array(
			'filename' => null,
			'enablechunks' => false,
			'sessionkey' => null,
		);
	}
}

class ApiTestContext extends RequestContext {

	/**
	 * Returns a DerivativeContext with the request variables in place
	 *
	 * @param $request WebRequest request object including parameters and session
	 * @param $user User or null
	 * @return DerivativeContext
	 */
	public function newTestContext( WebRequest $request, User $user = null ) {
		$context = new DerivativeContext( $this );
		$context->setRequest( $request );
		if ( $user !== null ) {
			$context->setUser( $user );
		}

		return $context;
	}
}
