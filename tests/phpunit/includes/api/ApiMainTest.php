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
	 * Test that the API will accept a FauxRequest and execute. The help action
	 * (default) throws a UsageException. Just validate we're getting proper XML
	 *
	 * @expectedException UsageException
	 */
	public function testApi() {
		$api = new ApiMain(
			new FauxRequest( array( 'action' => 'help', 'format' => 'xml' ) )
		);
		$api->execute();
		$api->getPrinter()->setBufferResult( true );
		$api->printResult( false );
		$resp = $api->getPrinter()->getBuffer();

		libxml_use_internal_errors( true );
		$sxe = simplexml_load_string( $resp );
		$this->assertNotInternalType( "bool", $sxe );
		$this->assertThat( $sxe, $this->isInstanceOf( "SimpleXMLElement" ) );
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

}
