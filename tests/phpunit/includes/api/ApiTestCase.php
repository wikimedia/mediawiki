<?php 

abstract class ApiTestCase extends MediaWikiTestCase {
	public static $users;

	function setUp() {
		global $wgContLang, $wgAuth, $wgMemc, $wgRequest, $wgUser;

		$wgMemc = new EmptyBagOStuff();
		$wgContLang = Language::factory( 'en' );
		$wgAuth = new StubObject( 'wgAuth', 'AuthPlugin' );
		$wgRequest = new FauxRequest( array() );

		self::$users = array(
			'sysop' => new ApiTestUser(
				'Apitestsysop',
				'Api Test Sysop',
				'api_test_sysop@sample.com',
				array( 'sysop' )
			),
			'uploader' => new ApiTestUser(
				'Apitestuser',
				'Api Test User',
				'api_test_user@sample.com',
				array()
			)
		);

		$wgUser = self::$users['sysop']->user;

	}

	protected function doApiRequest( $params, $session = null, $appendModule = false ) {
		if ( is_null( $session ) ) {
			$session = array();
		}

		$request = new FauxRequest( $params, true, $session );
		$module = new ApiMain( $request, true );
		$module->execute();

		return array( $module->getResultData(), $request, $request->getSessionArray() );
	}

	/**
	 * Add an edit token to the API request
	 * This is cheating a bit -- we grab a token in the correct format and then add it to the pseudo-session and to the
	 * request, without actually requesting a "real" edit token
	 * @param $params: key-value API params
	 * @param $session: session array
	 */
	protected function doApiRequestWithToken( $params, $session ) {
		if ( $session['wsToken'] ) {
			// add edit token to fake session
			$session['wsEditToken'] = $session['wsToken'];
			// add token to request parameters
			$params['token'] = md5( $session['wsToken'] ) . User::EDIT_TOKEN_SUFFIX;
			return $this->doApiRequest( $params, $session );
		} else {
			throw new Exception( "request data not in right format" );
		}
	}

}
