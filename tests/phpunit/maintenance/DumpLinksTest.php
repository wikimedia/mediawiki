<?php

namespace MediaWiki\Tests\Maintenance;

use DumpLinks;
use MediaWiki\Title\Title;

/**
 * @covers \DumpLinks
 * @group Database
 * @author Dreamy Jazz
 */
class DumpLinksTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return DumpLinks::class;
	}

	public function testExecuteWithNoPages() {
		// Expect no output if the wiki has no pages
		$this->maintenance->execute();
		$this->expectOutputRegex( '/^$/' );
	}

	public function testExecuteWithNoWikiLinksInPages() {
		$this->getExistingTestPage( Title::newFromText( 'Main Page' ) );
		$this->getExistingTestPage( Title::newFromText( 'Main Page2' ) );

		// Expect no output if the wiki has no wikilinks between pages
		$this->maintenance->execute();
		$this->expectOutputRegex( '/^$/' );
	}

	public function testExecuteWithWikiLinksBetweenPages() {
		$this->editPage(
			Title::newFromText( 'Main Page' ),
			'[[Main Page2]] [[Non-existing page]]'
		);
		$this->editPage(
			Title::newFromText( 'Main Page2' ),
			'[[Main Page]]'
		);

		$this->maintenance->execute();
		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( 'Main_Page Main_Page2 Non-existing_page', $actualOutput );
		$this->assertStringContainsString( 'Main_Page2 Main_Page', $actualOutput );
	}
}
