<?php

namespace MediaWiki\Tests\Maintenance;

use DeleteBatch;
use MediaWiki\Page\WikiPage;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;

/**
 * @covers \DeleteBatch
 * @group Database
 * @author Dreamy Jazz
 */
class DeleteBatchTest extends MaintenanceBaseTestCase {
	use MockAuthorityTrait;

	protected function getMaintenanceClass() {
		return DeleteBatch::class;
	}

	/**
	 * @param array $options
	 * @param string $fileContents
	 * @param WikiPage[] $pagesToDelete
	 */
	private function commonTestExecute( array $options, string $fileContents, array $pagesToDelete ) {
		// Add the specified $options
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		// Create a temporary file, write $fileContents to it, and then pass the filename in argv.
		$file = $this->getNewTempFile();
		file_put_contents( $file, $fileContents );
		$this->maintenance->setArg( 'listfile', $file );
		// Call ::execute
		$this->maintenance->execute();
		// Verify that pages are now deleted.
		foreach ( $pagesToDelete as $page ) {
			$page->clear();
			$this->assertFalse( $page->exists(), 'Page was not deleted' );
		}
	}

	public function testExecute() {
		$existingPages = [];
		for ( $i = 0; $i < 4; $i++ ) {
			$existingPages[] = $this->getExistingTestPage();
		}
		// Generate the file contents to pass as the 'listfile' argument and also generate the expected output regex.
		$fileContents = '';
		$expectedOutputRegex = '/';
		foreach ( $existingPages as $page ) {
			$text = $page->getTitle()->getPrefixedText();
			$fileContents .= $text . PHP_EOL;
			$expectedOutputRegex .= ".*Deleted $text!\n";
		}
		$this->expectOutputRegex( $expectedOutputRegex . '/' );
		$this->commonTestExecute( [], $fileContents, $existingPages );
	}

	public function testExecuteForPageIds() {
		$existingPages = [];
		for ( $i = 0; $i < 4; $i++ ) {
			$existingPages[] = $this->getExistingTestPage();
		}
		// Generate the file contents to pass as the 'listfile' argument and also generate the expected output regex.
		$fileContents = '';
		$expectedOutputRegex = '/';
		foreach ( $existingPages as $page ) {
			$pageId = $page->getId();
			$fileContents .= $pageId . PHP_EOL;
			$expectedOutputRegex .= ".*Deleted $pageId!\n";
		}
		$this->expectOutputRegex( $expectedOutputRegex . '/' );
		$this->commonTestExecute( [ 'by-id' => 1 ], $fileContents, $existingPages );
	}

	/** @dataProvider provideExecuteForInvalidPages */
	public function testExecuteForInvalidPages( $options, $fileContents, $expectedOutputRegex ) {
		$this->commonTestExecute( $options, $fileContents, [] );
		$this->expectOutputRegex( $expectedOutputRegex );
	}

	public static function provideExecuteForInvalidPages() {
		return [
			'Invalid page names and empty line' => [
				[],
				"Talk:::Test\n\n~~~~",
				"/Invalid title 'Talk:::Test' on line 1\nInvalid title '~~~~' on line 3\n/"
			],
			'Non-existent page name' => [
				[], "Non-existent-test-page-1234", "/Skipping nonexistent page 'Non-existent-test-page-1234'\n/",
			],
			'Invalid page IDs' => [ [ 'by-id' => 1 ], "test\n", "/Invalid page ID 'test' on line 1\n/" ],
		];
	}
}
