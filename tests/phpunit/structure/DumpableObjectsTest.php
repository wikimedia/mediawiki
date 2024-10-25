<?php

use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * Integration test for T277618.
 *
 * Add @noVarDump annotations to large properties if these tests fail.
 *
 * @group Database
 * @coversNothing
 */
class DumpableObjectsTest extends MediaWikiIntegrationTestCase {
	private function dumpSize( $object ) {
		$n = 0;
		ob_start(
			static function ( $buffer ) use ( &$n ) {
				$n += strlen( $buffer );
			},
			4096
		);
		var_dump( $object );
		ob_end_flush();
		return $n;
	}

	public function testUser() {
		$u = new User();
		$u->isAllowed( 'read' );
		$this->assertLessThan( 100000, $this->dumpSize( $u ) );
	}

	public function testTitle() {
		$object = Title::makeTitle( NS_MAIN, 'Test' );
		$this->assertLessThan( 100000, $this->dumpSize( $object ) );
	}

	public function testLanguage() {
		$object = \MediaWiki\MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'en' );
		$this->assertLessThan( 100000, $this->dumpSize( $object ) );
	}

	public function testMessage() {
		$object = wfMessage( 'jan' );
		$this->assertLessThan( 100000, $this->dumpSize( $object ) );
	}
}
