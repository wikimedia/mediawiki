<?php
/**
 * @group Database
 * @covers CoreParserFunctions
 */
class CoreParserFunctionsTest extends MediaWikiTestCase {

	public function testGender() {
		$user = User::createNew( '*Female' );
		$user->setOption( 'gender', 'female' );
		$user->saveSettings();

		$msg = ( new RawMessage( '{{GENDER:*Female|m|f|o}}' ) )->parse();
		$this->assertEquals( $msg, 'f', 'Works unescaped' );
		$escapedName = wfEscapeWikiText( '*Female' );
		$msg2 = ( new RawMessage( '{{GENDER:' . $escapedName . '|m|f|o}}' ) )
			->parse();
		$this->assertEquals( $msg, 'f', 'Works escaped' );
	}

}
