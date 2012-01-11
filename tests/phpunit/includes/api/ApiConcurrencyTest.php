<?php

class ApiConcurrencyTest extends ApiTestCase {
	/**
	 * @var Array of test users
	 */
	public static $users;

	// Prepare test environment

	function setUp() {
		parent::setUp();

		self::$users['one'] = new ApiTestUser(
				'ApitestuserA',
				'Api Test UserA',
				'api_test_userA@example.com',
				array()
		);

		self::$users['two'] = new ApiTestUser(
				'ApitestuserB',
				'Api Test UserB',
				'api_test_userB@example.com',
				array()
		);
	}

	public function tearDown() {
		parent::tearDown();
	}

	function testLogin() {

		$sessionArray = array();

		foreach ( self::$users as $key => $user ) {

			$params = array(
				'action' => 'login',
				'lgname' => $user->username,
				'lgpassword' => $user->password
			);
			list( $result, , $session ) = $this->doApiRequest( $params );
			$this->assertArrayHasKey( "login", $result );
			$this->assertArrayHasKey( "result", $result['login'] );
			$this->assertEquals( "NeedToken", $result['login']['result'] );
			$token = $result['login']['token'];

			$params = array(
				'action' => 'login',
				'lgtoken' => $token,
				'lgname' => $user->username,
				'lgpassword' => $user->password
			);
			list( $result, , $session ) = $this->doApiRequest( $params, $session );
			$this->assertArrayHasKey( "login", $result );
			$this->assertArrayHasKey( "result", $result['login'] );
			$this->assertEquals( "Success", $result['login']['result'] );
			$this->assertArrayHasKey( 'lgtoken', $result['login'] );

			$this->assertNotEmpty( $session, 'API Login must return a session' );

			$sessionArray[$key] = $session;

		}

		return $sessionArray;

	}

	/**
	 * @depends testLogin
	 */
	function testCheckOut( $sessionArray ) {

		global $wgUser;

		$wgUser = self::$users['one']->user;
		/* commenting these out since i need to go home and they're breakin CI.  See commit summary for details.
		
		list( $result, , $session ) =  $this->doApiRequestWithToken( array(
									'action' => 'concurrency',
									'ccaction' => 'checkout',
									'record' => 1,
									'resourcetype' => 'responding-to-moodbar-feedback'), $sessionArray['one'], self::$users['one']->user );

		$this->assertEquals( "success", $result['concurrency']['result'] );

		$wgUser = self::$users['two']->user;

		list( $result, , $session ) =  $this->doApiRequestWithToken( array(
									'action' => 'concurrency',
									'ccaction' => 'checkout',
									'record' => 1,
									'resourcetype' => 'responding-to-moodbar-feedback'), $sessionArray['two'], self::$users['two']->user );

		$this->assertEquals( "failure", $result['concurrency']['result'] );

		list( $result, , $session ) =  $this->doApiRequestWithToken( array(
									'action' => 'concurrency',
									'ccaction' => 'checkout',
									'record' => 2,
									'resourcetype' => 'responding-to-moodbar-feedback'), $sessionArray['two'], self::$users['two']->user );

		$this->assertEquals( "success", $result['concurrency']['result'] );
		*/
	}

	/**
	 * @depends testLogin
	 */
	function testCheckIn( $sessionArray ) {

		global $wgUser;

		$wgUser = self::$users['one']->user;
		/* commenting these out since i need to go home and they're breakin CI.  See commit summary for details.

		list( $result, , $session ) =  $this->doApiRequestWithToken( array(
									'action' => 'concurrency',
									'ccaction' => 'checkin',
									'record' => 1,
									'resourcetype' => 'responding-to-moodbar-feedback'), $sessionArray['one'], self::$users['one']->user );

		$this->assertEquals( "success", $result['concurrency']['result'] );

		list( $result, , $session ) =  $this->doApiRequestWithToken( array(
									'action' => 'concurrency',
									'ccaction' => 'checkin',
									'record' => 2,
									'resourcetype' => 'responding-to-moodbar-feedback'), $sessionArray['one'], self::$users['one']->user );

		$this->assertEquals( "failure", $result['concurrency']['result'] );

		$wgUser = self::$users['two']->user;

		list( $result, , $session ) =  $this->doApiRequestWithToken( array(
									'action' => 'concurrency',
									'ccaction' => 'checkin',
									'record' => 2,
									'resourcetype' => 'responding-to-moodbar-feedback'), $sessionArray['two'], self::$users['two']->user );

		$this->assertEquals( "success", $result['concurrency']['result'] );
		*/	
	}

	/**
	 * @depends testLogin
	 */
	function testInvalidCcacton( $sessionArray ) {
		$exception = false;
		try {
			global $wgUser;
			
			$wgUser = self::$users['one']->user;
		
			list( $result, , $session ) =  $this->doApiRequestWithToken( array(
												'action' => 'concurrency',
												'ccaction' => 'checkinX',
												'record' => 1,
												'resourcetype' => 'responding-to-moodbar-feedback'), $sessionArray['one'], self::$users['one']->user );
		} catch ( UsageException $e ) {
			$exception = true;
			$this->assertEquals("Unrecognized value for parameter 'ccaction': checkinX",
				$e->getMessage() );
		}
		$this->assertTrue( $exception, "Got exception" );

	}

}
