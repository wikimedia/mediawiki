<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\Page\WikiPage;
use PHPUnit\Framework\ExpectationFailedException;
use PurgePage;

/**
 * @covers \PurgePage
 * @group Database
 * @author Dreamy Jazz
 */
class PurgePageTest extends MaintenanceBaseTestCase {

	/** @var SemiMockedPurgePage */
	protected $maintenance;

	protected function getMaintenanceClass() {
		return SemiMockedPurgePage::class;
	}

	private function getFileWithContent( string $content ): string {
		$testFilename = $this->getNewTempFile();
		$testFile = fopen( $testFilename, 'w' );
		fwrite( $testFile, $content );
		fclose( $testFile );
		return $testFilename;
	}

	public function testPurgeForInvalidTitle() {
		$this->setTemporaryHook( 'ArticlePurge', function () {
			$this->fail( 'No pages should be purged if the title is invalid.' );
		} );
		$this->maintenance->mockStdin( $this->getFileWithContent( ":::\n\n\n\n" ) );
		$this->maintenance->execute();
		$this->expectOutputRegex( '/Invalid page title/' );
	}

	public function testPurgeForNonExistingPage() {
		$this->setTemporaryHook( 'ArticlePurge', function () {
			$this->fail(
				'No pages should be purged if the title does not exist and --skip-exists-check is not specified.'
			);
		} );
		$this->maintenance->mockStdin( $this->getFileWithContent( "NonExistingTestPage1234" ) );
		$this->maintenance->execute();
		$this->expectOutputRegex( '/Page doesn\'t exist/' );
	}

	public function testPurgeForFailedPurge() {
		// Expect that the ArticlePurge hook is called, as a purge attempt should be made.
		$title = $this->getExistingTestPage()->getTitle();
		$hookCalled = false;
		$this->setTemporaryHook(
			'ArticlePurge',
			function ( WikiPage $actualTitle ) use ( &$hookCalled, $title ) {
				$this->assertTrue( $actualTitle->isSamePageAs( $title ) );
				$hookCalled = true;
				return false;
			}
		);
		// Call the maintenance script and expect that the purge failed (because of the hook).
		$this->maintenance->mockStdin( $this->getFileWithContent( $title ) );
		$this->maintenance->execute();
		$this->expectOutputRegex( '/Purge failed for ' . preg_quote( $title, '/' ) . '/' );
		$this->assertTrue( $hookCalled );
	}

	public function testPurge() {
		// Expect that the ArticlePurge hook is called, as a purge attempt should be made.
		$title = $this->getExistingTestPage()->getTitle();
		$hookCalled = false;
		$this->setTemporaryHook(
			'ArticlePurge',
			function ( WikiPage $actualTitle ) use ( &$hookCalled, $title ) {
				$this->assertTrue( $actualTitle->isSamePageAs( $title ) );
				$hookCalled = true;
			}
		);
		// Call the maintenance script and expect that the purge worked.
		$this->maintenance->mockStdin( $this->getFileWithContent( $title ) );
		$this->maintenance->execute();
		$this->expectOutputRegex( '/Purged ' . preg_quote( $title, '/' ) . '/' );
		$this->assertTrue( $hookCalled );
	}
}

/**
 * The PurgePage maintenance script with the input mocked to allow easier testing.
 */
class SemiMockedPurgePage extends PurgePage {

	/**
	 * @var string|null The filename to a file which contains the mock input to the script.
	 */
	private ?string $mockStdinFile = null;

	/**
	 * Data for the fake stdin
	 *
	 * @param string $filepath The string to be used instead of stdin
	 */
	public function mockStdin( string $filepath ) {
		$this->mockStdinFile = $filepath;
	}

	public function getStdin( $len = null ) {
		if ( $len !== null ) {
			throw new ExpectationFailedException( "::getStdin call was expected to not pass any arguments" );
		}

		return fopen( $this->mockStdinFile, 'rt' );
	}
}
