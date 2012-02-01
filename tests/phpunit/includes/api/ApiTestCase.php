<?php 

abstract class ApiTestCase extends MediaWikiLangTestCase {
	/**
	 * @var Array of ApiTestUser
	 */
	public static $users;
	protected static $apiUrl;

	/**
	 * @var ApiTestContext
	 */
	protected $apiContext;

	function setUp() {
		global $wgContLang, $wgAuth, $wgMemc, $wgRequest, $wgUser, $wgServer;

		parent::setUp();
		self::$apiUrl = $wgServer . wfScript( 'api' );
		$wgMemc = new EmptyBagOStuff();
		$wgContLang = Language::factory( 'en' );
		$wgAuth = new StubObject( 'wgAuth', 'AuthPlugin' );
		$wgRequest = new FauxRequest( array() );

		self::$users = array(
			'sysop' => new ApiTestUser(
				'Apitestsysop',
				'Api Test Sysop',
				'api_test_sysop@example.com',
				array( 'sysop' )
			),
			'uploader' => new ApiTestUser(
				'Apitestuser',
				'Api Test User',
				'api_test_user@example.com',
				array()
			)
		);

		$wgUser = self::$users['sysop']->user;

		$this->apiContext = new ApiTestContext();

	}

	protected function doApiRequest( $params, $session = null, $appendModule = false, $user = null ) {
		if ( is_null( $session ) ) {
			$session = array();
		}

		$context = $this->apiContext->newTestContext( $params, $session, $user );
		$module = new ApiMain( $context, true );
		$module->execute();

		$results = array(
			$module->getResultData(),
			$context->getRequest(),
			$context->getRequest()->getSessionArray()
		);
		if( $appendModule ) {
			$results[] = $module;
		}

		return $results;
	}

	/**
	 * Add an edit token to the API request
	 * This is cheating a bit -- we grab a token in the correct format and then add it to the pseudo-session and to the
	 * request, without actually requesting a "real" edit token
	 * @param $params: key-value API params
	 * @param $session: session array
	 * @param $user String|null A User object for the context 
	 */
	protected function doApiRequestWithToken( $params, $session, $user = null ) {
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

	protected function doLogin() {
		$data = $this->doApiRequest( array(
			'action' => 'login',
			'lgname' => self::$users['sysop']->username,
			'lgpassword' => self::$users['sysop']->password ) );

		$token = $data[0]['login']['token'];

		$data = $this->doApiRequest( array(
			'action' => 'login',
			'lgtoken' => $token,
			'lgname' => self::$users['sysop']->username,
			'lgpassword' => self::$users['sysop']->password
			), $data );

		return $data;
	}

	protected function getTokenList( $user ) {
		$data = $this->doApiRequest( array(
			'action' => 'query',
			'titles' => 'Main Page',
			'intoken' => 'edit|delete|protect|move|block|unblock',
			'prop' => 'info' ), false, $user->user );
		return $data;
	}
}

class UserWrapper {
	public $userName, $password, $user;

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
	public function execute() { }
	public function getVersion() { }

	public function __construct() { }

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
	 * @param $params Array key-value API params
	 * @param $session Array session data
	 * @param $user User or null
	 * @return DerivativeContext
	 */
	public function newTestContext( $params, $session, $user = null ) {
		$context = new DerivativeContext( $this );
		$context->setRequest( new FauxRequest( $params, true, $session ) );
		if ( $user !== null ) {
			$context->setUser( $user );
		}
		return $context;
	}
}
