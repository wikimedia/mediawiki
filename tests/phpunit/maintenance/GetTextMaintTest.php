<?php

namespace MediaWiki\Tests\Maintenance;

use GetTextMaint;
use MediaWiki\Revision\RevisionRecord;

/**
 * @covers \GetTextMaint
 * @group Database
 * @author Dreamy Jazz
 */
class GetTextMaintTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return GetTextMaint::class;
	}

	public function testExecute() {
		$testPage = $this->getExistingTestPage();
		// Call ::execute
		$this->maintenance->setArg( 'title', $testPage );
		$this->maintenance->execute();
		// Verify that the content of the last revision of the page was outputted.
		$expectedOutput = $testPage->getContent( RevisionRecord::RAW )->serialize();
		if ( stream_isatty( STDOUT ) ) {
			$expectedOutput .= "\n";
		}
		$this->expectOutputString( $expectedOutput );
	}

	public function testExecuteForOldRevision() {
		// Get a test page with two revisions
		$testPage = $this->getExistingTestPage();
		$firstRevContent = $testPage->getContent( RevisionRecord::RAW )->serialize();
		$this->editPage( $testPage, 'testing1234' );
		// Call ::execute
		$this->maintenance->setArg( 'title', $testPage );
		$this->maintenance->setOption(
			'revision', $this->getServiceContainer()->getRevisionLookup()->getFirstRevision( $testPage )->getId()
		);
		$this->maintenance->execute();
		// Verify that the content of the first revision of the page was outputted.
		$expectedOutput = $firstRevContent;
		if ( stream_isatty( STDOUT ) ) {
			$expectedOutput .= "\n";
		}
		$this->expectOutputString( $expectedOutput );
	}

	/** @dataProvider provideInvalidTitleArgumentValues */
	public function testExecuteForInvalidTitleArgument( $title, $expectedOutputRegex ) {
		$this->expectCallToFatalError();
		$this->expectOutputRegex( $expectedOutputRegex );
		$this->maintenance->setArg( 'title', $title );
		$this->maintenance->execute();
	}

	public static function provideInvalidTitleArgumentValues() {
		return [
			'Invalid title' => [ ':::', '/::: is not a valid title/' ],
			'Title for a non-existent page' => [
				'Non-existing-test-page-1234', '/Non-existing-test-page-1234 does not exist/',
			],
		];
	}
}
