<?php

/**
 * @group API
 * @group medium
 *
 * @covers ApiMain
 */
class ApiMainTest extends ApiTestCase {

	/**
	 * Test that the API will accept a FauxRequest and execute.
	 */
	public function testApi() {
		$api = new ApiMain(
			new FauxRequest( array( 'action' => 'query', 'meta' => 'siteinfo' ) )
		);
		$api->execute();
		$data = $api->getResult()->getResultData();
		$this->assertInternalType( 'array', $data );
		$this->assertArrayHasKey( 'query', $data );
	}

	public static function provideAssert() {
		return array(
			array( false, array(), 'user', 'assertuserfailed' ),
			array( true, array(), 'user', false ),
			array( true, array(), 'bot', 'assertbotfailed' ),
			array( true, array( 'bot' ), 'user', false ),
			array( true, array( 'bot' ), 'bot', false ),
		);
	}

	/**
	 * Tests the assert={user|bot} functionality
	 *
	 * @covers ApiMain::checkAsserts
	 * @dataProvider provideAssert
	 * @param bool $registered
	 * @param array $rights
	 * @param string $assert
	 * @param string|bool $error False if no error expected
	 */
	public function testAssert( $registered, $rights, $assert, $error ) {
		$user = new User();
		if ( $registered ) {
			$user->setId( 1 );
		}
		$user->mRights = $rights;
		try {
			$this->doApiRequest( array(
				'action' => 'query',
				'assert' => $assert,
			), null, null, $user );
			$this->assertFalse( $error ); // That no error was expected
		} catch ( UsageException $e ) {
			$this->assertEquals( $e->getCodeString(), $error );
		}
	}

	/**
	 * Test if all classes in the main module manager exists
	 */
	public function testClassNamesInModuleManager() {
		global $wgAutoloadLocalClasses, $wgAutoloadClasses;

		// wgAutoloadLocalClasses has precedence, just like in includes/AutoLoader.php
		$classes = $wgAutoloadLocalClasses + $wgAutoloadClasses;

		$api = new ApiMain(
			new FauxRequest( array( 'action' => 'query', 'meta' => 'siteinfo' ) )
		);
		$modules = $api->getModuleManager()->getNamesWithClasses();
		foreach( $modules as $name => $class ) {
			$this->assertArrayHasKey(
				$class,
				$classes,
				'Class ' . $class . ' for api module ' . $name . ' not in autoloader (with exact case)'
			);
		}
	}
}
