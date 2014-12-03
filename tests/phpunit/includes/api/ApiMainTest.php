<?php

/**
 * @group API
 * @group Database
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
		$anon = new User();
		$bot = new User();
		$bot->setName( 'Bot' );
		$bot->addToDatabase();
		$bot->addGroup( 'bot' );
		$user = new User();
		$user->setName( 'User' );
		$user->addToDatabase();
		return array(
			array( $anon, 'user', 'assertuserfailed' ),
			array( $user, 'user', false ),
			array( $user, 'bot', 'assertbotfailed' ),
			array( $bot, 'user', false ),
			array( $bot, 'bot', false ),
		);
	}

	/**
	 * Tests the assert={user|bot} functionality
	 *
	 * @covers ApiMain::checkAsserts
	 * @dataProvider provideAssert
	 * @param User $user
	 * @param string $assert
	 * @param string|bool $error False if no error expected
	 */
	public function testAssert( $user, $assert, $error ) {
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
