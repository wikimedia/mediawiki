<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\Content\TextContent;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Tests\Mocks\Content\DummyNonTextContent;
use MediaWiki\Tests\Mocks\Content\DummyNonTextContentHandler;
use ViewCLI;

/**
 * @covers \ViewCLI
 * @group Database
 * @author Dreamy Jazz
 */
class ViewCLITest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return ViewCLI::class;
	}

	public function testExecute() {
		$testPage = $this->getExistingTestPage();
		// Call ::execute
		$this->maintenance->setArg( 'title', $testPage );
		$this->maintenance->execute();
		// Verify that the content of the last revision of the page was outputted.
		$content = $testPage->getContent( RevisionRecord::RAW );
		$this->assertInstanceOf( TextContent::class, $content );
		$expectedOutput = $content->getText();
		$this->expectOutputString( $expectedOutput );
	}

	/** @dataProvider provideExecuteForInvalidTitles */
	public function testExecuteForInvalidTitles( string $title, string $expectedOutputRegex ) {
		$this->expectCallToFatalError();
		$this->expectOutputRegex( $expectedOutputRegex );
		// Call ::execute
		$this->maintenance->setArg( 'title', $title );
		$this->maintenance->execute();
	}

	public static function provideExecuteForInvalidTitles() {
		return [
			'Empty title' => [ '', '/Invalid title/' ],
			'Special page title' => [ 'Special:Test', '/Special Pages not supported/' ],
			'Non-existent title' => [ 'Non-existing-test-page-1234', '/Page does not exist/' ],
		];
	}

	public function testExecuteForNonWikitextPage() {
		$this->mergeMwGlobalArrayValue( 'wgContentHandlers', [
			'testing-nontext' => DummyNonTextContentHandler::class,
		] );
		$this->editPage( 'ThisPageIsNotInWikitext', new DummyNonTextContent( 'Hello' ), 'Test', NS_MAIN, $this->getTestSysop()->getAuthority() );
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Non-text content models not supported/' );
		$this->maintenance->setArg( 'title', 'ThisPageIsNotInWikitext' );
		$this->maintenance->execute();
	}

}
